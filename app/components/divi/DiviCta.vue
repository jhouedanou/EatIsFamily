<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const title = computed(() => props.node.attrs.title || '')
const buttonText = computed(() => props.node.attrs.button_text || '')
const buttonUrl = computed(() => props.node.attrs.button_url || '#')
const bgColor = computed(() => props.node.attrs.background_color || '#c39d63')
const textColor = computed(() => props.node.attrs.text_color || '#fff')
</script>

<template>
  <div class="divi-cta" :style="{ backgroundColor: bgColor, color: textColor }">
    <h2 v-if="title" class="divi-cta__title">{{ title }}</h2>
    <div v-if="node.content" class="divi-cta__body" v-html="node.content"></div>
    <a v-if="buttonText" :href="buttonUrl" class="divi-cta__button">{{ buttonText }}</a>
  </div>
</template>

<style lang="scss">
.divi-cta {
  padding: 3rem 2rem;
  border-radius: 12px;
  text-align: center;
  margin: 2rem 0;

  &__title {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 1rem;
  }

  &__body {
    font-size: 1.125rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;

    p {
      margin: 0 0 0.5rem;
    }
  }

  &__button {
    display: inline-block;
    padding: 0.75em 2em;
    background: #fff;
    color: #1a1a1a;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;

    &:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
  }
}
</style>
