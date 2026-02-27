<script setup lang="ts">
import { ref } from 'vue'
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const isOpen = ref(props.node.attrs.open === 'on')
const title = computed(() => props.node.attrs.title || 'Toggle')
</script>

<template>
  <div class="divi-toggle" :class="{ 'divi-toggle--open': isOpen }">
    <button class="divi-toggle__header" @click="isOpen = !isOpen">
      <span>{{ title }}</span>
      <span class="divi-toggle__icon">{{ isOpen ? '−' : '+' }}</span>
    </button>
    <div v-show="isOpen" class="divi-toggle__body" v-html="node.content"></div>
  </div>
</template>

<style lang="scss">
.divi-toggle {
  margin: 1rem 0;
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;

  &__header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #fff;
    border: none;
    cursor: pointer;
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    text-align: left;

    &:hover {
      background: #f5f5f5;
    }
  }

  &__icon {
    font-size: 1.25rem;
    font-weight: 700;
    color: #c39d63;
  }

  &__body {
    padding: 1rem 1.25rem;
    font-size: 0.9375rem;
    line-height: 1.7;
    color: #444;

    p { margin: 0 0 0.75rem; }
  }
}
</style>
