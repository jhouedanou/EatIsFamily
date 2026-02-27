<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const name = computed(() => props.node.attrs.author || '')
const company = computed(() => props.node.attrs.company_name || '')
const jobTitle = computed(() => props.node.attrs.job_title || '')
const portrait = computed(() => props.node.attrs.portrait_url || '')
const quoteIcon = computed(() => props.node.attrs.quote_icon === 'on' || !props.node.attrs.quote_icon)
</script>

<template>
  <blockquote class="divi-testimonial">
    <div class="divi-testimonial__content">
      <div v-if="portrait" class="divi-testimonial__avatar">
        <img :src="portrait" :alt="name" loading="lazy" />
      </div>
      <div class="divi-testimonial__text">
        <div v-if="quoteIcon" class="divi-testimonial__quote-icon">"</div>
        <div class="divi-testimonial__body" v-html="node.content"></div>
        <footer class="divi-testimonial__footer">
          <strong v-if="name">{{ name }}</strong>
          <span v-if="jobTitle"> — {{ jobTitle }}</span>
          <span v-if="company">, {{ company }}</span>
        </footer>
      </div>
    </div>
  </blockquote>
</template>

<style lang="scss">
.divi-testimonial {
  margin: 2rem 0;
  padding: 0;
  border: none;

  &__content {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    background: #f9f9f9;
    border-radius: 12px;
    padding: 2rem;
  }

  &__avatar {
    flex: 0 0 80px;

    img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
    }
  }

  &__text {
    flex: 1;
  }

  &__quote-icon {
    font-family: Georgia, serif;
    font-size: 3rem;
    line-height: 1;
    color: #c39d63;
    margin-bottom: -0.5rem;
  }

  &__body {
    font-size: 1rem;
    line-height: 1.7;
    color: #444;
    font-style: italic;

    p { margin: 0 0 0.75rem; }
  }

  &__footer {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #888;
    font-style: normal;

    strong {
      color: #1a1a1a;
    }
  }

  @media (max-width: 768px) {
    &__content {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
  }
}
</style>
