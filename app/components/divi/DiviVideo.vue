<script setup lang="ts">
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const videoSrc = computed(() => props.node.attrs.src || '')
const posterSrc = computed(() => props.node.attrs.image_src || '')

const isYouTube = computed(() =>
  videoSrc.value.includes('youtube.com') || videoSrc.value.includes('youtu.be')
)

const isVimeo = computed(() =>
  videoSrc.value.includes('vimeo.com')
)

const youtubeId = computed(() => {
  if (!isYouTube.value) return ''
  const match = videoSrc.value.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/)
  return match ? match[1] : ''
})

const vimeoId = computed(() => {
  if (!isVimeo.value) return ''
  const match = videoSrc.value.match(/vimeo\.com\/(\d+)/)
  return match ? match[1] : ''
})

const isDirectVideo = computed(() =>
  videoSrc.value.endsWith('.mp4') || videoSrc.value.endsWith('.webm') || videoSrc.value.endsWith('.ogg')
)
</script>

<template>
  <div v-if="videoSrc" class="divi-video">
    <!-- YouTube embed -->
    <div v-if="isYouTube && youtubeId" class="divi-video__embed">
      <iframe
        :src="`https://www.youtube.com/embed/${youtubeId}`"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
        loading="lazy"
      ></iframe>
    </div>

    <!-- Vimeo embed -->
    <div v-else-if="isVimeo && vimeoId" class="divi-video__embed">
      <iframe
        :src="`https://player.vimeo.com/video/${vimeoId}`"
        frameborder="0"
        allow="autoplay; fullscreen; picture-in-picture"
        allowfullscreen
        loading="lazy"
      ></iframe>
    </div>

    <!-- Direct video file -->
    <div v-else-if="isDirectVideo" class="divi-video__player">
      <video
        controls
        preload="metadata"
        :poster="posterSrc"
        class="divi-video__native"
      >
        <source :src="videoSrc" :type="`video/${videoSrc.split('.').pop()}`" />
        Votre navigateur ne supporte pas la lecture vidéo.
      </video>
    </div>

    <!-- Fallback: link -->
    <div v-else class="divi-video__fallback">
      <a :href="videoSrc" target="_blank" rel="noopener noreferrer">
        Voir la vidéo →
      </a>
    </div>
  </div>
</template>

<style lang="scss">
.divi-video {
  margin: 2rem 0;
  border-radius: 12px;
  overflow: hidden;

  &__embed {
    position: relative;
    padding-bottom: 56.25%; // 16:9
    height: 0;

    iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-radius: 12px;
    }
  }

  &__player {
    position: relative;
  }

  &__native {
    width: 100%;
    border-radius: 12px;
    display: block;
  }

  &__fallback {
    padding: 2rem;
    text-align: center;
    background: #f5f5f5;
    border-radius: 12px;

    a {
      color: #c39d63;
      font-weight: 600;
      text-decoration: none;

      &:hover {
        text-decoration: underline;
      }
    }
  }
}
</style>
