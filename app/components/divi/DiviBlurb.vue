<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const title = computed(() => props.node.attrs.title || '')
const useIcon = computed(() => props.node.attrs.use_icon === 'on')
const iconColor = computed(() => props.node.attrs.icon_color || '#000000')
const bgColor = computed(() => props.node.attrs.background_color || 'transparent')
const image = computed(() => props.node.attrs.image || '')
const padding = computed(() => props.node.attrs.custom_padding || '15px')

const bodyTextAlign = computed(() => props.node.attrs.body_text_align || 'left')
const bodyFontSize = computed(() => props.node.attrs.body_font_size || '16px')
const headerTextAlign = computed(() => props.node.attrs.header_text_align || bodyTextAlign.value)
const headerColor = computed(() => props.node.attrs.header_text_color || '#000000')
const bodyColor = computed(() => props.node.attrs.body_text_color || '#000000')

/**
 * Parse Divi font icon code to a displayable character
 * Divi uses codes like &#xe033; or &#xf0e7; (FontAwesome/ETModules)
 */
const iconCharacter = computed(() => {
  const fontIcon = props.node.attrs.font_icon || ''
  // Extract hex code from patterns like &#xe033;||divi||400 or &#xf0e7;||fa||900
  const match = fontIcon.match(/&#x([a-f0-9]+);/i)
  if (match && match[1]) return String.fromCodePoint(parseInt(match[1], 16))
  return '★' // Fallback icon
})

const iconFontFamily = computed(() => {
  const fontIcon = props.node.attrs.font_icon || ''
  if (fontIcon.includes('||fa||')) return "'Font Awesome 6 Free', 'Font Awesome 5 Free', FontAwesome"
  return "'ETmodules'"
})
</script>

<template>
  <div
    class="divi-blurb"
    :style="{
      backgroundColor: bgColor,
      padding: padding,
      textAlign: bodyTextAlign as any,
    }"
  >
    <!-- Icon -->
    <div v-if="useIcon" class="divi-blurb__icon" :style="{ color: iconColor }">
      <span :style="{ fontFamily: iconFontFamily, fontSize: '50px' }">{{ iconCharacter }}</span>
    </div>

    <!-- Image (if no icon) -->
    <div v-else-if="image" class="divi-blurb__image">
      <img :src="image" :alt="title" loading="lazy" />
    </div>

    <!-- Title -->
    <h3
      v-if="title"
      class="divi-blurb__title"
      :style="{ textAlign: headerTextAlign as any, color: headerColor }"
    >
      {{ title }}
    </h3>

    <!-- Body content -->
    <div
      class="divi-blurb__body"
      :style="{ fontSize: bodyFontSize, color: bodyColor, textAlign: bodyTextAlign as any }"
      v-html="node.content"
    ></div>
  </div>
</template>

<style lang="scss">
.divi-blurb {
  border-radius: 8px;
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-4px);
  }

  &__icon {
    margin-bottom: 1rem;
    line-height: 1;
  }

  &__image {
    margin-bottom: 1rem;

    img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }
  }

  &__title {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0 0 0.75rem;
    line-height: 1.3;
  }

  &__body {
    line-height: 1.5;

    p {
      margin: 0;
    }

    a {
      color: inherit;
      text-decoration: underline;
    }
  }
}
</style>
