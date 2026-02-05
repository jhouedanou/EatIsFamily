<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { LucideMenu, LucideX } from 'lucide-vue-next'

const route = useRoute()
const isOpen = ref(false)
const { settings, loadSettings } = useGlobalSettings()

// Check if a link is active
const isActiveLink = (linkTo: string) => {
  if (linkTo === '/') {
    return route.path === '/'
  }
  return route.path.startsWith(linkTo)
}

// Load settings if not already loaded
onMounted(async () => {
  if (!settings.value) {
    await loadSettings()
  }
})

// Computed values from global settings
const brandName = computed(() => settings.value?.brand?.name || 'Eat is')
const brandHighlight = computed(() => settings.value?.brand?.highlight || 'Family')
const navLinks = computed(() => settings.value?.navigation?.links || [])
const ctaText = computed(() => settings.value?.header?.cta_text || 'Contactez-nous')
const ctaTextMobile = computed(() => settings.value?.header?.cta_text_mobile || 'Contact')
const ctaLink = computed(() => settings.value?.header?.cta_link || '/contact')
const siteLogo = computed(() => settings.value?.brand?.logo || '')
</script>

<template>
  <nav class="fixed top-0 left-0 w-full z-50 bg-white/95 backdrop-blur-md border-b-2 border-black">
    <div class="container mx-auto px-4 h-20 flex items-center justify-between">

      <!-- Mobile Menu Button -->
      <button @click="isOpen = !isOpen" class="lg:hidden text-brand-dark">
        <LucideMenu v-if="!isOpen" class="w-8 h-8" />
        <LucideX v-else class="w-8 h-8" />
      </button>

      <!-- Left Links (Desktop) -->
      <div v-if="navLinks.length" class="hidden lg:flex items-center space-x-8">
        <NuxtLink
          v-for="link in navLinks.slice(0, 2)"
          :key="link.text"
          :to="link.to"
          class="nav-link font-body font-medium transition-colors"
          :class="{ 'nav-active': isActiveLink(link.to) }"
        >
          {{ link.text }}
        </NuxtLink>
      </div>

      <!-- Logo -->
      <NuxtLink to="/" class="absolute left-1/2 transform -translate-x-1/2 group">
        <!-- Image logo if available -->
        <img 
          v-if="siteLogo" 
          :src="siteLogo" 
          :alt="brandName + ' ' + brandHighlight"
          class="h-12 lg:h-14 group-hover:scale-105 transition-transform"
        />
        <!-- Text logo fallback -->
        <h1 v-else class="text-2xl lg:text-3xl font-heading font-bold tracking-tight group-hover:scale-105 transition-transform">
          {{ brandName }} <span class="text-brand-pink italic">{{ brandHighlight }}</span>
          <span class="absolute -right-2 -top-1 w-2 h-2 bg-brand-yellow rounded-full border border-black"></span>
        </h1>
      </NuxtLink>

      <!-- Right Links & CTA (Desktop) -->
      <div v-if="navLinks.length" class="hidden lg:flex items-center space-x-8">
        <NuxtLink
          v-for="link in navLinks.slice(2)"
          :key="link.text"
          :to="link.to"
          class="nav-link font-body font-medium transition-colors"
          :class="{ 'nav-active': isActiveLink(link.to) }"
        >
          {{ link.text }}
        </NuxtLink>
        <NuxtLink :to="ctaLink" class="btn-primary text-sm">
          {{ ctaText }}
        </NuxtLink>
      </div>

      <!-- Mobile CTA -->
      <div class="lg:hidden">
        <NuxtLink :to="ctaLink" class="btn-primary text-sm px-4 py-2">
          {{ ctaTextMobile }}
        </NuxtLink>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform -translate-y-4 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-4 opacity-0"
    >
      <div v-if="isOpen && navLinks.length" class="lg:hidden bg-white border-t-2 border-black p-6 shadow-lg absolute w-full">
        <div class="flex flex-col space-y-4">
          <NuxtLink
            v-for="link in navLinks"
            :key="link.text"
            :to="link.to"
            class="nav-link text-lg font-body font-medium transition-colors"
            :class="{ 'nav-active': isActiveLink(link.to) }"
            @click="isOpen = false"
          >
            {{ link.text }}
          </NuxtLink>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<style scoped>
.nav-link {
  color: #000;
}

.nav-link:hover {
  color: #f9375b !important;
}

.nav-active {
  color: #f9375b !important;
  font-weight: 600;
}
</style>
