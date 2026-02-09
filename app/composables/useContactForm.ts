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
  const wpBaseUrl = config.public.wordpressUrl || 'https://www.eatisfamily.fr/api'
  
  // Contact Form 7 form ID (Ã  configurer dans WordPress)
  const { settings } = useGlobalSettings()
  
  // Cache pour l'ID numÃ©rique rÃ©solu
  const resolvedNumericId = ref<string | null>(null)
  
  const getFormId = (): string => {
    return settings.value?.contact_form?.cf7_form_id || ''
  }
  
  /**
   * VÃ©rifie si l'ID est un hash (lettres et chiffres, gÃ©nÃ©ralement 7 caractÃ¨res)
   * ou un ID numÃ©rique
   */
  const isHashId = (id: string): boolean => {
    return /^[a-f0-9]{7}$/i.test(id)
  }
  
  /**
   * RÃ©sout l'ID numÃ©rique depuis un hash ID si nÃ©cessaire
   */
  const resolveNumericId = async (formIdOrHash: string): Promise<string> => {
    // Si c'est dÃ©jÃ  un ID numÃ©rique, le retourner
    if (/^\d+$/.test(formIdOrHash)) {
      return formIdOrHash
    }
    
    // Si on a dÃ©jÃ  rÃ©solu cet ID
    if (resolvedNumericId.value) {
      return resolvedNumericId.value
    }
    
    // Si c'est un hash, essayer de rÃ©soudre via l'API
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
   * Submit form data to Contact Form 7 via server proxy
   * Uses /api/contact/submit to avoid CORS issues
   */
  const submitContactForm = async (formData: ContactFormData): Promise<CF7Response> => {
    const formIdOrHash = getFormId()
    
    if (!formIdOrHash) {
      console.error('[ContactForm] No form ID configured')
      return {
        status: 'mail_failed',
        message: 'Le formulaire de contact n\'est pas configurÃ©. Contactez l\'administrateur.',
      }
    }
    
    console.log(`[ContactForm] Submitting via server proxy with form ID: ${formIdOrHash}`)
    
    try {
      const response = await fetch('/api/contact/submit', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          name: formData.name,
          email: formData.email,
          eventType: formData.eventType,
          location: formData.location,
          date: formData.date,
          guests: formData.guests,
          message: formData.message,
          formId: formIdOrHash,
        }),
      })
      
      console.log(`[ContactForm] Proxy response status: ${response.status}`)
      
      const result = await response.json()
      console.log(`[ContactForm] Proxy response:`, result)
      
      // Le proxy peut retourner l'erreur dans data (H3Error) ou directement
      if (result.data && result.data.status) {
        return result.data as CF7Response
      }
      
      return result as CF7Response
      
    } catch (error) {
      console.error('[ContactForm] Error submitting form:', error)
      
      // Retourner une rÃ©ponse d'erreur
      return {
        status: 'mail_failed',
        message: 'Une erreur est survenue lors de l\'envoi du formulaire. Veuillez rÃ©essayer.',
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
