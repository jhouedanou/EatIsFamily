<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { LucideLinkedin, LucideInstagram, LucideTwitter, LucideHeart } from 'lucide-vue-next'

const { settings, loadSettings, getCopyrightText } = useGlobalSettings()

// Load settings if not already loaded
onMounted(async () => {
  if (!settings.value) {
    await loadSettings()
  }
})

// Computed values from global settings
const brandName = computed(() => `${settings.value?.brand?.name || 'Eat is'} ${settings.value?.brand?.highlight || 'Family'}`)
const brandDescription = computed(() => settings.value?.footer?.tagline || 'Creating unforgettable culinary experiences.')
const contactEmail = computed(() => settings.value?.footer?.contact_email || settings.value?.contact?.email || '')
const contactPhone = computed(() => settings.value?.footer?.contact_phone || settings.value?.contact?.phone || '')
const companyTitle = computed(() => settings.value?.footer?.company_title || 'Company')
const policyTitle = computed(() => settings.value?.footer?.policy_title || 'Policy Info')
const showBackToTop = computed(() => settings.value?.footer?.show_back_to_top !== false)
const backToTopText = computed(() => settings.value?.footer?.back_to_top_text || 'Back to top')
const copyrightText = computed(() => getCopyrightText())

// Company links from footer settings (fallback to navigation links for backwards compatibility)
const companyLinks = computed(() => {
  // First check for footer.company_links (new structure from admin)
  const footerLinks = settings.value?.footer?.company_links
  if (footerLinks && footerLinks.length > 0) {
    return footerLinks
  }
  // Fallback to navigation links for backwards compatibility
  return settings.value?.navigation?.links || []
})

// Policy links from footer settings (with default fallback)
const policyLinks = computed(() => {
  const footerPolicyLinks = settings.value?.footer?.policy_links
  if (footerPolicyLinks && footerPolicyLinks.length > 0) {
    return footerPolicyLinks
  }
  // Default policy links if none configured
  return [
    { text: 'Privacy Statement', to: '/privacy' },
    { text: 'Terms and Conditions', to: '/terms' },
    { text: 'Cookies Policy', to: '/cookies' }
  ]
})

// Social links from global settings
const socialLinks = computed(() => {
  const social = settings.value?.social || {}
  const links = []
  if (social.linkedin) links.push({ icon: LucideLinkedin, href: social.linkedin, label: 'LinkedIn' })
  if (social.instagram) links.push({ icon: LucideInstagram, href: social.instagram, label: 'Instagram' })
  if (social.twitter) links.push({ icon: LucideTwitter, href: social.twitter, label: 'Twitter' })
  // Return at least placeholder links if none configured
  if (links.length === 0) {
    return [
      { icon: LucideLinkedin, href: '#', label: 'LinkedIn' },
      { icon: LucideInstagram, href: '#', label: 'Instagram' },
      { icon: LucideTwitter, href: '#', label: 'Twitter' }
    ]
  }
  return links
})
</script>

<template>
  <footer class="bg-brand-dark text-white py-16">
    <div class="container mx-auto px-4">
      <!-- Main Footer Content -->
      <div class="grid md:grid-cols-3 gap-12 mb-12">
        <!-- Brand Column -->
        <div>
          <div class="flex items-center gap-2 mb-6">
            <LucideHeart class="w-6 h-6 text-brand-pink fill-brand-pink" />
            <span class="font-heading text-xl font-bold">{{ brandName }}</span>
          </div>

          <p class="text-gray-400 mb-6 font-body text-sm leading-relaxed">
            {{ brandDescription }}
          </p>

          <div class="space-y-2 text-sm text-gray-400">
            <p v-if="contactEmail">{{ contactEmail }}</p>
            <p v-if="contactPhone">{{ contactPhone }}</p>
          </div>

          <!-- Social Links -->
          <div class="flex gap-3 mt-6">
            <a
              v-for="social in socialLinks"
              :key="social.label"
              :href="social.href"
              :aria-label="social.label"
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center hover:bg-white hover:text-brand-dark transition-all duration-300"
            >
              <component :is="social.icon" class="w-4 h-4" />
            </a>
          </div>
        </div>

        <!-- Company Links -->
        <div>
          <h4 class="font-heading font-bold text-lg mb-6">{{ companyTitle }}</h4>
          <ul class="space-y-3">
            <li v-for="link in companyLinks" :key="link.text">
              <NuxtLink
                :to="link.to"
                class="text-gray-400 hover:text-white transition-colors font-body text-sm"
              >
                {{ link.text }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Policy Links -->
        <div>
          <h4 class="font-heading font-bold text-lg mb-6">{{ policyTitle }}</h4>
          <ul class="space-y-3">
            <li v-for="link in policyLinks" :key="link.text">
              <NuxtLink
                :to="link.to"
                class="text-gray-400 hover:text-white transition-colors font-body text-sm"
              >
                {{ link.text }}
              </NuxtLink>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
        <p>{{ copyrightText }}</p>
        <a 
          v-if="showBackToTop"
          href="#top" 
          class="mt-4 md:mt-0 hover:text-white transition-colors flex items-center gap-2"
        >
          {{ backToTopText }} ↑
        </a>
      </div>
    </div>
  </footer>
</template>
