<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const src = computed(() => props.node.attrs.src || '')
const alt = computed(() => props.node.attrs.alt || props.node.attrs.title_text || '')
const alignment = computed(() => props.node.attrs.align || 'center')
</script>

<template>
  <figure v-if="src" class="divi-image" :class="`divi-image--${alignment}`">
    <img
      :src="src"
      :alt="alt"
      loading="lazy"
      class="divi-image__img"
    />
  </figure>
</template>

<style lang="scss">
.divi-image {
  margin: 1.5rem 0;
  line-height: 0;

  &--center {
    text-align: center;
  }

  &--left {
    text-align: left;
  }

  &--right {
    text-align: right;
  }

  &__img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    display: inline-block;
  }
}
</style>
