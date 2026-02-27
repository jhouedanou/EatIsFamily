<script setup lang="ts">
import { ref } from 'vue'
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const items = computed(() =>
  props.node.children.filter(
    c => c.type === 'et_pb_accordion_item' || c.type === 'et_pb_toggle'
  )
)

const openIndex = ref(0) // First item open by default

function toggleItem(index: number) {
  openIndex.value = openIndex.value === index ? -1 : index
}
</script>

<template>
  <div class="divi-accordion">
    <div
      v-for="(item, idx) in items"
      :key="`acc-${idx}`"
      class="divi-accordion__item"
      :class="{ 'divi-accordion__item--open': openIndex === idx }"
    >
      <button class="divi-accordion__header" @click="toggleItem(idx)">
        <span>{{ item.attrs.title || `Section ${idx + 1}` }}</span>
        <span class="divi-accordion__icon">{{ openIndex === idx ? '−' : '+' }}</span>
      </button>
      <div v-show="openIndex === idx" class="divi-accordion__body" v-html="item.content"></div>
    </div>
  </div>
</template>

<style lang="scss">
.divi-accordion {
  margin: 1.5rem 0;
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;

  &__item {
    border-bottom: 1px solid #eee;

    &:last-child {
      border-bottom: none;
    }

    &--open .divi-accordion__header {
      background: #f9f9f9;
    }
  }

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
    transition: background 0.2s ease;

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

    p {
      margin: 0 0 0.75rem;
    }
  }
}
</style>
