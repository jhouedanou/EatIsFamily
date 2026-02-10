/**
 * Composable for Job Application Form via Contact Form 7 API
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

export interface JobApplicationFormData {
  name: string
  email: string
  phone: string
  linkedin?: string
  resumeLink: string
  coverLetter?: string
  jobTitle: string
  jobLocation: string
  jobSlug: string
  consent: boolean
}

const API_BASE = 'https://www.eatisfamily.fr/api'

export const useJobApplicationForm = () => {
  const { settings, loadSettings } = useGlobalSettings()

  const getFormId = async (): Promise<string> => {
    if (!settings.value) {
      await loadSettings()
    }
    return settings.value?.contact_form?.cf7_job_application_form_id || '357'
  }

  /**
   * Submit job application to Contact Form 7
   */
  const submitJobApplication = async (formData: JobApplicationFormData): Promise<CF7Response> => {
    const formId = await getFormId()

    if (!formId) {
      console.error('[JobApplicationForm] No form ID configured')
      return {
        status: 'mail_failed',
        message: 'Le formulaire de candidature n\'est pas configuré. Contactez l\'administrateur.',
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
        console.warn('[JobApplicationForm] Could not resolve hash ID:', error)
      }
    }

    const endpoint = `${API_BASE}/wp-json/contact-form-7/v1/contact-forms/${numericFormId}/feedback`

    try {
      const body = new FormData()
      body.append('your-name', formData.name)
      body.append('your-email', formData.email)
      body.append('your-phone', formData.phone)
      body.append('your-linkedin', formData.linkedin || '')
      body.append('your-resume-link', formData.resumeLink)
      body.append('your-message', formData.coverLetter || '')
      body.append('job-title', formData.jobTitle)
      body.append('job-location', formData.jobLocation)
      body.append('job-slug', formData.jobSlug)
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
      console.error('[JobApplicationForm] Error submitting form:', error)
      return {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi de votre candidature. Veuillez réessayer.',
      }
    }
  }

  const isValidEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  const isValidPhone = (phone: string): boolean => {
    const phoneRegex = /^(\+33|0033|0)[1-9](\s?\d{2}){4}$/
    return phoneRegex.test(phone.replace(/\s/g, ''))
  }

  const isValidResumeLink = (url: string): boolean => {
    try {
      const urlObj = new URL(url)
      const allowedDomains = [
        'drive.google.com',
        'docs.google.com',
        'onedrive.live.com',
        '1drv.ms',
        'dropbox.com',
        'www.dropbox.com',
        'dl.dropboxusercontent.com',
        'icloud.com',
        'www.icloud.com',
        'box.com',
        'www.box.com',
        'app.box.com',
        'wetransfer.com',
        'we.tl',
        'mega.nz',
        'mega.io',
        'sharepoint.com',
        'linkedin.com',
        'www.linkedin.com'
      ]
      const hostname = urlObj.hostname.toLowerCase()
      return allowedDomains.some(domain => hostname === domain || hostname.endsWith('.' + domain))
    } catch {
      return false
    }
  }

  const validateForm = (formData: JobApplicationFormData): { valid: boolean; errors: string[] } => {
    const errors: string[] = []

    if (!formData.name.trim()) {
      errors.push('Le nom complet est requis')
    }

    if (!formData.email.trim()) {
      errors.push('L\'adresse email est requise')
    } else if (!isValidEmail(formData.email)) {
      errors.push('L\'adresse email n\'est pas valide')
    }

    if (!formData.phone.trim()) {
      errors.push('Le numéro de téléphone est requis')
    } else if (!isValidPhone(formData.phone)) {
      errors.push('Le numéro de téléphone n\'est pas valide')
    }

    if (!formData.resumeLink.trim()) {
      errors.push('Le lien vers votre CV est requis')
    } else if (!isValidResumeLink(formData.resumeLink)) {
      errors.push('Veuillez fournir un lien valide depuis Google Drive, OneDrive, Dropbox ou un autre service cloud reconnu')
    }

    if (!formData.consent) {
      errors.push('Vous devez accepter la politique de confidentialité')
    }

    return {
      valid: errors.length === 0,
      errors
    }
  }

  return {
    submitJobApplication,
    validateForm,
    isValidEmail,
    isValidPhone,
    isValidResumeLink,
    getFormId
  }
}
