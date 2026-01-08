<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LucideArrowRight } from 'lucide-vue-next'

const { getContentByPath } = usePageContent()
const content = ref<any>(null)

onMounted(async () => {
  content.value = await getContentByPath('blog.index')
})
</script>

<template>
  <div v-if="content" class="bg-brand-gray min-vh-100 pb-5">
    <section class="bg-white py-5 border-bottom border-2 border-dark">
       <div class="container text-center">
          <SectionHeader :title="content.section_title" :subtitle="content.section_subtitle" centered />
       </div>
    </section>

    <div class="container mt-5">
       <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <div v-for="(article, index) in content.articles" :key="index" class="col">
            <article class="bg-white rounded-4 overflow-hidden border border-2 border-dark article-card position-relative h-100">
               <div class="article-image overflow-hidden position-relative border-bottom border-2 border-dark">
                  <NuxtImg :src="article.image" class="w-100 h-100 object-fit-cover article-img" />
                  <span class="position-absolute top-0 end-0 mt-3 me-3 bg-brand-yellow text-dark small fw-bold px-3 py-1 rounded-pill border border-dark">
                     {{ article.tag }}
                  </span>
               </div>

               <div class="p-4">
                  <p class="text-muted small fw-bold mb-2 text-uppercase letter-spacing-wide">{{ article.date }}</p>
                  <h3 class="fs-4 font-heading fw-bold mb-2 lh-sm article-title">
                     <a href="#" class="text-decoration-none text-dark stretched-link">
                        {{ article.title }}
                     </a>
                  </h3>
                  <p class="text-muted mb-4 line-clamp-3">
                     {{ article.excerpt }}
                  </p>

                  <div class="d-flex align-items-center text-brand-pink fw-bold read-more">
                     {{ content.read_article_link }} <LucideArrowRight class="ms-2" style="width: 1.25rem; height: 1.25rem;" />
                  </div>
               </div>
            </article>
          </div>
       </div>

       <div class="mt-5 text-center">
          <BaseButton variant="outline" size="lg">{{ content.load_more_button }}</BaseButton>
       </div>
    </div>
  </div>
</template>

<style scoped>
.article-image {
  height: 16rem;
}

.article-img {
  transition: transform 0.7s ease;
}

.article-card:hover .article-img {
  transform: scale(1.1);
}

.article-card {
  transition: all 0.3s ease;
}

.article-card:hover {
  transform: translateY(-0.5rem);
  box-shadow: 4px 4px 0 rgba(0, 0, 0, 1);
}

.article-title a {
  transition: color 0.3s ease;
}

.article-card:hover .article-title a {
  color: var(--brand-pink, #FF4D6D) !important;
}

.read-more {
  transition: transform 0.3s ease;
}

.article-card:hover .read-more {
  transform: translateX(0.5rem);
}

.letter-spacing-wide {
  letter-spacing: 0.05em;
}
</style>
