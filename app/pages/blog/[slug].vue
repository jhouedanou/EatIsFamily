<script setup lang="ts">
import { LucideX } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const { getBlogPostBySlug } = useBlog()

// Récupérer l'article depuis le serveur
const slug = route.params.slug as string
const { data: article } = await useAsyncData(`blog-post-${slug}`, () => getBlogPostBySlug(slug))

// Redirection si article non trouvé
if (!article.value) {
  navigateTo('/blog')
}

const goBack = () => {
  router.back()
}

// Formater la date
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now.getTime() - date.getTime())
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24))
  const diffHours = Math.floor(diffTime / (1000 * 60 * 60))

  if (diffHours < 24) {
    return `Publié il y a ${diffHours} heure${diffHours !== 1 ? 's' : ''}`
  } else if (diffDays === 1) {
    return 'Publié il y a 1 jour'
  } else {
    return `Publié il y a ${diffDays} jours`
  }
}
</script>

<template>
  <div class="blog-detail-page">
    <!-- Close Button -->
    <button class="close-btn" @click="goBack" aria-label="Close">
      <LucideX :size="24" />
    </button>

    <article v-if="article" class="article-container">
      <!-- Header -->
      <header class="article-header">
        <h1 class="article-title">
          {{ article.title.rendered }}
        </h1>
        <p class="article-date">{{ formatDate(article.date) }}</p>
      </header>

      <!-- Featured Image -->
      <div class="article-image">
        <img :src="article.featured_media" :alt="article.title.rendered" />
      </div>

      <!-- Content -->
      <div class="article-content" v-html="article.content.rendered"></div>
    </article>
  </div>
</template>

<style scoped lang="scss">
.blog-detail-page {
  min-height: 100vh;
  background: #fff;
  padding: 2rem 0 4rem;
  position: relative;
}

// Close button
.close-btn {
  position: fixed;
  top: 1.5rem;
  right: 2rem;
  width: 48px;
  height: 48px;
  background: #FFDD00;
  border: 2px solid #1a1a1a;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 100;

  &:hover {
    transform: scale(1.05);
    box-shadow: 2px 2px 0 #1a1a1a;
  }
}

// Article container
.article-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 0 2rem;
}

// Header
.article-header {
  padding-top: 2rem;
  margin-bottom: 2rem;
}

.article-title {
  font-family: var(--font-heading, 'Recoleta', serif);
  font-size: clamp(2rem, 4vw, 2.75rem);
  font-weight: 700;
  line-height: 1.2;
  color: #1a1a1a;
  margin: 0 0 1rem;
}

.article-date {
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
  font-size: 0.9375rem;
  color: #666;
  margin: 0;
}

// Featured image
.article-image {
  margin-bottom: 2.5rem;
  border-radius: 12px;
  overflow: hidden;

  img {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 16 / 10;
    object-fit: cover;
  }
}

// Content
.article-content {
 font-family: FONTSPRINGDEMO-Recoleta;
  
 font-size: 18px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.56;
  letter-spacing: normal;
  text-align: left;
color: #000;

:deep(strong) {
  font-weight: bold;
  }

  /* User list styles */
  :deep(.user-list) {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
    padding: 0;
    list-style: none;
  }

  :deep(.user-list__item) {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 0.75rem;
    display: flex;
    gap: 0.75rem;
    align-items: center;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }

  :deep(.user-list__item:hover) {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(26, 26, 26, 0.08);
  }

  :deep(.user-avatar) {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    overflow: hidden;
    flex: 0 0 56px;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
  }

  :deep(.user-info) {
    flex: 1;
    min-width: 0;
  }

  :deep(.user-name) {
    font-weight: 600;
    font-size: 0.95rem;
    color: #1a1a1a;
    margin: 0 0 0.25rem;
  }

  :deep(.user-meta) {
    font-size: 0.8125rem;
    color: #666;
    margin: 0;
  }

  /* Responsive tweaks */
  @media (max-width: 480px) {
    :deep(.user-avatar) { width: 44px; height: 44px; flex: 0 0 44px; }
    :deep(.user-list) { grid-template-columns: 1fr; }
  }

  :deep(p) {
    margin: 0 0 1.75rem;

    &:last-child {
      margin-bottom: 0;
    }
  }

  :deep(h2) {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 2.5rem 0 1rem;
  }

  :deep(h3) {
    font-family: var(--font-heading, 'Recoleta', serif);
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 2rem 0 0.75rem;
  }

  :deep(ul), :deep(ol) {
    margin: 0 0 1.75rem;
    padding-left: 1.5rem;
  }

  :deep(li) {
    margin-bottom: 0.5rem;
  }

  :deep(blockquote) {
    margin: 2rem 0;
    padding: 1.5rem 2rem;
    background: #f8f8f8;
    border-left: 4px solid #FF4D6D;
    font-style: italic;
    color: #555;
  }

  :deep(a) {
    color: #FF4D6D;
    text-decoration: underline;
    transition: color 0.2s ease;

    &:hover {
      color: #e04460;
    }
  }
}

// Responsive
@media (max-width: 768px) {
  .blog-detail-page {
    padding: 1.5rem 0 3rem;
  }

  .close-btn {
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
  }

  .article-container {
    padding: 0 1.25rem;
  }

  .article-header {
    padding-top: 3rem;
  }

  .article-title {
    font-size: 1.75rem;
  }
}

@media (max-width: 480px) {
  .article-content {
    font-size: 0.9375rem;
    line-height: 1.7;
  }
}
</style>
