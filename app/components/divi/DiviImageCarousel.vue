<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

// Extract carousel children
const images = computed(() =>
  props.node.children
    .filter(c => c.type === 'wdcl_image_carousel_child')
    .map(c => ({
      src: c.attrs.photo || '',
      alt: c.attrs.admin_title || '',
    }))
    .filter(img => img.src)
)

const slideCount = computed(() => {
  const count = parseInt(props.node.attrs.slide_count || '5')
  return isNaN(count) ? 5 : count
})

const scrollContainer = ref<HTMLElement | null>(null)

function scrollLeft() {
  if (!scrollContainer.value) return
  scrollContainer.value.scrollBy({ left: -300, behavior: 'smooth' })
}

function scrollRight() {
  if (!scrollContainer.value) return
  scrollContainer.value.scrollBy({ left: 300, behavior: 'smooth' })
}

// Auto-scroll (optional)
let autoScrollTimer: ReturnType<typeof setInterval> | null = null

onMounted(() => {
  if (images.value.length > slideCount.value) {
    autoScrollTimer = setInterval(() => {
      if (!scrollContainer.value) return
      const { scrollLeft: sl, scrollWidth, clientWidth } = scrollContainer.value
      if (sl + clientWidth >= scrollWidth - 10) {
        scrollContainer.value.scrollTo({ left: 0, behavior: 'smooth' })
      } else {
        scrollContainer.value.scrollBy({ left: 250, behavior: 'smooth' })
      }
    }, 3000)
  }
})

onUnmounted(() => {
  if (autoScrollTimer) clearInterval(autoScrollTimer)
})
</script>

<template>
  <div v-if="images.length > 0" class="divi-carousel">
    <button class="divi-carousel__nav divi-carousel__nav--prev" @click="scrollLeft" aria-label="Précédent">
      ‹
    </button>

    <div ref="scrollContainer" class="divi-carousel__track">
      <div
        v-for="(img, idx) in images"
        :key="`carousel-${idx}`"
        class="divi-carousel__item"
      >
        <img :src="img.src" :alt="img.alt" loading="lazy" />
      </div>
    </div>

    <button class="divi-carousel__nav divi-carousel__nav--next" @click="scrollRight" aria-label="Suivant">
      ›
    </button>
  </div>
</template>

<style lang="scss">
.divi-carousel {
  position: relative;
  margin: 2rem 0;
  padding: 0 2rem;

  &__track {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding: 0.5rem 0;

    &::-webkit-scrollbar {
      display: none;
    }
  }

  &__item {
    flex: 0 0 auto;
    width: 250px;
    border-radius: 8px;
    overflow: hidden;

    img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
      border-radius: 8px;
      transition: transform 0.3s ease;

      &:hover {
        transform: scale(1.05);
      }
    }

    @media (max-width: 768px) {
      width: 200px;

      img {
        height: 140px;
      }
    }
  }

  &__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #eee;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 1.25rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: #1a1a1a;

    &:hover {
      background: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    &--prev { left: 0; }
    &--next { right: 0; }
  }
}
</style>
