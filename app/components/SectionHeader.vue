<script setup lang="ts">
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  centered: {
    type: Boolean,
    default: false
  }
})
</script>

<template>
  <div :class="['mb-12', centered ? 'text-center' : '']">
    <h2 class="font-serif text-4xl md:text-5xl font-bold mb-4 relative inline-block">
      {{ title }}
      <!-- Playful underline decoration could go here -->
      <svg v-if="centered" class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-3 text-brand-mint z-[-1]" viewBox="0 0 100 10" preserveAspectRatio="none">
         <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
      </svg>
    </h2>
    <p v-if="subtitle" class="text-gray-600 text-lg md:text-xl max-w-2xl mx-auto mt-2">
      {{ subtitle }}
    </p>
  </div>
</template>
