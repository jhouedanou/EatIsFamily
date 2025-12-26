// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',

  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxt/image',
    '@nuxtjs/google-fonts',
    '@nuxtjs/leaflet'
  ],

  googleFonts: {
    families: {
      'Plus Jakarta Sans': [400, 500, 600, 700],
      'Inter': [400, 500, 600, 700],
    },
    display: 'swap'
  },

  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Eat Is Family - Celebrate Food Every Day',
      meta: [
        { name: 'description', content: 'Discover amazing culinary experiences, join exciting food events, and explore career opportunities in the food industry.' },
        { name: 'keywords', content: 'food, culinary, cooking, events, jobs, careers, recipes, blog' },
        { property: 'og:title', content: 'Eat Is Family' },
        { property: 'og:description', content: 'Celebrate Food Every Day' },
        { property: 'og:type', content: 'website' }
      ],
    }
  },

  css: ['~/assets/css/main.css'],

  devtools: { enabled: true }
})
