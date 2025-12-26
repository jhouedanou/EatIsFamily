<script setup lang="ts">
import { LucideLinkedin, LucideInstagram, LucideTwitter, LucideHeart } from 'lucide-vue-next'

const companyLinks = [
  { text: 'About us', to: '/about' },
  { text: 'Activities', to: '/activities' },
  { text: 'Our Events', to: '/events' },
  { text: 'Careers', to: '/careers' },
  { text: 'Blogs', to: '/blog' },
  { text: 'Contact us', to: '/contact' }
]

const policyLinks = [
  { text: 'Privacy Statement', to: '/privacy' },
  { text: 'Terms and condition', to: '/terms' },
  { text: 'Cookies policy', to: '/cookies' }
]

const socialLinks = [
  { icon: LucideLinkedin, href: '#', label: 'LinkedIn' },
  { icon: LucideInstagram, href: '#', label: 'Instagram' },
  { icon: LucideTwitter, href: '#', label: 'Twitter' }
]
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
            <span class="font-heading text-xl font-bold">Eat is Family</span>
          </div>
          
          <p class="text-gray-400 mb-6 font-body text-sm leading-relaxed">
            Creating unforgettable culinary experiences that bring people together and celebrate life's special moments.
          </p>
          
          <div class="space-y-2 text-sm text-gray-400">
            <p>hello@eatsfamily.fr</p>
            <p>+33 1 23 5 67 89</p>
          </div>
          
          <!-- Social Links -->
          <div class="flex gap-3 mt-6">
            <a 
              v-for="social in socialLinks" 
              :key="social.label"
              :href="social.href" 
              :aria-label="social.label"
              class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center hover:bg-white hover:text-brand-dark transition-all duration-300"
            >
              <component :is="social.icon" class="w-4 h-4" />
            </a>
          </div>
        </div>

        <!-- Company Links -->
        <div>
          <h4 class="font-heading font-bold text-lg mb-6">Company</h4>
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
          <h4 class="font-heading font-bold text-lg mb-6">Policy Info</h4>
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
        <p>© {{ new Date().getFullYear() }} Eat is Family. All Rights Reserved.</p>
        <a href="#top" class="mt-4 md:mt-0 hover:text-white transition-colors flex items-center gap-2">
          Back to top ↑
        </a>
      </div>
    </div>
  </footer>
</template>
