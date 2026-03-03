import type { RouterConfig } from '@nuxt/schema'

// https://router.vuejs.org/api/interfaces/routeroptions.html
export default <RouterConfig>{
  scrollBehavior(to, from, savedPosition) {
    // Si on a une position sauvegardée (navigation avec back/forward)
    if (savedPosition) {
      return savedPosition
    }

    // Si on a un hash dans l'URL (ancre), attendre que la page soit prête
    if (to.hash) {
      return new Promise((resolve) => {
        setTimeout(() => {
          const el = document.querySelector(to.hash)
          if (el) {
            resolve({
              el: to.hash,
              behavior: 'smooth',
            })
          } else {
            resolve({ top: 0, left: 0 })
          }
        }, 150)
      })
    }

    // Le plugin scroll-to-top.client.ts gère le scroll pour les nouvelles navigations
    return { top: 0, left: 0, behavior: 'instant' }
  },
}
