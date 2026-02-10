/**
 * Composable for Contact Form 7 API integration
 *
 * API base: https://www.eatisfamily.fr/api
 * CF7 endpoint: POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback
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
}

const API_BASE = 'https://www.eatisfamily.fr/api'

export const useContactForm = () => {
  const { settings } = useGlobalSettings()

  const getFormId = (): string => {
    return settings.value?.contact_form?.cf7_form_id || '342'
  }

  /**
   * Submit form data to Contact Form 7 via the API
   */
  const submitContactForm = async (formData: ContactFormData): Promise<CF7Response> => {
    const formId = getFormId()

    if (!formId) {
      console.error('[ContactForm] No form ID configured')
      return {
        status: 'mail_failed',
        message: 'Le formulaire de contact n\'est pas configuré. Contactez l\'administrateur.',
      }
    }

    // Résoudre l'ID numérique si c'est un hash
    let numericFormId = formId
    if (!/^\d+$/.test(formId)) {
      try {
        const resolveResponse = await fetch(`${API_BASE}/wp-json/eatisfamily/v1/cf7-form-id/${formId}`)
        if (resolveResponse.ok) {
          const data = await resolveResponse.json()
          numericFormId = String(data.numeric_id)
        }
      } catch (error) {
        console.warn('[ContactForm] Could not resolve hash ID:', error)
      }
    }

    const endpoint = `${API_BASE}/wp-json/contact-form-7/v1/contact-forms/${numericFormId}/feedback`

    try {
      const body = new FormData()
      body.append('your-name', formData.name)
      body.append('your-email', formData.email)
      body.append('event-type', formData.eventType)
      body.append('location', formData.location)
      body.append('event-date', formData.date)
      body.append('guests', formData.guests)
      body.append('your-message', formData.message)
      body.append('_wpcf7_unit_tag', `wpcf7-f${numericFormId}-o1`)

      const response = await fetch(endpoint, {
        method: 'POST',
        body,
        headers: { 'Accept': 'application/json' },
      })

      const result = await response.json()

      if (result.data && result.data.status) {
        return result.data as CF7Response
      }

      return result as CF7Response

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

    return {
      valid: errors.length === 0,
      errors
    }
  }

  return {
    submitContactForm,
    validateForm,
    isValidEmail,
    getFormId
  }
}
