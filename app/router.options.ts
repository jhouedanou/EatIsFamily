import type { RouterConfig } from '@nuxt/schema'

// https://router.vuejs.org/api/interfaces/routeroptions.html
export default <RouterConfig>{
  scrollBehavior(to, from, savedPosition) {
    // Si on a une position sauvegardée (navigation avec back/forward)
    if (savedPosition) {
      return savedPosition
    }

    // Si on a un hash dans l'URL (ancre)
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }

    // Pour les nouvelles navigations, remonter en haut de la page
    return { top: 0, behavior: 'smooth' }
  },
}
