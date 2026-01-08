<script setup lang="ts">
import { LucideMapPin, LucideX, LucideHeart, LucideShare2, LucideChevronRight } from 'lucide-vue-next'

const { getJobDetailContent } = usePageContent()
const content = ref<any>(null)
const job = ref<any>(null)

onMounted(async () => {
  content.value = await getJobDetailContent()
  // In a real app, this would come from an API based on route params
  job.value = content.value?.sample_job || null
})
</script>

<template>
  <div v-if="content && job" class="min-vh-100 bg-white">
    <!-- Header -->
    <header class="border-bottom py-3">
      <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 text-brand-pink">
          <LucideMapPin style="width: 1.25rem; height: 1.25rem;" />
          <span class="fw-medium">{{ job.location }}</span>
        </div>

        <button class="close-btn-small rounded-circle bg-brand-yellow d-flex align-items-center justify-content-center border border-2 border-dark">
          <LucideX style="width: 1.25rem; height: 1.25rem;" />
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container py-4">
      <!-- Title & Tags -->
      <div class="mb-4">
        <h1 class="font-heading display-5 fw-bold mb-4">{{ job.title }}</h1>

        <div class="d-flex flex-wrap gap-2">
          <span class="tag-blue">{{ content.quick_facts.department_label }} · {{ job.department }}</span>
          <span class="tag-lime d-flex align-items-center gap-1">
            <span>🌿</span> {{ job.type }}
          </span>
          <span class="tag-yellow d-flex align-items-center gap-1">
            <span>💰</span> {{ job.salary }}
          </span>
        </div>
      </div>

      <!-- Description -->
      <p class="text-muted fs-5 mb-4 font-body lh-lg" style="max-width: 56rem;">
        {{ job.description }}
      </p>

      <!-- CTA Banner -->
      <div class="bg-brand-blue border-organic p-4 mb-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div>
          <h3 class="font-heading fs-5 fw-bold mb-1">{{ content.cta_banner.title }}</h3>
          <p class="text-dark font-body mb-0">{{ content.cta_banner.subtitle }}</p>
        </div>
        <button class="btn-primary text-nowrap">
          {{ content.cta_banner.apply_button }}
        </button>
      </div>

      <!-- Life at Location -->
      <section class="mb-5">
        <h2 class="font-heading fs-4 fw-bold mb-4">{{ content.life_at_location.title_template.replace('{location}', job.location.split(',')[0]) }}</h2>
        <div class="row row-cols-2 row-cols-md-4 g-3">
          <div v-for="(img, i) in job.images" :key="i" class="col">
            <div class="border-organic overflow-hidden image-card">
              <NuxtImg :src="img" class="w-100 h-100 object-fit-cover gallery-image" />
            </div>
          </div>
        </div>
      </section>

      <!-- Job Details Grid -->
      <section class="mb-5">
        <h2 class="font-heading fs-4 fw-bold mb-4">{{ content.job_description.section_title }}</h2>

        <div class="row g-4">
          <!-- What You'll Do -->
          <div class="col-md-6">
            <div class="card-organic h-100">
              <h3 class="font-heading fs-5 fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="check-icon rounded-circle bg-brand-pink d-flex align-items-center justify-content-center text-white">✓</span>
                {{ content.job_description.what_you_do_title }}
              </h3>
              <p class="text-muted small mb-3 font-body">{{ content.job_description.what_you_do_intro }}</p>
              <ul class="list-unstyled d-flex flex-column gap-2">
                <li v-for="(item, i) in job.what_you_do" :key="i" class="d-flex align-items-start gap-2 small font-body">
                  <LucideChevronRight class="text-brand-pink flex-shrink-0 mt-1" style="width: 1rem; height: 1rem;" />
                  {{ item }}
                </li>
              </ul>
            </div>
          </div>

          <!-- Requirements -->
          <div class="col-md-6">
            <div class="card-organic bg-brand-lime h-100">
              <h3 class="font-heading fs-5 fw-bold mb-3">{{ content.job_description.requirements_title }}</h3>
              <p class="text-dark small mb-3 font-body">{{ content.job_description.requirements_intro }}</p>
              <ul class="list-unstyled d-flex flex-column gap-2">
                <li v-for="(item, i) in job.requirements" :key="i" class="d-flex align-items-start gap-2 small font-body">
                  <LucideChevronRight class="text-brand-dark flex-shrink-0 mt-1" style="width: 1rem; height: 1rem;" />
                  {{ item }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Bottom Section -->
      <section class="row g-4 mb-5">
        <!-- Why Join Us -->
        <div class="col-md-4">
          <div class="bg-brand-pink text-white p-4 border-organic h-100">
            <h3 class="font-heading fs-5 fw-bold mb-3 d-flex align-items-center gap-2">
              <LucideHeart style="width: 1.25rem; height: 1.25rem;" /> {{ content.why_join_us.title }}
            </h3>
            <ul class="list-unstyled d-flex flex-column gap-2">
              <li v-for="(item, i) in job.why_join_us" :key="i" class="d-flex align-items-start gap-2 small font-body">
                <LucideChevronRight class="flex-shrink-0 mt-1" style="width: 1rem; height: 1rem;" />
                {{ item }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Quick Facts -->
        <div class="col-md-4">
          <div class="card-organic h-100">
            <h3 class="font-heading fs-5 fw-bold mb-3">{{ content.quick_facts.title }}</h3>
            <div class="d-flex flex-column gap-3 small">
              <div>
                <p class="text-muted text-uppercase small mb-1">{{ content.quick_facts.location_label }}</p>
                <p class="fw-bold mb-0">{{ job.location }}</p>
              </div>
              <div>
                <p class="text-muted text-uppercase small mb-1">{{ content.quick_facts.department_label }}</p>
                <p class="fw-bold mb-0">{{ job.department }}</p>
              </div>
              <div>
                <p class="text-muted text-uppercase small mb-1">{{ content.quick_facts.employment_type_label }}</p>
                <p class="fw-bold mb-0">{{ job.type }}</p>
              </div>
              <div>
                <p class="text-muted text-uppercase small mb-1">{{ content.quick_facts.available_positions_label }}</p>
                <p class="fw-bold text-brand-pink mb-0">{{ job.slots }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Share -->
        <div class="col-md-4">
          <div class="card-organic h-100">
            <h3 class="font-heading fs-5 fw-bold mb-3">{{ content.share_section.title }}</h3>
            <p class="text-muted small mb-3 font-body">{{ content.share_section.subtitle }}</p>
            <button class="btn-secondary d-flex align-items-center gap-2 w-100 justify-content-center">
              <LucideShare2 style="width: 1rem; height: 1rem;" />
              {{ content.share_section.share_button }}
            </button>
          </div>
        </div>
      </section>

      <!-- Final CTA -->
      <section class="bg-brand-yellow border-organic p-4 p-md-5 text-center">
        <h2 class="font-heading display-6 fw-bold mb-3">{{ content.final_cta.title }}</h2>
        <p class="text-dark mx-auto mb-4 font-body" style="max-width: 42rem;">
          {{ content.final_cta.description }}
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
          <button class="btn-lime">{{ content.final_cta.apply_button }}</button>
          <NuxtLink to="/careers" class="btn-secondary">{{ content.final_cta.back_button }}</NuxtLink>
        </div>
      </section>
    </main>
  </div>
</template>

<style scoped>
.close-btn-small {
  width: 2.5rem;
  height: 2.5rem;
  transition: transform 0.3s ease;
}

.close-btn-small:hover {
  transform: scale(1.1);
}

.image-card {
  aspect-ratio: 16/9;
}

.gallery-image {
  transition: transform 0.5s ease;
}

.gallery-image:hover {
  transform: scale(1.1);
}

.check-icon {
  width: 1.5rem;
  height: 1.5rem;
  font-size: 0.75rem;
}
</style>
