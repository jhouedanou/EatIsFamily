<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  to: {
    type: String,
    default: null
  },
  href: {
    type: String,
    default: null
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value: string) => ['primary', 'secondary', 'lime', 'outline', 'dark'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  },
  block: {
    type: Boolean,
    default: false
  }
})

const isLink = computed(() => !!props.to)
const isExternalLink = computed(() => !!props.href)
const componentType = computed(() => {
  if (isLink.value) return 'NuxtLink'
  if (isExternalLink.value) return 'a'
  return 'button'
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'bg-brand-pink text-white'
    case 'secondary':
      return 'bg-white text-brand-dark'
    case 'lime':
      return 'bg-brand-lime text-brand-dark'
    case 'outline':
      return 'bg-transparent text-brand-dark'
    case 'dark':
      return 'bg-brand-dark text-white'
    default:
      return ''
  }
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-4 py-2 text-sm'
    case 'md':
      return 'px-6 py-3 text-base'
    case 'lg':
      return 'px-8 py-4 text-lg'
    default:
      return ''
  }
})
</script>

<template>
  <component
    :is="componentType"
    :to="to"
    :href="href"
    :class="[
      'btn-organic font-bold',
      variantClasses,
      sizeClasses,
      block ? 'w-full justify-center' : ''
    ]"
  >
    <slot />
  </component>
</template>
