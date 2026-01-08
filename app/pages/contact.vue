<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LucideX } from 'lucide-vue-next'
import type { ContactContent } from '~/composables/usePageContent'

const { getContactContent } = usePageContent()
const content = ref<ContactContent | null>(null)

const form = ref({
  name: '',
  email: '',
  eventType: '',
  location: '',
  date: '',
  guests: '',
  message: ''
})

onMounted(async () => {
  content.value = await getContactContent()
})

const submitForm = () => {
  console.log('Form submitted:', form.value)
  // Handle form submission
}
</script>

<template>
  <div v-if="content" class="min-vh-100 bg-brand-lime">
    <!-- Close Button (for modal context) -->
    <button class="position-fixed top-0 end-0 mt-4 me-4 close-btn rounded-circle bg-brand-yellow d-flex align-items-center justify-content-center border border-2 border-dark shadow-organic">
      <LucideX style="width: 1.5rem; height: 1.5rem;" />
    </button>

    <!-- Decorative Arrow -->
    <div class="position-absolute decorative-arrow d-none d-lg-block">
      <svg width="80" height="80" viewBox="0 0 80 80" fill="none" class="animate-wiggle">
        <path d="M20 10 C 30 30, 50 40, 70 60" stroke="#1A1A1A" stroke-width="2" fill="none" stroke-linecap="round"/>
        <path d="M60 55 L 70 60 L 65 50" stroke="#1A1A1A" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <div class="container py-5 py-lg-5">
      <!-- Hero Section -->
      <div class="d-flex flex-column flex-lg-row align-items-start justify-content-between gap-5 mb-5">
        <!-- Left: Heading -->
        <div class="col-lg-6">
          <h1 class="font-heading display-2 fw-bold lh-1 text-brand-dark">
            {{ content.hero_section.title.line_1 }} <span class="position-relative d-inline-block">
              {{ content.hero_section.title.line_1_highlight }}
              <span class="position-absolute bottom-0 start-0 w-100 bg-brand-blue highlight-bar opacity-75"></span>
            </span> {{ content.hero_section.title.line_2 }}<br/>
            {{ content.hero_section.title.line_3 }} <span class="fst-italic">{{ content.hero_section.title.line_3_highlight }}</span>
          </h1>

          <p class="mt-4 fs-5 text-brand-dark opacity-75 font-body" style="max-width: 28rem;">
            {{ content.hero_section.description }}
          </p>
        </div>

        <!-- Right: Image in Blob Shape -->
        <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
          <div class="position-relative blob-container">
            <!-- The actual blob shaped image -->
            <div class="position-absolute top-0 start-0 end-0 bottom-0 blob-mask overflow-hidden border border-4 border-dark image-group">
              <NuxtImg
                :src="content.hero_section.image.src"
                class="w-100 h-100 object-fit-cover blob-image"
                :alt="content.hero_section.image.alt"
              />
            </div>

            <!-- Floating accent circles -->
            <div class="position-absolute floating-circle-1 bg-brand-yellow rounded-circle animate-float"></div>
            <div class="position-absolute floating-circle-2 bg-brand-blue rounded-circle animate-float" style="animation-delay: 1.5s"></div>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="dashed-organic p-4 p-lg-5 form-container">
        <form @submit.prevent="submitForm" class="d-flex flex-column gap-4">
          <!-- Row 1: Name & Email -->
          <div class="row g-4">
            <div class="col-md-6">
              <input
                v-model="form.name"
                type="text"
                :placeholder="content.form.name_placeholder"
                class="input-organic"
              />
            </div>
            <div class="col-md-6">
              <input
                v-model="form.email"
                type="email"
                :placeholder="content.form.email_placeholder"
                class="input-organic"
              />
            </div>
          </div>

          <!-- Row 2: Event Type & Location -->
          <div class="row g-4">
            <div class="col-md-6">
              <input
                v-model="form.eventType"
                type="text"
                :placeholder="content.form.event_type_placeholder"
                class="input-organic"
              />
            </div>
            <div class="col-md-6">
              <input
                v-model="form.location"
                type="text"
                :placeholder="content.form.location_placeholder"
                class="input-organic"
              />
            </div>
          </div>

          <!-- Row 3: Date & Guests -->
          <div class="row g-4">
            <div class="col-md-6">
              <input
                v-model="form.date"
                type="text"
                :placeholder="content.form.date_placeholder"
                class="input-organic"
              />
            </div>
            <div class="col-md-6">
              <input
                v-model="form.guests"
                type="text"
                :placeholder="content.form.guests_placeholder"
                class="input-organic"
              />
            </div>
          </div>

          <!-- Row 4: Message -->
          <div>
            <textarea
              v-model="form.message"
              :placeholder="content.form.message_placeholder"
              rows="5"
              class="textarea-organic"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <div class="pt-3">
            <button type="submit" class="btn-primary fs-5 px-5 py-3">
              {{ content.form.submit_button }}
            </button>
          </div>
        </form>
      </div>
    </div>
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

.decorative-arrow {
  top: 5rem;
  left: 2rem;
}

.highlight-bar {
  height: 1rem;
  z-index: -1;
  transform: rotate(-1deg);
}

.blob-container {
  width: 18rem;
  height: 18rem;
}

@media (min-width: 768px) {
  .blob-container {
    width: 20rem;
    height: 20rem;
  }
}

@media (min-width: 992px) {
  .blob-container {
    width: 450px;
    height: 450px;
  }
}

.blob-image {
  transition: transform 0.7s ease;
}

.image-group:hover .blob-image {
  transform: scale(1.1);
}

.floating-circle-1 {
  width: 8rem;
  height: 8rem;
  top: -2.5rem;
  right: -2.5rem;
  z-index: -1;
}

.floating-circle-2 {
  width: 6rem;
  height: 6rem;
  bottom: -2.5rem;
  left: -2.5rem;
  z-index: -1;
}

.form-container {
  background-color: rgba(200, 245, 96, 0.3);
}
</style>
