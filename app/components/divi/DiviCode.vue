<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

/**
 * Render code blocks but strip <script> tags for security.
 * Keep structured data (JSON-LD) as plain text display or ignore.
 */
const sanitizedContent = computed(() => {
  let content = props.node.content || ''

  // Remove script tags entirely (security)
  content = content.replace(/<script\b[^]*?<\/script>/gi, '')

  // Remove line break holders that Divi uses
  content = content.replace(/<!--\s*\[et_pb_line_break_holder\]\s*-->/g, '\n')

  return content.trim()
})

const hasContent = computed(() => sanitizedContent.value.length > 0)
</script>

<template>
  <div v-if="hasContent" class="divi-code" v-html="sanitizedContent"></div>
</template>

<style lang="scss">
.divi-code {
  margin: 1rem 0;

  // If it contains a code block, style it
  pre, code {
    background: #f5f5f5;
    border-radius: 8px;
    padding: 1rem;
    overflow-x: auto;
    font-family: 'Fira Code', 'Consolas', monospace;
    font-size: 0.875rem;
  }
}
</style>
