/**
 * Composable for Contact Form 7 API integration
 * 
 * Contact Form 7 REST API endpoint:
 * POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback
 */

import { ref } from 'vue'

export interface CF7FormData {
  [key: string]: string | File | FileList
}

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

export const useContactForm = () => {
  const config = useRuntimeConfig()
  
  // WordPress base URL
  const wpBaseUrl = config.public.wordpressUrl || 'https://bigfive.dev/eatisfamily'
  
  // Contact Form 7 form ID (à configurer dans WordPress)
  const { settings } = useGlobalSettings()
  
  // Cache pour l'ID numérique résolu
  const resolvedNumericId = ref<string | null>(null)
  
  const getFormId = (): string => {
    return settings.value?.contact_form?.cf7_form_id || ''
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
          console.log(`[ContactForm] Resolved hash ${formIdOrHash} to numeric ID ${data.numeric_id}`)
          return resolvedNumericId.value
        }
      } catch (error) {
        console.warn('[ContactForm] Could not resolve hash ID, using as-is:', error)
      }
    }
    
    // Fallback: utiliser l'ID tel quel
    return formIdOrHash
  }

  /**
   * Submit form data to Contact Form 7
   */
  const submitContactForm = async (formData: ContactFormData): Promise<CF7Response> => {
    const formIdOrHash = getFormId()
    
    if (!formIdOrHash) {
      console.error('[ContactForm] No form ID configured')
      return {
        status: 'mail_failed',
        message: 'Le formulaire de contact n\'est pas configuré. Contactez l\'administrateur.',
      }
    }
    
    // Résoudre l'ID numérique si nécessaire
    const formId = await resolveNumericId(formIdOrHash)
    const endpoint = `${wpBaseUrl}/wp-json/contact-form-7/v1/contact-forms/${formId}/feedback`
    
    console.log(`[ContactForm] Submitting to: ${endpoint}`)
    
    // Créer un FormData pour l'envoi
    const data = new FormData()
    
    // Mapper les champs du formulaire vers les noms de champs CF7
    // Ces noms doivent correspondre aux champs définis dans le formulaire CF7
    data.append('your-name', formData.name)
    data.append('your-email', formData.email)
    data.append('event-type', formData.eventType)
    data.append('location', formData.location)
    data.append('event-date', formData.date)
    data.append('guests', formData.guests)
    data.append('your-message', formData.message)
    
    // Ajouter le referer pour la validation CORS
    data.append('_wpcf7_unit_tag', `wpcf7-f${formId}-o1`)
    
    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        body: data,
        headers: {
          'Accept': 'application/json',
        },
      })
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const result: CF7Response = await response.json()
      return result
      
    } catch (error) {
      console.error('[ContactForm] Error submitting form:', error)
      
      // Retourner une réponse d'erreur
      return {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi du formulaire. Veuillez réessayer.',
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
   * Validate form data before submission
   */
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
