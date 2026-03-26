/**
 * Composable for Job Application Form with direct file upload
 *
 * Submits job applications directly to WordPress CF7 REST API
 * (same domain, no CORS issues, no need for Nuxt server proxy)
 */

export interface JobApplicationResponse {
  success: boolean
  message: string
  applicationId?: string
}

export interface JobApplicationFormData {
  name: string
  email: string
  phone: string
  linkedin?: string
  resumeFile: File | null
  coverLetter?: string
  jobTitle: string
  jobLocation: string
  jobSlug: string
  consent: boolean
}

const ALLOWED_EXTENSIONS = ['.pdf', '.doc', '.docx']
const MAX_FILE_SIZE = 2 * 1024 * 1024 // 2MB

// WordPress CF7 API base (WordPress is at /api/ on the same domain)
const WP_CF7_BASE = '/api/wp-json/contact-form-7/v1/contact-forms'
// Default CF7 form ID for job applications
const DEFAULT_JOB_CF7_FORM_ID = '357'

export const useJobApplicationForm = () => {

  const isValidFileType = (file: File): boolean => {
    const ext = file.name.toLowerCase().slice(file.name.lastIndexOf('.'))
    return ALLOWED_EXTENSIONS.includes(ext)
  }

  const isValidFileSize = (file: File): boolean => {
    return file.size <= MAX_FILE_SIZE
  }

  /**
   * Get the CF7 form ID for job applications from global settings
   */
  const getJobFormId = (): string => {
    try {
      const { settings } = useGlobalSettings()
      return settings.value?.contact_form?.cf7_job_application_form_id || DEFAULT_JOB_CF7_FORM_ID
    } catch {
      return DEFAULT_JOB_CF7_FORM_ID
    }
  }

  /**
   * Submit job application directly to WordPress CF7 REST API
   */
  const submitJobApplication = async (formData: JobApplicationFormData): Promise<JobApplicationResponse> => {
    try {
      const formId = getJobFormId()

      // Build FormData with CF7 field names
      const body = new FormData()
      body.append('your-name', formData.name)
      body.append('your-email', formData.email)
      body.append('your-phone', formData.phone)
      body.append('your-linkedin', formData.linkedin || '')
      body.append('your-message', formData.coverLetter || '')
      body.append('job-title', formData.jobTitle)
      body.append('job-location', formData.jobLocation)
      body.append('job-slug', formData.jobSlug)
      body.append('_wpcf7_unit_tag', `wpcf7-f${formId}-o1`)

      // Attach resume file
      if (formData.resumeFile) {
        body.append('resume', formData.resumeFile, formData.resumeFile.name)
      }

      const endpoint = `${WP_CF7_BASE}/${formId}/feedback`
      console.log(`[JobApplication] Submitting directly to CF7: ${endpoint}`)

      const response = await fetch(endpoint, {
        method: 'POST',
        body,
        headers: { 'Accept': 'application/json' },
      })

      console.log(`[JobApplication] CF7 response status: ${response.status}`)

      const responseText = await response.text()

      let result: any
      try {
        result = JSON.parse(responseText)
      } catch (parseError) {
        console.error('[JobApplication] Failed to parse CF7 response:', responseText.substring(0, 200))
        if (response.ok) {
          return {
            success: true,
            message: 'Votre candidature a été envoyée avec succès.',
          }
        }
        return {
          success: false,
          message: 'Erreur de communication avec le serveur. Veuillez réessayer.',
        }
      }

      // CF7 returns status 'mail_sent' on success
      const isSuccess = result.status === 'mail_sent' || !!result.posted_data_hash
      return {
        success: isSuccess,
        message: result.message || (isSuccess
          ? 'Votre candidature a été envoyée avec succès.'
          : 'Une erreur est survenue. Veuillez réessayer.'),
        applicationId: result.posted_data_hash,
      }

    } catch (error) {
      console.error('[JobApplicationForm] Error submitting form:', error)
      return {
        success: false,
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

    if (!formData.resumeFile) {
      errors.push('Le CV est requis (PDF, DOC ou DOCX)')
    } else {
      if (!isValidFileType(formData.resumeFile)) {
        errors.push('Type de fichier non autorisé. Formats acceptés: PDF, DOC, DOCX')
      }
      if (!isValidFileSize(formData.resumeFile)) {
        errors.push('Le fichier est trop volumineux. Taille maximum: 2MB')
      }
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
    isValidFileType,
    isValidFileSize,
  }
}
