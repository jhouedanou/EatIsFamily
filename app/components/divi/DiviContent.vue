<script setup lang="ts">
/**
 * DiviContent.vue - Main Divi Content Renderer
 * 
 * Takes raw WordPress/Divi content and renders it using Vue components.
 * Automatically detects whether content contains Divi shortcodes
 * and falls back to plain v-html rendering if not.
 */
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{
  content: string
  /** Optional: CSS class for the wrapper */
  wrapperClass?: string
}>()

const { parseDiviContent, isDiviContent } = useDiviParser()

const hasDivi = computed(() => isDiviContent(props.content))
const diviTree = computed(() => {
  if (!hasDivi.value) return []
  return parseDiviContent(props.content)
})
</script>

<template>
  <!-- Divi-structured content -->
  <div v-if="hasDivi" :class="['divi-content', wrapperClass]">
    <DiviNodeRenderer
      v-for="(node, index) in diviTree"
      :key="`divi-${index}`"
      :node="node"
    />
  </div>

  <!-- Plain HTML content (non-Divi) -->
  <div v-else :class="['article-content', wrapperClass]" v-html="content"></div>
</template>

<style lang="scss">
.divi-content {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
}
</style>
