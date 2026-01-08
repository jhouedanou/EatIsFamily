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
  <div :class="['mb-5', centered ? 'text-center' : '']">
    <h2 class="font-heading display-5 fw-bold mb-3 position-relative d-inline-block">
      {{ title }}
      <!-- Playful underline decoration could go here -->
      <svg v-if="centered" class="position-absolute bottom-0 start-50 translate-middle-x text-brand-mint" style="width: 6rem; height: 0.75rem; z-index: -1;" viewBox="0 0 100 10" preserveAspectRatio="none">
         <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
      </svg>
    </h2>
    <p v-if="subtitle" class="text-muted fs-5 mx-auto mt-2" style="max-width: 42rem;">
      {{ subtitle }}
    </p>
  </div>
</template>
