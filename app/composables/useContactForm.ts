/**
 * Composable for Contact Form submission with file upload
 *
 * Submits form data directly to WordPress Contact Form 7 REST API
 * (same domain, no CORS issues, no need for Nuxt server proxy)
 */

export interface CF7Response {
  status: 'mail_sent' | 'mail_failed' | 'validation_failed' | 'spam' | 'aborted'
  message: string
  posted_data_hash?: string
  into?: string
  invalid_fields?: Array<{
    field: string
    message: string
    idref: string | null
    error_id: string
  }>
}

export interface ContactFormData {
  name: string
  email: string
  eventType: string
  location: string
  date: string
  guests: string
  message: string
  attachmentFile: File | null
}

const ALLOWED_EXTENSIONS = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png', '.webp']
const MAX_FILE_SIZE = 2 * 1024 * 1024 // 2MB

// WordPress CF7 API base (WordPress is at /api/ on the same domain)
const WP_CF7_BASE = '/api/wp-json/contact-form-7/v1/contact-forms'

export const useContactForm = () => {
  const { settings } = useGlobalSettings()

  const getFormId = (): string => {
    return settings.value?.contact_form?.cf7_form_id || '342'
  }

  const isValidFileType = (file: File): boolean => {
    const ext = file.name.toLowerCase().slice(file.name.lastIndexOf('.'))
    return ALLOWED_EXTENSIONS.includes(ext)
  }

  const isValidFileSize = (file: File): boolean => {
    return file.size <= MAX_FILE_SIZE
  }

  /**
   * Resolve CF7 form ID (hash → numeric) if needed
   */
  const resolveFormId = async (formId: string): Promise<string> => {
    // If already numeric, return as-is
    if (/^\d+$/.test(formId)) {
      return formId
    }
    // Try to resolve hash to numeric ID via WordPress custom endpoint
    try {
      const res = await fetch(`/api/wp-json/eatisfamily/v1/cf7-form-id/${formId}`)
      if (res.ok) {
        const data = await res.json()
        console.log(`[Contact] Resolved form hash ${formId} → ${data.numeric_id}`)
        return String(data.numeric_id)
      }
    } catch (e) {
      console.warn('[Contact] Could not resolve form hash, using as-is:', e)
    }
    return formId
  }

  /**
   * Submit form data directly to WordPress CF7 REST API
   * No server proxy needed — same domain, no CORS issues
   */
  const submitContactForm = async (formData: ContactFormData): Promise<CF7Response> => {
    const rawFormId = getFormId()

    if (!rawFormId) {
      return {
        status: 'mail_failed',
        message: 'Le formulaire de contact n\'est pas configuré. Contactez l\'administrateur.',
      }
    }

    try {
      // Resolve form ID (hash → numeric if needed)
      const formId = await resolveFormId(rawFormId)

      // Build FormData with CF7 field names
      const body = new FormData()
      body.append('your-name', formData.name)
      body.append('your-email', formData.email)
      body.append('event-type', formData.eventType)
      body.append('location', formData.location)
      body.append('event-date', formData.date)
      body.append('guests', formData.guests)
      body.append('your-message', formData.message)
      body.append('_wpcf7_unit_tag', `wpcf7-f${formId}-o1`)

      // Attach file if present
      if (formData.attachmentFile) {
        body.append('attachment', formData.attachmentFile, formData.attachmentFile.name)
      }

      const endpoint = `${WP_CF7_BASE}/${formId}/feedback`
      console.log(`[Contact] Submitting directly to CF7: ${endpoint}`)

      const response = await fetch(endpoint, {
        method: 'POST',
        body,
        headers: { 'Accept': 'application/json' },
      })

      console.log(`[Contact] CF7 response status: ${response.status}`)

      const responseText = await response.text()

      let result: CF7Response
      try {
        result = JSON.parse(responseText)
      } catch (parseError) {
        console.error('[Contact] Failed to parse CF7 response:', responseText.substring(0, 200))
        // If we got a 200 but can't parse, assume success
        if (response.ok) {
          return {
            status: 'mail_sent',
            message: 'Votre message a été envoyé avec succès.',
          }
        }
        return {
          status: 'mail_failed',
          message: 'Erreur de communication avec le serveur. Veuillez réessayer.',
        }
      }

      return result

    } catch (error) {
      console.error('[ContactForm] Error submitting form:', error)
      return {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi du formulaire. Veuillez réessayer.',
      }
    }
  }

  const isValidEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  const validateForm = (formData: ContactFormData): { valid: boolean; errors: string[] } => {
    const errors: string[] = []

    if (!formData.name.trim()) {
      errors.push('Le nom est requis')
    }

    if (!formData.email.trim()) {
      errors.push('L\'email est requis')
    } else if (!isValidEmail(formData.email)) {
      errors.push('L\'email n\'est pas valide')
    }

    if (!formData.message.trim()) {
      errors.push('Le message est requis')
    }

    if (formData.attachmentFile) {
      if (!isValidFileType(formData.attachmentFile)) {
        errors.push(`Type de fichier non autorisé. Formats acceptés: ${ALLOWED_EXTENSIONS.join(', ')}`)
      }
      if (!isValidFileSize(formData.attachmentFile)) {
        errors.push('Le fichier est trop volumineux. Taille maximum: 2MB')
      }
    }

    return {
      valid: errors.length === 0,
      errors
    }
  }

  return {
    submitContactForm,
    validateForm,
    isValidEmail,
    isValidFileType,
    isValidFileSize,
    getFormId
  }
}
