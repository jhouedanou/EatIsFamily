<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const buttonText = computed(() => props.node.attrs.button_text || '')
const buttonUrl = computed(() => {
  // Convert WordPress URLs to local paths if needed
  let url = props.node.attrs.button_url || '#'
  // Replace WordPress base URL with local equivalent
  url = url.replace(/https?:\/\/www\.eatisfamily\.fr\/api\/?/g, '/')
  return url
})
const alignment = computed(() => props.node.attrs.button_alignment || 'center')
const bgColor = computed(() => props.node.attrs.button_bg_color || '#c39d63')
const textColor = computed(() => props.node.attrs.button_text_color || '#FFFFFF')
const borderRadius = computed(() => props.node.attrs.button_border_radius || '12px')
const borderColor = computed(() => props.node.attrs.button_border_color || '#000000')
const borderWidth = computed(() => props.node.attrs.button_border_width || '1px')
const fontSize = computed(() => props.node.attrs.button_text_size || '18px')
const letterSpacing = computed(() => props.node.attrs.button_letter_spacing || '1px')

const isExternal = computed(() => {
  return buttonUrl.value.startsWith('http') || buttonUrl.value.startsWith('//')
})
</script>

<template>
  <div v-if="buttonText" class="divi-button-wrapper" :class="`divi-button-wrapper--${alignment}`">
    <a
      v-if="isExternal"
      :href="buttonUrl"
      class="divi-button"
      :style="{
        backgroundColor: bgColor,
        color: textColor,
        borderRadius: borderRadius,
        border: `${borderWidth} solid ${borderColor}`,
        fontSize: fontSize,
        letterSpacing: letterSpacing,
      }"
      target="_blank"
      rel="noopener noreferrer"
    >
      {{ buttonText }}
    </a>
    <NuxtLink
      v-else
      :to="buttonUrl"
      class="divi-button"
      :style="{
        backgroundColor: bgColor,
        color: textColor,
        borderRadius: borderRadius,
        border: `${borderWidth} solid ${borderColor}`,
        fontSize: fontSize,
        letterSpacing: letterSpacing,
      }"
    >
      {{ buttonText }}
    </NuxtLink>
  </div>
</template>

<style lang="scss">
.divi-button-wrapper {
  margin: 1.5rem 0;

  &--center { text-align: center; }
  &--left { text-align: left; }
  &--right { text-align: right; }
}

.divi-button {
  display: inline-block;
  padding: 0.75em 1.5em;
  text-decoration: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0.9;
  }

  @media (max-width: 768px) {
    font-size: 16px !important;
    padding: 0.6em 1.2em;
  }
}
</style>
