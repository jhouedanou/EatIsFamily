/**
 * Composable for Contact Form submission with file upload
 *
 * Submits form data with optional file attachment to the server API
 * which proxies to WordPress Contact Form 7
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
   * Submit form data with optional file attachment
   */
  const submitContactForm = async (formData: ContactFormData): Promise<CF7Response> => {
    const formId = getFormId()

    if (!formId) {
      return {
        status: 'mail_failed',
        message: 'Le formulaire de contact n\'est pas configuré. Contactez l\'administrateur.',
      }
    }

    try {
      const body = new FormData()
      body.append('name', formData.name)
      body.append('email', formData.email)
      body.append('eventType', formData.eventType)
      body.append('location', formData.location)
      body.append('date', formData.date)
      body.append('guests', formData.guests)
      body.append('message', formData.message)
      body.append('formId', formId)

      // Attach file if present
      if (formData.attachmentFile) {
        body.append('attachment', formData.attachmentFile, formData.attachmentFile.name)
      }

      const response = await fetch('/api/contact/submit', {
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
