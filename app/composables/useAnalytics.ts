/**
 * Composable Google Analytics (GA4) - G-RX0HPWBFPJ
 * 
 * Fournit des fonctions réutilisables pour :
 * - Suivi des pages consultées (page_view)
 * - Envoi d'événements personnalisés
 * - Gestion du consentement RGPD (Consent Mode v2)
 * - Logs console stylisés pour le debug
 * 
 * Utilisation :
 *   const { trackPageView, trackEvent, grantConsent, revokeConsent } = useAnalytics()
 */

const GA_MEASUREMENT_ID = 'G-RX0HPWBFPJ'

// Styles pour les logs console
const LOG_STYLES = {
  pageView:  'background: #4CAF50; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  event:     'background: #2196F3; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  form:      'background: #FF9800; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  content:   'background: #9C27B0; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  click:     'background: #E91E63; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  consent:   'background: #607D8B; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  search:    'background: #00BCD4; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  social:    'background: #3F51B5; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold;',
  data:      'color: #666; font-style: italic;',
}

// Typage de la fonction gtag globale
declare global {
  interface Window {
    gtag: (...args: any[]) => void
    dataLayer: any[]
  }
}

function gtag(...args: any[]) {
  if (import.meta.server) return
  if (typeof window !== 'undefined' && window.gtag) {
    window.gtag(...args)
  }
}

export const useAnalytics = () => {

  // ─────────────────────────────────────────────
  //  📄 SUIVI DES PAGES
  // ─────────────────────────────────────────────

  /**
   * Envoie un événement page_view avec le chemin et le titre de la page
   */
  const trackPageView = (path: string, title?: string) => {
    const pageTitle = title || (typeof document !== 'undefined' ? document.title : '')
    console.log(`%c📄 PAGE VIEW`, LOG_STYLES.pageView, `→ ${path}`, `| "${pageTitle}"`)
    gtag('event', 'page_view', {
      page_path: path,
      page_title: pageTitle,
      page_location: typeof window !== 'undefined' ? window.location.origin + path : path,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  🎯 ÉVÉNEMENTS GÉNÉRIQUES
  // ─────────────────────────────────────────────

  /**
   * Envoie un événement personnalisé à GA4
   */
  const trackEvent = (eventName: string, params?: Record<string, any>) => {
    console.log(`%c🎯 EVENT`, LOG_STYLES.event, `→ ${eventName}`, params || '')
    gtag('event', eventName, {
      ...params,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  📝 SUIVI DES FORMULAIRES
  // ─────────────────────────────────────────────

  /**
   * Suivi de la soumission d'un formulaire
   */
  const trackFormSubmit = (formName: string, formData?: Record<string, any>) => {
    console.log(`%c📝 FORM SUBMIT`, LOG_STYLES.form, `→ ${formName}`, formData || '')
    gtag('event', 'form_submit', {
      form_name: formName,
      ...formData,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi de l'ouverture/début d'interaction avec un formulaire
   */
  const trackFormStart = (formName: string) => {
    console.log(`%c📝 FORM START`, LOG_STYLES.form, `→ ${formName}`)
    gtag('event', 'form_start', {
      form_name: formName,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  👁️ SUIVI DES CONTENUS
  // ─────────────────────────────────────────────

  /**
   * Suivi de la consultation d'un contenu spécifique (événement, blog, activité…)
   */
  const trackContentView = (contentType: string, contentId: string, contentTitle?: string) => {
    console.log(`%c👁️ CONTENT VIEW`, LOG_STYLES.content, `→ [${contentType}] ${contentTitle || contentId}`)
    gtag('event', 'view_item', {
      content_type: contentType,
      content_id: contentId,
      content_title: contentTitle,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi de la consultation d'un article de blog
   */
  const trackBlogView = (slug: string, title: string, categories?: string[]) => {
    console.log(`%c👁️ BLOG VIEW`, LOG_STYLES.content, `→ "${title}"`, `%c(${slug})`, LOG_STYLES.data)
    gtag('event', 'view_item', {
      content_type: 'blog_post',
      content_id: slug,
      content_title: title,
      content_categories: categories?.join(', '),
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi de la consultation d'une offre d'emploi
   */
  const trackJobView = (slug: string, title: string, location?: string, jobType?: string) => {
    console.log(`%c👁️ JOB VIEW`, LOG_STYLES.content, `→ "${title}"`, `%c📍 ${location || 'N/A'} | 💼 ${jobType || 'N/A'}`, LOG_STYLES.data)
    gtag('event', 'view_item', {
      content_type: 'job_listing',
      content_id: slug,
      content_title: title,
      job_location: location,
      job_type: jobType,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi de la consultation d'un événement
   */
  const trackEventView = (eventId: string, title: string, eventType?: string) => {
    console.log(`%c👁️ EVENT VIEW`, LOG_STYLES.content, `→ "${title}"`, `%c(${eventType || 'N/A'})`, LOG_STYLES.data)
    gtag('event', 'view_item', {
      content_type: 'event',
      content_id: eventId,
      content_title: title,
      event_type: eventType,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  🖱️ SUIVI DES CLICS
  // ─────────────────────────────────────────────

  /**
   * Suivi d'un clic sur un CTA (Call to Action)
   */
  const trackCTAClick = (ctaName: string, ctaLocation: string, destination?: string) => {
    console.log(`%c🖱️ CTA CLICK`, LOG_STYLES.click, `→ "${ctaName}"`, `%c@ ${ctaLocation}${destination ? ' → ' + destination : ''}`, LOG_STYLES.data)
    gtag('event', 'cta_click', {
      cta_name: ctaName,
      cta_location: ctaLocation,
      cta_destination: destination,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi d'un clic de navigation
   */
  const trackNavClick = (linkText: string, linkUrl: string, navLocation: string) => {
    console.log(`%c🖱️ NAV CLICK`, LOG_STYLES.click, `→ "${linkText}"`, `%c(${navLocation}) → ${linkUrl}`, LOG_STYLES.data)
    gtag('event', 'navigation_click', {
      link_text: linkText,
      link_url: linkUrl,
      nav_location: navLocation,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi d'un clic sur un lien externe
   */
  const trackOutboundLink = (url: string, linkText?: string) => {
    console.log(`%c🔗 OUTBOUND`, LOG_STYLES.click, `→ ${url}`, linkText ? `"${linkText}"` : '')
    gtag('event', 'click', {
      event_category: 'outbound',
      event_label: url,
      link_text: linkText,
      transport_type: 'beacon',
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi d'un clic sur un réseau social
   */
  const trackSocialClick = (platform: string, url: string) => {
    console.log(`%c📱 SOCIAL`, LOG_STYLES.social, `→ ${platform}`, `%c${url}`, LOG_STYLES.data)
    gtag('event', 'social_click', {
      social_platform: platform,
      social_url: url,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  🔍 SUIVI DE RECHERCHE
  // ─────────────────────────────────────────────

  /**
   * Suivi d'une recherche
   */
  const trackSearch = (searchTerm: string, searchType?: string, resultsCount?: number) => {
    console.log(`%c🔍 SEARCH`, LOG_STYLES.search, `→ "${searchTerm}"`, `%ctype: ${searchType || 'general'} | résultats: ${resultsCount ?? '?'}`, LOG_STYLES.data)
    gtag('event', 'search', {
      search_term: searchTerm,
      search_type: searchType,
      results_count: resultsCount,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi d'un filtrage (jobs par lieu, type, etc.)
   */
  const trackFilter = (filterType: string, filterValue: string, context?: string) => {
    console.log(`%c🔍 FILTER`, LOG_STYLES.search, `→ ${filterType}: "${filterValue}"`, `%c(${context || 'page'})`, LOG_STYLES.data)
    gtag('event', 'filter', {
      filter_type: filterType,
      filter_value: filterValue,
      filter_context: context,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  📋 SUIVI CANDIDATURES
  // ─────────────────────────────────────────────

  /**
   * Suivi du début d'une candidature (ouverture du modal)
   */
  const trackJobApplyStart = (jobTitle: string, jobSlug: string) => {
    console.log(`%c📋 APPLY START`, LOG_STYLES.form, `→ "${jobTitle}"`, `%c(${jobSlug})`, LOG_STYLES.data)
    gtag('event', 'begin_checkout', {
      content_type: 'job_application',
      content_id: jobSlug,
      content_title: jobTitle,
      send_to: GA_MEASUREMENT_ID
    })
  }

  /**
   * Suivi d'une candidature envoyée avec succès
   */
  const trackJobApplySuccess = (jobTitle: string, jobSlug: string) => {
    console.log(`%c✅ APPLY SUCCESS`, LOG_STYLES.form, `→ "${jobTitle}"`, `%c(${jobSlug})`, LOG_STYLES.data)
    gtag('event', 'generate_lead', {
      content_type: 'job_application',
      content_id: jobSlug,
      content_title: jobTitle,
      currency: 'EUR',
      value: 1,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  🍪 GESTION DU CONSENTEMENT
  // ─────────────────────────────────────────────

  /**
   * Active le consentement analytics (après acceptation des cookies)
   */
  const grantConsent = () => {
    console.log(`%c🍪 CONSENT`, LOG_STYLES.consent, `→ GRANTED ✅`)
    gtag('consent', 'update', {
      analytics_storage: 'granted'
    })
  }

  /**
   * Révoque le consentement analytics (après refus des cookies)
   */
  const revokeConsent = () => {
    console.log(`%c🍪 CONSENT`, LOG_STYLES.consent, `→ DENIED ❌`)
    gtag('consent', 'update', {
      analytics_storage: 'denied'
    })
  }

  /**
   * Suivi du choix de consentement
   */
  const trackConsentChoice = (choice: 'accepted' | 'rejected') => {
    console.log(`%c🍪 CONSENT CHOICE`, LOG_STYLES.consent, `→ ${choice.toUpperCase()}`)
    gtag('event', 'consent_choice', {
      consent_action: choice,
      send_to: GA_MEASUREMENT_ID
    })
  }

  // ─────────────────────────────────────────────
  //  📊 SUIVI SCROLL / ENGAGEMENT
  // ─────────────────────────────────────────────

  /**
   * Suivi du scroll à un pourcentage donné
   */
  const trackScroll = (percent: number, pagePath: string) => {
    console.log(`%c📊 SCROLL`, LOG_STYLES.event, `→ ${percent}%`, `%c@ ${pagePath}`, LOG_STYLES.data)
    gtag('event', 'scroll', {
      percent_scrolled: percent,
      page_path: pagePath,
      send_to: GA_MEASUREMENT_ID
    })
  }

  return {
    // Pages
    trackPageView,
    // Événements génériques
    trackEvent,
    // Formulaires
    trackFormSubmit,
    trackFormStart,
    // Contenus
    trackContentView,
    trackBlogView,
    trackJobView,
    trackEventView,
    // Clics
    trackCTAClick,
    trackNavClick,
    trackOutboundLink,
    trackSocialClick,
    // Recherche
    trackSearch,
    trackFilter,
    // Candidatures
    trackJobApplyStart,
    trackJobApplySuccess,
    // Consentement
    grantConsent,
    revokeConsent,
    trackConsentChoice,
    // Engagement
    trackScroll
  }
}
