<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const isFullwidth = computed(() => props.node.attrs.fullwidth === 'on')
</script>

<template>
  <section :class="['divi-section', { 'divi-section--fullwidth': isFullwidth }]">
    <DiviNodeRenderer
      v-for="(child, idx) in node.children"
      :key="`section-${idx}`"
      :node="child"
    />
    <div v-if="node.content" class="divi-section__content" v-html="node.content"></div>
  </section>
</template>

<style lang="scss">
.divi-section {
  width: 100%;
  margin: 0 auto;
  padding: 2rem 0;

  &--fullwidth {
    max-width: 100%;
    padding: 0;
  }
}
</style>
