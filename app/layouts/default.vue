<script setup lang="ts">
// Default layout with dynamic SEO from WordPress
const { settings, loadSettings } = useGlobalSettings()

// Load settings on mount
onMounted(async () => {
  if (!settings.value) {
    await loadSettings()
  }
})

// Fonction pour valider le title template (éviter les URLs incorrectes)
const getValidTitleTemplate = () => {
  const template = settings.value?.seo?.title_template
  // Si le template contient 'http', 'wp-content', ou 'themes', c'est une URL incorrecte
  if (template && (template.includes('http') || template.includes('wp-content') || template.includes('themes'))) {
    return '%s | Eat Is Family'
  }
  return template || '%s | Eat Is Family'
}

// Set default SEO meta tags from global settings
useHead(() => ({
  titleTemplate: getValidTitleTemplate(),
  meta: [
    { 
      name: 'description', 
      content: settings.value?.seo?.default_description || 'Eat Is Family delivers exceptional catering for stadiums, arenas, and festivals across France.'
    },
    { 
      name: 'keywords', 
      content: settings.value?.seo?.keywords || ''
    },
    // Open Graph
    { 
      property: 'og:site_name', 
      content: `${settings.value?.brand?.name || 'Eat is'} ${settings.value?.brand?.highlight || 'Family'}`
    },
    { 
      property: 'og:type', 
      content: 'website'
    },
    {
      property: 'og:image',
      content: settings.value?.seo?.og_image || ''
    },
    // Twitter
    { 
      name: 'twitter:card', 
      content: 'summary_large_image'
    },
    { 
      name: 'twitter:site', 
      content: settings.value?.seo?.twitter_site || '@eatisfamily'
    }
  ],
  link: settings.value?.seo?.canonical_base ? [
    { rel: 'canonical', href: settings.value.seo.canonical_base }
  ] : []
}))
</script>

<template>
  <div class="d-flex flex-column min-vh-100 bg-brand-gray overflow-hidden">
    <LayoutHeader />

    <main class="flex-grow-1 main-content">
      <slot />
    </main>

    <LayoutFooter />
    <BackToTop />
  </div>
</template>

<style scoped>
.main-content {
  padding-top: ss0px; /* Hauteur du header */
}

@media (max-width: 968px) {
  .main-content {
    padding-top: 0px; /* Hauteur du header mobile */
  }
}
</style>