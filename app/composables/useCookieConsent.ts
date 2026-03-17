import { ref } from 'vue'

const STORAGE_KEY = 'cookie_consent'

export const useCookieConsent = () => {
    const showBanner = ref(false)
    const hasConsented = ref(false)
    const { grantConsent, revokeConsent, trackConsentChoice } = useAnalytics()

    const init = () => {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored) {
            hasConsented.value = true
            showBanner.value = false

            // Restaurer l'état du consentement GA selon le choix précédent
            try {
                const consent = JSON.parse(stored)
                if (consent.value === 'accepted') {
                    grantConsent()
                } else {
                    revokeConsent()
                }
            } catch {
                // Silently ignore parsing errors
            }
        } else {
            showBanner.value = true
        }
    }

    const acceptAll = () => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ value: 'accepted', date: new Date().toISOString() }))
        hasConsented.value = true
        showBanner.value = false
        // Activer le suivi Google Analytics
        grantConsent()
        trackConsentChoice('accepted')
    }

    const rejectAll = () => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ value: 'rejected', date: new Date().toISOString() }))
        hasConsented.value = true
        showBanner.value = false
        // Désactiver le suivi Google Analytics
        revokeConsent()
        trackConsentChoice('rejected')
    }

    return {
        showBanner,
        hasConsented,
        init,
        acceptAll,
        rejectAll
    }
}
