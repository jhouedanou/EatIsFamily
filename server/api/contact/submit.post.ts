/**
 * Contact Form Proxy API Endpoint
 * POST /api/contact/submit
 * 
 * Proxies contact form submissions to WordPress Contact Form 7 API
 * This avoids CORS issues when submitting from the frontend
 */

import { readBody, createError, H3Event } from 'h3'

export default defineEventHandler(async (event: H3Event) => {
  // Only accept POST requests
  if (event.method !== 'POST') {
    throw createError({
      statusCode: 405,
      statusMessage: 'Method Not Allowed'
    })
  }

  try {
    const body = await readBody(event)
    
    const {
      name,
      email,
      eventType,
      location,
      date,
      guests,
      message,
      formId
    } = body

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

    // URL de base WordPress
    const wpBaseUrl = process.env.NUXT_PUBLIC_WP_BASE_URL || 'https://bigfive.dev/eatisfamily'
    
    // Résoudre l'ID numérique si nécessaire
    let numericFormId = formId
    
    if (formId && !/^\d+$/.test(formId)) {
      // C'est un hash, essayer de résoudre
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

    // Créer le FormData pour CF7
    const formData = new FormData()
    formData.append('your-name', name || '')
    formData.append('your-email', email || '')
    formData.append('event-type', eventType || '')
    formData.append('location', location || '')
    formData.append('event-date', date || '')
    formData.append('guests', guests || '')
    formData.append('your-message', message || '')
    formData.append('_wpcf7_unit_tag', `wpcf7-f${numericFormId}-o1`)

    // Envoyer à WordPress CF7
    const cf7Response = await fetch(endpoint, {
      method: 'POST',
      body: formData,
      headers: {
        'Accept': 'application/json',
      },
    })

    console.log(`[Contact Proxy] CF7 response status: ${cf7Response.status}`)

    const responseText = await cf7Response.text()
    console.log(`[Contact Proxy] CF7 response body:`, responseText)

    // Tenter de parser le JSON
    let result
    try {
      result = JSON.parse(responseText)
    } catch (parseError) {
      console.error('[Contact Proxy] Failed to parse CF7 response:', parseError)
      
      // Si la requête HTTP a réussi, le mail a probablement été envoyé
      if (cf7Response.ok) {
        return {
          status: 'mail_sent',
          message: 'Votre message a été envoyé avec succès.'
        }
      }
      
      throw createError({
        statusCode: 502,
        statusMessage: 'Bad Gateway',
        data: {
          status: 'mail_failed',
          message: 'Erreur de communication avec le serveur mail.'
        }
      })
    }

    // Retourner la réponse CF7 telle quelle
    return result

  } catch (error: any) {
    console.error('[Contact Proxy] Error:', error)
    
    // Si l'erreur est déjà une H3Error, la propager
    if (error.statusCode) {
      throw error
    }

    // Erreur réseau ou autre
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
