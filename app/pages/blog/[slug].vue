<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LucideArrowRight, LucideX } from 'lucide-vue-next'

const { getContentByPath } = usePageContent()
const content = ref<any>(null)

// In a real app, this would come from an API based on route params
const article = {
  title: 'The Future Of Stadium Catering: 5 Trends Reshaping The Industry',
  date: 'Posted 3 hours ago',
  image: 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=1200&auto=format&fit=crop',
  content: `
    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>

    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>

    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>

    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>
  `
}

onMounted(async () => {
  content.value = await getContentByPath('blog.detail')
})
</script>

<template>
  <div v-if="content" class="min-vh-100 bg-white">
    <!-- Close Button -->
    <button class="position-fixed top-0 end-0 mt-4 me-4 close-btn rounded-circle bg-brand-yellow d-flex align-items-center justify-content-center border border-2 border-dark shadow-organic">
      <LucideX style="width: 1.5rem; height: 1.5rem;" />
    </button>

    <article class="container py-5 article-container">
      <!-- Header -->
      <header class="mb-4">
        <h1 class="font-heading display-4 fw-bold lh-sm mb-3">
          <span class="position-relative d-inline">
            {{ article.title.split(':')[0] }}:
          </span>
          <span class="position-relative d-inline-block">
            {{ article.title.split(':')[1] }}
            <span class="position-absolute bottom-0 start-0 w-100 bg-brand-yellow highlight-bar"></span>
          </span>
        </h1>
        <p class="text-muted font-body">{{ article.date }}</p>
      </header>

      <!-- Featured Image -->
      <div class="mb-5 border-organic overflow-hidden">
        <NuxtImg
          :src="article.image"
          class="w-100 h-auto object-fit-cover"
          :alt="content.featured_image_alt"
        />
      </div>

      <!-- Content -->
      <div class="article-content font-body text-muted lh-lg" v-html="article.content">
      </div>

      <!-- Share / Navigation -->
      <div class="mt-5 pt-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <NuxtLink to="/blog" class="text-brand-pink fw-bold text-decoration-none d-flex align-items-center gap-2">
          ← {{ content.back_link }}
        </NuxtLink>

        <div class="d-flex gap-3">
          <button class="btn-secondary small">{{ content.share_button }}</button>
          <button class="btn-primary small d-flex align-items-center gap-2">
            {{ content.next_article_button }} <LucideArrowRight style="width: 1rem; height: 1rem;" />
          </button>
        </div>
      </div>
    </article>
  </div>
</template>

<style scoped>
.close-btn {
  width: 3rem;
  height: 3rem;
  z-index: 50;
  transition: transform 0.3s ease;
}

.close-btn:hover {
  transform: scale(1.1);
}

.article-container {
  max-width: 56rem;
}

.highlight-bar {
  height: 1rem;
  z-index: -1;
  transform: rotate(-1deg);
}

.article-content p {
  margin-bottom: 1.5rem;
}
</style>
