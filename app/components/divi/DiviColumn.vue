<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()
</script>

<template>
  <div class="divi-column">
    <DiviNodeRenderer
      v-for="(child, idx) in node.children"
      :key="`col-${idx}`"
      :node="child"
    />
    <div v-if="node.content && node.children.length === 0" v-html="node.content"></div>
  </div>
</template>

<style lang="scss">
.divi-column {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
