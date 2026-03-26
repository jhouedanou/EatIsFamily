/**
 * Contact Form Proxy API Endpoint
 * POST /_api/contact/submit
 *
 * Handles contact form submissions with optional file attachment
 * Stores file locally, then forwards form data to WordPress Contact Form 7 API
 */

import { readMultipartFormData, createError, H3Event } from 'h3'
import { writeFile, mkdir } from 'fs/promises'
import { join } from 'path'
import { existsSync } from 'fs'

// Upload directory for contact attachments (outside public folder for security)
const CONTACT_UPLOADS_DIR = join(process.cwd(), 'server', 'uploads', 'contact')

// Allowed file extensions for contact attachments
const ALLOWED_EXTENSIONS = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png', '.webp']
const ALLOWED_MIME_TYPES = [
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'image/jpeg',
  'image/png',
  'image/webp'
]
const MAX_FILE_SIZE = 2 * 1024 * 1024 // 2MB

export default defineEventHandler(async (event: H3Event) => {
  if (event.method !== 'POST') {
    throw createError({
      statusCode: 405,
      statusMessage: 'Method Not Allowed'
    })
  }

  try {
    // Parse multipart form data (supports both file upload and text fields)
    const multipartData = await readMultipartFormData(event)

    if (!multipartData || multipartData.length === 0) {
      throw createError({
        statusCode: 400,
        statusMessage: 'Bad Request',
        data: { status: 'validation_failed', message: 'Données du formulaire manquantes.' }
      })
    }

    // Extract fields and file
    const fields: Record<string, string> = {}
    let attachmentFile: { data: Buffer; filename: string; type: string } | null = null

    for (const field of multipartData) {
      if (field.name === 'attachment' && field.filename) {
        attachmentFile = {
          data: field.data,
          filename: field.filename,
          type: field.type || 'application/octet-stream'
        }
      } else if (field.name && field.data) {
        fields[field.name] = field.data.toString('utf-8')
      }
    }

    const { name, email, eventType, location, date, guests, message, formId } = fields

    // Validation basique
    if (!name || !email || !message) {
      throw createError({
        statusCode: 400,
        statusMessage: 'Bad Request',
        data: {
          status: 'validation_failed',
          message: 'Les champs nom, email et message sont requis.'
        }
      })
    }

    // Validate and store attachment file if present
    let storedFilename = ''
    if (attachmentFile) {
      // Check extension
      const ext = attachmentFile.filename.toLowerCase().slice(attachmentFile.filename.lastIndexOf('.'))
      if (!ALLOWED_EXTENSIONS.includes(ext)) {
        throw createError({
          statusCode: 400,
          statusMessage: 'Invalid File Type',
          data: { status: 'validation_failed', message: `Type de fichier non autorisé. Formats acceptés: ${ALLOWED_EXTENSIONS.join(', ')}` }
        })
      }

      // Check MIME type
      if (!ALLOWED_MIME_TYPES.includes(attachmentFile.type)) {
        throw createError({
          statusCode: 400,
          statusMessage: 'Invalid File Type',
          data: { status: 'validation_failed', message: 'Type MIME non autorisé.' }
        })
      }

      // Check size
      if (attachmentFile.data.length > MAX_FILE_SIZE) {
        throw createError({
          statusCode: 400,
          statusMessage: 'File Too Large',
          data: { status: 'validation_failed', message: 'Le fichier est trop volumineux. Taille maximum: 2MB.' }
        })
      }

      // Create upload directory if needed
      if (!existsSync(CONTACT_UPLOADS_DIR)) {
        await mkdir(CONTACT_UPLOADS_DIR, { recursive: true })
      }

      // Generate secure filename
      const uuid = crypto.randomUUID()
      const timestamp = Date.now()
      storedFilename = `${uuid}-${timestamp}${ext}`
      const filePath = join(CONTACT_UPLOADS_DIR, storedFilename)

      await writeFile(filePath, attachmentFile.data)
      console.log(`[Contact] File saved: ${storedFilename} (${attachmentFile.data.length} bytes)`)
    }

    // WordPress CF7 submission
    const wpBaseUrl = process.env.NUXT_PUBLIC_WP_BASE_URL || 'https://www.eatisfamily.fr/api'

    // Resolve form ID
    let numericFormId = formId
    if (formId && !/^\d+$/.test(formId)) {
      try {
        const resolveResponse = await fetch(`${wpBaseUrl}/wp-json/eatisfamily/v1/cf7-form-id/${formId}`)
        if (resolveResponse.ok) {
          const resolveData = await resolveResponse.json()
          numericFormId = String(resolveData.numeric_id)
          console.log(`[Contact Proxy] Resolved hash ${formId} to numeric ID ${numericFormId}`)
        }
      } catch (resolveError) {
        console.warn('[Contact Proxy] Could not resolve hash ID:', resolveError)
      }
    }

    if (!numericFormId) {
      throw createError({
        statusCode: 400,
        statusMessage: 'Bad Request',
        data: {
          status: 'mail_failed',
          message: 'Le formulaire de contact n\'est pas configuré (ID manquant).'
        }
      })
    }

    const endpoint = `${wpBaseUrl}/wp-json/contact-form-7/v1/contact-forms/${numericFormId}/feedback`
    console.log(`[Contact Proxy] Submitting to: ${endpoint}`)

    // Build FormData for CF7
    const formData = new FormData()
    formData.append('your-name', name || '')
    formData.append('your-email', email || '')
    formData.append('event-type', eventType || '')
    formData.append('location', location || '')
    formData.append('event-date', date || '')
    formData.append('guests', guests || '')
    formData.append('your-message', message || '')
    formData.append('_wpcf7_unit_tag', `wpcf7-f${numericFormId}-o1`)

    // If a file was uploaded, send it to CF7 as file attachment
    if (attachmentFile && storedFilename) {
      const blob = new Blob([new Uint8Array(attachmentFile.data)], { type: attachmentFile.type })
      formData.append('attachment', blob, attachmentFile.filename)
    }

    // Send to WordPress CF7
    const cf7Response = await fetch(endpoint, {
      method: 'POST',
      body: formData,
      headers: { 'Accept': 'application/json' },
    })

    console.log(`[Contact Proxy] CF7 response status: ${cf7Response.status}`)

    const responseText = await cf7Response.text()
    console.log(`[Contact Proxy] CF7 response body:`, responseText)

    let result
    try {
      result = JSON.parse(responseText)
    } catch (parseError) {
      console.error('[Contact Proxy] Failed to parse CF7 response:', parseError)
      if (cf7Response.ok) {
        return {
          status: 'mail_sent',
          message: 'Votre message a été envoyé avec succès.'
        }
      }
      throw createError({
        statusCode: 502,
        statusMessage: 'Bad Gateway',
        data: { status: 'mail_failed', message: 'Erreur de communication avec le serveur mail.' }
      })
    }

    return result

  } catch (error: any) {
    console.error('[Contact Proxy] Error:', error)
    if (error.statusCode) {
      throw error
    }
    throw createError({
      statusCode: 500,
      statusMessage: 'Internal Server Error',
      data: {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi du formulaire. Veuillez réessayer.'
      }
    })
  }
})
