/**
 * Plugin Analytics - Suivi automatique des pages consultées
 * 
 * Ce plugin côté client :
 * 1. Restaure le consentement GA si l'utilisateur a déjà accepté les cookies
 * 2. Envoie un page_view à chaque changement de route (SPA navigation)
 * 3. Envoie le page_view initial au chargement de la page
 */
export default defineNuxtPlugin((nuxtApp) => {
  const router = useRouter()
  const { trackPageView, grantConsent } = useAnalytics()

  console.log(
    '%c📊 Google Analytics (GA4) initialisé %c G-RX0HPWBFPJ ',
    'background: #4CAF50; color: white; padding: 4px 8px; border-radius: 3px 0 0 3px; font-weight: bold;',
    'background: #333; color: #4CAF50; padding: 4px 8px; border-radius: 0 3px 3px 0;'
  )

  // Restaurer le consentement si l'utilisateur a déjà accepté précédemment
  if (typeof localStorage !== 'undefined') {
    try {
      const stored = localStorage.getItem('cookie_consent')
      if (stored) {
        const consent = JSON.parse(stored)
        if (consent.value === 'accepted') {
          grantConsent()
        }
        console.log(
          `%c🍪 Consentement restauré %c ${consent.value} `,
          'background: #607D8B; color: white; padding: 2px 8px; border-radius: 3px 0 0 3px; font-weight: bold;',
          `background: ${consent.value === 'accepted' ? '#4CAF50' : '#f44336'}; color: white; padding: 2px 8px; border-radius: 0 3px 3px 0;`
        )
      } else {
        console.log(
          '%c🍪 Aucun consentement enregistré %c En attente du choix utilisateur ',
          'background: #607D8B; color: white; padding: 2px 8px; border-radius: 3px 0 0 3px; font-weight: bold;',
          'background: #FF9800; color: white; padding: 2px 8px; border-radius: 0 3px 3px 0;'
        )
      }
    } catch {
      // Silently ignore parsing errors
    }
  }

  // Suivi de la page initiale (après hydration)
  nuxtApp.hook('app:mounted', () => {
    // Petit délai pour s'assurer que le titre est à jour
    nextTick(() => {
      trackPageView(router.currentRoute.value.fullPath)
    })
  })

  // Suivi automatique à chaque navigation côté client (SPA)
  router.afterEach((to, from) => {
    // Éviter le double tracking au chargement initial
    if (!from.name && !from.matched.length) return

    // Attendre que le DOM soit à jour pour capturer le bon titre
    nextTick(() => {
      trackPageView(to.fullPath, document.title)
    })
  })
})
