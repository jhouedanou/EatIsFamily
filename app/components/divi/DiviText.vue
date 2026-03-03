<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const { rewriteInternalLinks } = useDiviParser()

const props = defineProps<{ node: DiviNode }>()

const textColor = computed(() => props.node.attrs.text_text_color || '')
const fontSize = computed(() => props.node.attrs.text_font_size || '')
const letterSpacing = computed(() => props.node.attrs.text_letter_spacing || '')
const lineHeight = computed(() => props.node.attrs.text_line_height || '')
const textOrientation = computed(() => {
  const o = props.node.attrs.text_orientation
  if (o === 'justified') return 'justify'
  return o || ''
})

const headingColor = computed(() => props.node.attrs.header_2_text_color || props.node.attrs.header_3_text_color || '')

const textStyle = computed(() => {
  const style: Record<string, string> = {}
  if (textColor.value) style.color = textColor.value
  if (fontSize.value && fontSize.value !== '') style.fontSize = fontSize.value
  if (letterSpacing.value) style.letterSpacing = letterSpacing.value
  if (lineHeight.value) style.lineHeight = lineHeight.value
  if (textOrientation.value) style.textAlign = textOrientation.value
  return style
})
</script>

<template>
  <div class="divi-text" :style="textStyle">
    <div v-html="rewriteInternalLinks(node.content)"></div>
    <DiviNodeRenderer
      v-for="(child, idx) in node.children"
      :key="`text-${idx}`"
      :node="child"
    />
  </div>
</template>

<style lang="scss">
.divi-text {
  margin-bottom: 1rem;
  word-wrap: break-word;

  h2, h3, h4 {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-weight: 700;
    line-height: 1.3;
    margin: 1.5rem 0 0.75rem;
  }

  h2 {
    font-size: 1.5rem;
    color: #000;
  }

  h3 {
    font-size: 1.25rem;
    color: #000;
  }

  p {
    margin: 0 0 1.25rem;
    line-height: 1.8;
    
    &:last-child {
      margin-bottom: 0;
    }
  }

  ul, ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
  }

  li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
  }

  a {
    color: #c39d63;
    text-decoration: underline;
    transition: color 0.2s ease;

    &:hover {
      color: #a88347;
    }
  }

  strong, b {
    font-weight: 700;
  }
}
</style>
