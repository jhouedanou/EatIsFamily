/**
 * Composable for Job Application Form via Contact Form 7 API
 * 
 * Contact Form 7 REST API endpoint:
 * POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback
 */

import { ref } from 'vue'

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

export const useJobApplicationForm = () => {
  const config = useRuntimeConfig()
  
  // WordPress base URL
  const wpBaseUrl = config.public.wordpressUrl || 'https://bigfive.dev/eatisfamily'
  
  // Get settings for CF7 form ID
  const { settings, loadSettings } = useGlobalSettings()
  
  // Cache pour l'ID numérique résolu
  const resolvedNumericId = ref<string | null>(null)
  
  /**
   * Récupère l'ID du formulaire CF7 pour les candidatures
   * Charge les settings si nécessaire
   */
  const getFormId = async (): Promise<string> => {
    // S'assurer que les settings sont chargées
    if (!settings.value) {
      console.log('[JobApplicationForm] Settings not loaded, loading now...')
      await loadSettings()
    }
    return settings.value?.contact_form?.cf7_job_application_form_id || ''
  }
  
  /**
   * Vérifie si l'ID est un hash (lettres et chiffres, généralement 7 caractères)
   * ou un ID numérique
   */
  const isHashId = (id: string): boolean => {
    return /^[a-f0-9]{7}$/i.test(id)
  }
  
  /**
   * Résout l'ID numérique depuis un hash ID si nécessaire
   */
  const resolveNumericId = async (formIdOrHash: string): Promise<string> => {
    // Si c'est déjà un ID numérique, le retourner
    if (/^\d+$/.test(formIdOrHash)) {
      return formIdOrHash
    }
    
    // Si on a déjà résolu cet ID
    if (resolvedNumericId.value) {
      return resolvedNumericId.value
    }
    
    // Si c'est un hash, essayer de résoudre via l'API
    if (isHashId(formIdOrHash)) {
      try {
        const response = await fetch(`${wpBaseUrl}/wp-json/eatisfamily/v1/cf7-form-id/${formIdOrHash}`)
        if (response.ok) {
          const data = await response.json()
          resolvedNumericId.value = String(data.numeric_id)
          console.log(`[JobApplicationForm] Resolved hash ${formIdOrHash} to numeric ID ${data.numeric_id}`)
          return resolvedNumericId.value
        }
      } catch (error) {
        console.warn('[JobApplicationForm] Could not resolve hash ID, using as-is:', error)
      }
    }
    
    // Fallback: utiliser l'ID tel quel
    return formIdOrHash
  }

  /**
   * Submit job application to Contact Form 7
   */
  const submitJobApplication = async (formData: JobApplicationFormData): Promise<CF7Response> => {
    const formIdOrHash = await getFormId()
    
    if (!formIdOrHash) {
      console.error('[JobApplicationForm] No form ID configured')
      return {
        status: 'mail_failed',
        message: 'Le formulaire de candidature n\'est pas configuré. Contactez l\'administrateur.',
      }
    }
    
    // Résoudre l'ID numérique si nécessaire
    const formId = await resolveNumericId(formIdOrHash)
    const endpoint = `${wpBaseUrl}/wp-json/contact-form-7/v1/contact-forms/${formId}/feedback`
    
    console.log(`[JobApplicationForm] Submitting to: ${endpoint}`)
    console.log(`[JobApplicationForm] Form data:`, {
      name: formData.name,
      email: formData.email,
      phone: formData.phone,
      linkedin: formData.linkedin,
      resumeLink: formData.resumeLink,
      jobTitle: formData.jobTitle,
      jobLocation: formData.jobLocation,
      jobSlug: formData.jobSlug
    })
    
    // Créer un FormData pour l'envoi
    const data = new FormData()
    
    // Mapper les champs du formulaire vers les noms de champs CF7
    // Ces noms doivent correspondre aux champs définis dans le formulaire CF7
    data.append('your-name', formData.name)
    data.append('your-email', formData.email)
    data.append('your-phone', formData.phone)
    data.append('your-linkedin', formData.linkedin || '')
    data.append('your-resume-link', formData.resumeLink)
    data.append('your-message', formData.coverLetter || '')
    data.append('job-title', formData.jobTitle)
    data.append('job-location', formData.jobLocation)
    data.append('job-slug', formData.jobSlug)
    
    // Ajouter les champs requis par CF7 pour le traitement
    data.append('_wpcf7', formId)
    data.append('_wpcf7_version', '5.9')
    data.append('_wpcf7_locale', 'fr_FR')
    data.append('_wpcf7_unit_tag', `wpcf7-f${formId}-p0-o1`)
    data.append('_wpcf7_container_post', '0')
    data.append('_wpcf7_posted_data_hash', '')
    
    // Debug: Afficher le contenu du FormData
    console.log('[JobApplicationForm] FormData entries:')
    for (const [key, value] of data.entries()) {
      if (value instanceof File) {
        console.log(`  ${key}: [File] ${value.name} (${value.size} bytes, ${value.type})`)
      } else {
        console.log(`  ${key}: ${value}`)
      }
    }
    
    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        body: data,
        headers: {
          'Accept': 'application/json',
        },
      })
      
      console.log('[JobApplicationForm] Response status:', response.status)
      
      if (!response.ok) {
        const errorText = await response.text()
        console.error('[JobApplicationForm] Error response:', errorText)
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const result: CF7Response = await response.json()
      console.log('[JobApplicationForm] CF7 Response:', result)
      return result
      
    } catch (error) {
      console.error('[JobApplicationForm] Error submitting form:', error)
      
      // Retourner une réponse d'erreur
      return {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi de votre candidature. Veuillez réessayer.',
      }
    }
  }

  /**
   * Validate email format
   */
  const isValidEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  /**
   * Validate phone format (French format)
   */
  const isValidPhone = (phone: string): boolean => {
    // Accept formats: +33 6 00 00 00 00, 06 00 00 00 00, 0600000000
    const phoneRegex = /^(\+33|0033|0)[1-9](\s?\d{2}){4}$/
    return phoneRegex.test(phone.replace(/\s/g, ''))
  }

  /**
   * Validate resume link URL (cloud storage services)
   */
  const isValidResumeLink = (url: string): boolean => {
    // Vérifier si c'est une URL valide
    try {
      const urlObj = new URL(url)
      
      // Liste des domaines de services cloud autorisés
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
        'linkedin.com',  // Pour les CV LinkedIn
        'www.linkedin.com'
      ]
      
      // Vérifier si le domaine est dans la liste autorisée
      const hostname = urlObj.hostname.toLowerCase()
      return allowedDomains.some(domain => hostname === domain || hostname.endsWith('.' + domain))
    } catch {
      return false
    }
  }

  /**
   * Validate form data before submission
   */
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
