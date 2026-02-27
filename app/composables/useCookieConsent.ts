import { ref } from 'vue'

const STORAGE_KEY = 'cookie_consent'

export const useCookieConsent = () => {
    const showBanner = ref(false)
    const hasConsented = ref(false)

    const init = () => {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored) {
            hasConsented.value = true
            showBanner.value = false
        } else {
            showBanner.value = true
        }
    }

    const acceptAll = () => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ value: 'accepted', date: new Date().toISOString() }))
        hasConsented.value = true
        showBanner.value = false
    }

    const rejectAll = () => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ value: 'rejected', date: new Date().toISOString() }))
        hasConsented.value = true
        showBanner.value = false
    }

    return {
        showBanner,
        hasConsented,
        init,
        acceptAll,
        rejectAll
    }
}
