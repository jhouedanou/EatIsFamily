<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

/**
 * Parse column structure from attrs, e.g. column_structure="1_3,1_3,1_3"
 * Or detect from children column types
 */
const columnLayout = computed(() => {
  const structure = props.node.attrs.column_structure
  if (structure) {
    return structure.split(',').map(s => s.trim())
  }
  // Detect from children
  return props.node.children
    .filter(c => c.type === 'et_pb_column' || c.type === 'et_pb_column_inner')
    .map(c => c.attrs.type || '4_4')
})

/**
 * Convert Divi fraction to CSS grid fraction
 * e.g. "1_3" => "1fr", "2_3" => "2fr", "1_2" => "1fr"
 */
const gridTemplateColumns = computed(() => {
  if (columnLayout.value.length === 0) return '1fr'

  return columnLayout.value.map(col => {
    const parts = col.split('_')
    if (parts.length === 2) {
      const num = parseInt(parts[0])
      const den = parseInt(parts[1])
      if (!isNaN(num) && !isNaN(den) && den > 0) {
        // Convert fraction to relative size
        return `${num}fr`
      }
    }
    return '1fr'
  }).join(' ')
})

const isCustomGutter = computed(() => props.node.attrs.use_custom_gutter === 'on')
const gutterWidth = computed(() => {
  if (!isCustomGutter.value) return '30px'
  const gw = parseInt(props.node.attrs.gutter_width || '3')
  // Divi gutter: 1=no gap, 2=small, 3=default, 4=large
  const map: Record<number, string> = { 1: '0px', 2: '15px', 3: '30px', 4: '45px' }
  return map[gw] || '30px'
})
</script>

<template>
  <div
    class="divi-row"
    :style="{
      display: 'grid',
      gridTemplateColumns: gridTemplateColumns,
      gap: gutterWidth,
    }"
  >
    <DiviNodeRenderer
      v-for="(child, idx) in node.children"
      :key="`row-${idx}`"
      :node="child"
    />
    <div v-if="node.content && node.children.length === 0" v-html="node.content"></div>
  </div>
</template>

<style lang="scss">
.divi-row {
  max-width: 1080px;
  margin: 0 auto;
  padding: 1rem 2rem;

  @media (max-width: 980px) {
    grid-template-columns: 1fr !important;
    padding: 1rem;
  }

  @media (max-width: 768px) {
    padding: 0.5rem 1rem;
  }
}
</style>
