<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

// Extract slide children
const slides = computed(() =>
  props.node.children.filter(c => c.type === 'et_pb_slide')
)

const currentSlide = ref(0)
let autoplayTimer: ReturnType<typeof setInterval> | null = null

const autoSpeed = computed(() => {
  const speed = parseInt(props.node.attrs.auto_speed || '4000')
  return isNaN(speed) ? 4000 : speed
})

const isAuto = computed(() => props.node.attrs.auto === 'on')

function nextSlide() {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

function prevSlide() {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
}

function goToSlide(index: number) {
  currentSlide.value = index
}

onMounted(() => {
  if (isAuto.value && slides.value.length > 1) {
    autoplayTimer = setInterval(nextSlide, autoSpeed.value)
  }
})

onUnmounted(() => {
  if (autoplayTimer) clearInterval(autoplayTimer)
})
</script>

<template>
  <div v-if="slides.length > 0" class="divi-slider">
    <div class="divi-slider__viewport">
      <div
        v-for="(slide, idx) in slides"
        :key="`slide-${idx}`"
        class="divi-slider__slide"
        :class="{ 'divi-slider__slide--active': idx === currentSlide }"
        :style="{
          backgroundImage: slide.attrs.background_image ? `url(${slide.attrs.background_image})` : 'none',
        }"
      >
        <!-- Overlay -->
        <div
          v-if="slide.attrs.use_bg_overlay === 'on'"
          class="divi-slider__overlay"
        ></div>

        <div class="divi-slider__content">
          <!-- Heading -->
          <h2 v-if="slide.attrs.heading" class="divi-slider__heading">
            {{ slide.attrs.heading }}
          </h2>

          <!-- Body content -->
          <div v-if="slide.content" class="divi-slider__body" v-html="slide.content"></div>

          <!-- Button -->
          <a
            v-if="slide.attrs.button_text"
            :href="slide.attrs.button_link || '#'"
            class="divi-slider__button"
            :style="{
              backgroundColor: slide.attrs.button_bg_color || '#000',
              color: '#fff',
            }"
          >
            {{ slide.attrs.button_text }}
          </a>
        </div>
      </div>
    </div>

    <!-- Navigation arrows -->
    <button v-if="slides.length > 1" class="divi-slider__nav divi-slider__nav--prev" @click="prevSlide" aria-label="Slide précédent">
      ‹
    </button>
    <button v-if="slides.length > 1" class="divi-slider__nav divi-slider__nav--next" @click="nextSlide" aria-label="Slide suivant">
      ›
    </button>

    <!-- Dots -->
    <div v-if="slides.length > 1" class="divi-slider__dots">
      <button
        v-for="(_, idx) in slides"
        :key="`dot-${idx}`"
        class="divi-slider__dot"
        :class="{ 'divi-slider__dot--active': idx === currentSlide }"
        @click="goToSlide(idx)"
        :aria-label="`Slide ${idx + 1}`"
      ></button>
    </div>
  </div>
</template>

<style lang="scss">
.divi-slider {
  position: relative;
  width: 100%;
  overflow: hidden;
  border-radius: 8px;
  margin: 1.5rem 0;

  &__viewport {
    position: relative;
    width: 100%;
    min-height: 400px;

    @media (max-width: 768px) {
      min-height: 300px;
    }
  }

  &__slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.6s ease;
    z-index: 0;

    &--active {
      opacity: 1;
      z-index: 1;
      position: relative;
    }
  }

  &__overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.35);
    z-index: 1;
  }

  &__content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 3rem 2rem;
    max-width: 800px;
    color: #fff;
  }

  &__heading {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    font-weight: 600;
    margin: 0 0 1rem;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    color: #fff;
  }

  &__body {
    font-size: 1.125rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);

    p {
      margin: 0 0 0.5rem;
      color: #fff;
    }
  }

  &__button {
    display: inline-block;
    padding: 0.75em 1.5em;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    color: #fff !important;
    font-size: 1rem;

    &:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
  }

  &__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255, 255, 255, 0.85);
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: #1a1a1a;

    &:hover {
      background: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    &--prev { left: 1rem; }
    &--next { right: 1rem; }
  }

  &__dots {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 0.5rem;
  }

  &__dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    background: transparent;
    cursor: pointer;
    transition: background 0.2s ease;
    padding: 0;

    &--active {
      background: #fff;
    }
  }
}
</style>
