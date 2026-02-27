<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue'

/**
 * Decode HTML entities that WordPress may leave in titles/excerpts
 */
const decodeHtml = (html: string): string => {
  if (!html) return ''
  return html
    .replace(/&#038;/g, '&')
    .replace(/&amp;/g, '&')
    .replace(/&#8230;/g, '…')
    .replace(/&hellip;/g, '…')
    .replace(/\[…\]/g, '…')
    .replace(/\[&hellip;\]/g, '…')
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"')
    .replace(/&#039;/g, "'")
    .replace(/&nbsp;/g, ' ')
}

/**
 * Strip HTML tags and decode entities for plain text excerpts.
 * If the content is Divi-formatted, extract text from the Divi tree instead.
 */
const cleanExcerpt = (html: string, fullContent?: string): string => {
  // If excerpt is empty but we have Divi content, extract from content
  if ((!html || html.trim() === '') && fullContent && isDiviContent(fullContent)) {
    return getDiviExcerpt(fullContent, 200)
  }
  // If excerpt itself contains Divi shortcodes, extract plain text
  if (html && isDiviContent(html)) {
    return getDiviExcerpt(html, 200)
  }
  if (!html) return ''
  // Remove HTML tags first, then decode entities
  const stripped = html.replace(/<[^>]*>/g, '').trim()
  return decodeHtml(stripped)
}

const { getBlogPosts } = useBlog()
const { settings } = useGlobalSettings()
const { getBlogPageContent } = usePageContent()
const { getButton, loadButtons } = useButtons()
const { isDiviContent, getDiviExcerpt } = useDiviParser()

// Dynamic button URL with fallback
const btnReadMore = computed(() => settings.value?.icons?.btn_read_more || '/images/btnReadMore.svg')

// Charger les articles depuis le serveur
const { data: posts } = await useAsyncData('blog-posts', () => getBlogPosts())

// Charger le contenu de la page blog depuis WordPress
const { data: blogContent } = await useAsyncData('blog-page-content', () => getBlogPageContent())

// Charger les boutons
await loadButtons()

// Les 2 premiers posts sont "featured", les autres dans la grille
const featuredPosts = computed(() => posts.value?.slice(0, 2) || [])
const allPosts = computed(() => posts.value?.slice(2) || [])

// Infinite scroll : afficher 4 articles au départ, puis charger par lots de 4
const postsPerLoad = 4
const visibleCount = ref(postsPerLoad)
const visiblePosts = computed(() => allPosts.value.slice(0, visibleCount.value))
const hasMore = computed(() => visibleCount.value < allPosts.value.length)

const loadMoreTrigger = ref<HTMLElement | null>(null)
let observer: IntersectionObserver | null = null

// Animation d'entrée pour les nouvelles cartes
let cardObserver: IntersectionObserver | null = null

function observeNewCards() {
  nextTick(() => {
    const cards = document.querySelectorAll('.post-card.card-hidden')
    cards.forEach((card) => {
      cardObserver?.observe(card)
    })
  })
}

onMounted(() => {
  // Observer pour le défilement continu
  nextTick(() => {
    if (!loadMoreTrigger.value) return
    observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && hasMore.value) {
          visibleCount.value += postsPerLoad
          // Observer les nouvelles cartes après chargement
          nextTick(() => observeNewCards())
        }
      },
      { rootMargin: '200px' }
    )
    observer.observe(loadMoreTrigger.value)
  })

  // Observer pour les animations d'entrée des cartes
  cardObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.remove('card-hidden')
          entry.target.classList.add('card-visible')
          cardObserver?.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.1 }
  )

  observeNewCards()
})

onUnmounted(() => {
  observer?.disconnect()
  cardObserver?.disconnect()
})

// Contenu dynamique avec fallbacks
const heroTitleLine1 = computed(() => blogContent.value?.hero?.title_line_1 || 'Histoires Inspirantes')
const heroTitleLine2 = computed(() => blogContent.value?.hero?.title_line_2 || 'Des Coulisses')
const recentInsightsTitle = computed(() => blogContent.value?.sections?.recent_insights_title || 'Articles les plus récents')
const allInsightsTitle = computed(() => blogContent.value?.sections?.all_insights_title || 'Explorer tous les articles')
</script>

<template>
  <!-- SVG Filters pour les bordures rugueuses -->
  <svg width="0" height="0" style="position:absolute;overflow:hidden;">
    <defs>
      <filter id="rough-border">
        <feTurbulence type="turbulence" baseFrequency="0.04" numOctaves="4" result="noise" seed="1"/>
        <feDisplacementMap in="SourceGraphic" in2="noise" scale="3" xChannelSelector="R" yChannelSelector="G"/>
      </filter>
    </defs>
  </svg>

  <div class="blog-page">
    <!-- Hero Section -->
    <section class="blog-hero">
      <div class="container">
        <h1 class="hero-title">
          <span class="title-black">{{ heroTitleLine1 }}<br>{{ heroTitleLine2 }}</span>
        </h1>
      </div>
    </section>

    <!-- Most Recent Insights -->
    <section class="recent-insights">
      <div class="container">
        <h2 class="section-title">{{ recentInsightsTitle }}</h2>

        <!-- Featured Post 1 - Image Left -->
        <article v-if="featuredPosts[0]" class="featured-post">
          <div class="post-image">
            <NuxtLink :to="`/blog/${featuredPosts[0].slug}`">
              <img :src="featuredPosts[0].featured_media" :alt="decodeHtml(featuredPosts[0].title.rendered)" />
            </NuxtLink>
          </div>
          <div class="post-content d-flex align-items-center flex-wrap flex-row">
            <h3 class="post-title">
              <NuxtLink :to="`/blog/${featuredPosts[0].slug}`">
                {{ decodeHtml(featuredPosts[0].title.rendered) }}
              </NuxtLink>
            </h3>
            <p class="post-excerpt">{{ cleanExcerpt(featuredPosts[0].excerpt.rendered, featuredPosts[0].content.rendered) }}</p>
<PillButton
              :color="getButton('blog_read_article').color"
              :variant="getButton('blog_read_article').variant"
              :to="`/blog/${featuredPosts[0].slug}`"
              :label="getButton('blog_read_article').label"
              :width="getButton('blog_read_article').width"
              bg-left="-8px"
              bg-right="-8px"
              bg-top="-6px"
              bg-bottom="-6px"
              bg-width="110%"
            />
                    </div>
        </article>

        <!-- Featured Post 2 - Image Right -->
        <!--<article v-if="featuredPosts[1]" class="featured-post reverse">-->
        <article v-if="featuredPosts[1]" class="featured-post second">
          <div class="post-image">
            <NuxtLink :to="`/blog/${featuredPosts[1].slug}`">
              <img :src="featuredPosts[1].featured_media" :alt="decodeHtml(featuredPosts[1].title.rendered)" />
            </NuxtLink>
          </div>
          <div class="post-content d-flex align-items-center flex-wrap flex-row">
            <h3 class="post-title">
              <NuxtLink :to="`/blog/${featuredPosts[1].slug}`">
                {{ decodeHtml(featuredPosts[1].title.rendered) }}
              </NuxtLink>
            </h3>
            <p class="post-excerpt">{{ cleanExcerpt(featuredPosts[1].excerpt.rendered, featuredPosts[1].content.rendered) }}</p>
          <!--   <NuxtLink :to="`/blog/${featuredPosts[1].slug}`" class="bg-transparent border-0 p-0 m-0">
              <NuxtImg :src="btnReadMore" alt="Lire la suite" width="247"/>
            </NuxtLink> -->
             <PillButton
              :color="getButton('blog_read_article').color"
              :variant="getButton('blog_read_article').variant"
              :to="`/blog/${featuredPosts[1].slug}`"
              :label="getButton('blog_read_article').label"
              :width="getButton('blog_read_article').width"
              bg-left="-8px"
              bg-right="-8px"
              bg-top="-6px"
              bg-bottom="-6px"
              bg-width="110%"
            />
          </div>
        </article>
      </div>
    </section>

    <!-- Explore All Insights -->
    <section class="all-insights">
      <div class="container">
        <h2 class="section-title">{{ allInsightsTitle }}</h2>

        <div class="posts-grid">
          <article
            v-for="post in visiblePosts"
            :key="post.id"
            class="post-card card-hidden"
          >
            <div class="card-image">
              <NuxtLink :to="`/blog/${post.slug}`">
                <img :src="post.featured_media" :alt="decodeHtml(post.title.rendered)" />
              </NuxtLink>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <NuxtLink :to="`/blog/${post.slug}`">
                  {{ decodeHtml(post.title.rendered) }}
                </NuxtLink>
              </h3>
            </div>
          </article>
        </div>

        <!-- Sentinelle pour le défilement continu -->
        <div v-if="hasMore" ref="loadMoreTrigger" class="load-more-trigger">
          <span class="loading-spinner"></span>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped lang="scss">
.blog-page {
  padding-top:8em;
  min-height: 100vh;
  background: #fff;
}

// Hero Section
.blog-hero {
      background: url(/images/bBlog.svg);
    background-repeat: no-repeat;
    background-size: cover;
    max-width: 1400px;
    max-height: 266px;
    margin: 24px auto 0;
    height: 100vh;
    display: flex;
    align-items: center;
    border-radius: 10px;
}

.hero-decoration.pink-blob {
  position: absolute;
  top: 0;
  left: 0;
  width: 300px;
  height: 100%;
  background: #FF6B9D;
  border-radius: 0 0 150px 0;
}

.hero-title {
  position: relative;
  z-index: 1;
  font-family: var(--font-heading, 'Recoleta', serif);
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 700;
  line-height: 1.15;
  margin: 0;
  padding-left: 2rem;
}

.title-white {
  display: block;
  color: #fff;
}

.title-highlight {
  display: block;
  color: #1a1a1a;
  position: relative;

  &::before {
    content: '';
    position: absolute;
    bottom: 0.1em;
    left: 0;
    width: 100%;
    height: 0.3em;
    background: #FFDD00;
    z-index: -1;
  }
}

.title-black {
  display: block;
  color: #1a1a1a;
}

// Section Title
.section-title {
  font-family: var(--font-heading, 'Recoleta', serif);
  font-size: 2rem;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 2.5rem;
}

// Recent Insights Section
.recent-insights {
  padding: 4rem 0;
  background: #fff;
}

.featured-post {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  &:nth-of-type(1){
    .post-content{
      background:#93cbff url(/images/bgFeatured1.svg);
      border-radius: 10px;
      position: relative;
      isolation: isolate;

      &::before {
        content: '';
        position: absolute;
        inset: -4px;
        background: #93cbff;
        border-radius: 14px;
        filter: url(#rough-border);
        z-index: -2;
      }

      &::after {
        content: '';
        position: absolute;
        inset: 0;
        background: #93cbff url(/images/bgFeatured1.svg);
        background-size: inherit;
        background-repeat: inherit;
        border-radius: 10px;
        filter: url(#rough-border);
        z-index: -1;
      }
    }
  }
  &:nth-of-type(2){
    .post-content{
      background: url(/images/bgFeatured.svg) #d7a8ff;
      border-radius: 10px;
      position: relative;
      isolation: isolate;

      &::before {
        content: '';
        position: absolute;
        inset: -4px;
        background: #d7a8ff;
        border-radius: 14px;
        filter: url(#rough-border);
        z-index: -2;
      }

      &::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url(/images/bgFeatured.svg) #d7a8ff;
        background-size: inherit;
        background-repeat: inherit;
        border-radius: 10px;
        filter: url(#rough-border);
        z-index: -1;
      }
    }
  }
  
  &:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  &.reverse {
    .post-image {
      order: 2;
    }
    .post-content {
      order: 1;
    }
  }
}

.post-image {
  border-radius: 12px;
  overflow: hidden;

  img {
    width: 100%;
    height: 390px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }

  &:hover img {
    transform: scale(1.03);
  }
}

.post-content {
  padding: 1rem 0;
}

.post-title {
  font-family: FONTSPRINGDEMO-RecoletaBold;
  font-size: 34px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: left;
  color: #000b0f;

  a {
    font-family: FONTSPRINGDEMO-RecoletaBold;
  font-size: 34px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: left;
  text-decoration:none;
  color: #000b0f;

    &:hover {
      color: #FF4D6D;
    }
  }
}

.post-excerpt {
   font-family: FONTSPRINGDEMO-RecoletaMedium;
  font-size: 18px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.56;
  letter-spacing: normal;
  text-align: left;
  color: #000;
}

.read-more-btn {
  display: inline-block;
  padding: 0.75rem 1.75rem;
  background: #fff;
  color: #1a1a1a;
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
  font-size: 0.875rem;
  font-weight: 600;
  text-decoration: none;
  border: 2px solid #1a1a1a;
  border-radius: 50px;
  transition: all 0.2s ease;

  &:hover {
    background: #1a1a1a;
    color: #fff;
  }
}

// Infinite scroll trigger
.load-more-trigger {
  display: flex;
  justify-content: center;
  padding: 3rem 0;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #eee;
  border-top-color: #FF4D6D;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

// All Insights Section
.all-insights {
  padding: 4rem 0;
  background: #fff;
}

.posts-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2.5rem;
}

// Rough border card style
.post-card {
  display: flex;
  flex-direction: column;
  position: relative;
  isolation: isolate;
  background: transparent;
  transition: transform 0.3s ease;

  // Contour noir rugueux (derrière tout le contenu)
  &::before {
    content: '';
    position: absolute;
    inset: -4px;
    background: #1a1a1a;
    border-radius: 20px;
    filter: url(#rough-border);
    z-index: -2;
  }

  // Fond blanc rugueux (par-dessus le contour, derrière le contenu)
  &::after {
    content: '';
    position: absolute;
    inset: 0;
    background: #fff;
    border-radius: 16px;
    filter: url(#rough-border);
    z-index: -1;
  }

  &:hover {
    transform: translateY(-4px);
  }
}

// Scroll entry animation
.post-card.card-hidden {
  opacity: 0;
  transform: translateY(40px);
}

.post-card.card-visible {
  animation: cardSlideIn 0.6s ease forwards;
}

@keyframes cardSlideIn {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

// Stagger animation for cards in same batch
.post-card.card-visible:nth-child(4n+2) { animation-delay: 0.1s; }
.post-card.card-visible:nth-child(4n+3) { animation-delay: 0.2s; }
.post-card.card-visible:nth-child(4n)   { animation-delay: 0.3s; }

.card-image {
  overflow: hidden;
  margin-bottom: 1.25rem;

  img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    border-radius:10px 10px 0 0;
  }

  &:hover img {
    transform: scale(1.03);
  }
}

.card-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding:1em;
}

.card-title {
   font-family: FONTSPRINGDEMO-RecoletaBold;
  font-size: 34px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: left;
  color: #000b0f;
  a {
   font-family: FONTSPRINGDEMO-RecoletaBold;
  font-size: 34px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: left;
  color: #000b0f;
  text-decoration:none;

    &:hover {
      color: #FF4D6D;
    }
  }
}

.card-excerpt {
  font-family: FONTSPRINGDEMO-RecoletaMedium;
  font-size: 18px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.56;
  letter-spacing: normal;
  text-align: left;
  margin:2em auto;
  color: #000;
  // Clamp to 3 lines
//  display: -webkit-box;
  //-webkit-line-clamp: 3;
 // -webkit-box-orient: vertical;
  overflow: hidden;
}

// Responsive
@media (max-width: 992px) {
  .featured-post {
    grid-template-columns: 1fr;
    gap: 1.5rem;

    &.reverse {
      .post-image {
        order: 1;
      }
      .post-content {
        order: 2;
      }
    }
  }

  .post-image img {
    height: 250px;
  }
}

@media (max-width: 768px) {
  .blog-hero {
    padding: 3rem 0;
  }

  .hero-decoration.pink-blob {
    width: 150px;
  }

  .hero-title {
    font-size: 2rem;
    padding-left: 1rem;
  }

  .posts-grid {
    grid-template-columns: 1fr;
  }

  .section-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .hero-decoration.pink-blob {
    width: 100px;
  }

  .post-image img,
  .card-image img {
    height: 200px;
  }
}
@media (min-width:1024px){
  
  .post-content{
       width: 100%;
    height: 100%;
    background-size: contain !important;
    background-repeat: no-repeat !important;
    padding: 2em;
  }
}

@media (max-width:1024px){
  
  .post-content{
       width: 100%;
    height: 100%;
    background-size: cover !important;
    background-repeat: no-repeat !important;
    padding: 2em;
  }
}
</style>
