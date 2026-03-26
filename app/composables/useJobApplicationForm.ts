/**
 * Composable for Job Application Form with direct file upload
 *
 * Sends job applications with resume file to /api/applications/apply
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

export const useJobApplicationForm = () => {

  const isValidFileType = (file: File): boolean => {
    const ext = file.name.toLowerCase().slice(file.name.lastIndexOf('.'))
    return ALLOWED_EXTENSIONS.includes(ext)
  }

  const isValidFileSize = (file: File): boolean => {
    return file.size <= MAX_FILE_SIZE
  }

  /**
   * Submit job application with resume file upload
   */
  const submitJobApplication = async (formData: JobApplicationFormData): Promise<JobApplicationResponse> => {
    try {
      const body = new FormData()
      body.append('name', formData.name)
      body.append('email', formData.email)
      body.append('phone', formData.phone)
      body.append('linkedin', formData.linkedin || '')
      body.append('coverLetter', formData.coverLetter || '')
      body.append('jobSlug', formData.jobSlug)
      body.append('jobTitle', formData.jobTitle)
      body.append('jobLocation', formData.jobLocation)

      // Attach resume file
      if (formData.resumeFile) {
        body.append('resume', formData.resumeFile, formData.resumeFile.name)
      }

      const response = await fetch('/api/applications/apply', {
        method: 'POST',
        body,
        headers: { 'Accept': 'application/json' },
      })

      if (!response.ok) {
        const errorData = await response.json().catch(() => null)
        return {
          success: false,
          message: errorData?.data?.message || errorData?.message || 'Une erreur est survenue. Veuillez réessayer.'
        }
      }

      const result = await response.json()
      return result as JobApplicationResponse

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
