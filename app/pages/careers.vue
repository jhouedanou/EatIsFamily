<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { LucideSearch, LucideMapPin, LucideX, LucideChevronDown } from 'lucide-vue-next'
import type { CareersContent } from '~/composables/usePageContent'

const { getCareersContent } = usePageContent()
const content = ref<CareersContent | null>(null)

const searchQuery = ref('')
const selectedJobType = ref('')
const selectedVenue = ref('')
const showFilters = ref(false)

onMounted(async () => {
  content.value = await getCareersContent()
  if (content.value) {
    selectedJobType.value = content.value.search_section.job_types[0]
    selectedVenue.value = content.value.venues[0]?.id || ''
  }
})

const currentVenue = computed(() => {
  if (!content.value) return null
  return content.value.venues.find(v => v.id === selectedVenue.value) || content.value.venues[0]
})

const filteredJobs = computed(() => {
  if (!content.value) return []
  return content.value.jobs.filter(job => {
    const matchesSearch = job.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          job.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesType = selectedJobType.value === content.value!.search_section.job_types[0] || job.type === selectedJobType.value
    const matchesVenue = job.venue_id === selectedVenue.value
    return matchesSearch && matchesType && matchesVenue
  })
})

const selectVenue = (venueId: string) => {
  selectedVenue.value = venueId
}
</script>

<template>
  <div v-if="content" class="min-vh-100 bg-brand-gray">
    <!-- Hero Section with Map -->
    <section class="position-relative hero-map-section">
      <!-- Interactive Map Background -->
      <div class="position-absolute top-0 start-0 end-0 bottom-0">
        <ClientOnly>
          <VenueMap
            :venues="content.venues.map(v => ({
              id: v.id,
              name: v.name,
              location: v.location,
              lat: v.lat,
              lng: v.lng,
              openPositions: v.open_positions
            }))"
            :selected-venue="selectedVenue"
            @select-venue="selectVenue"
          />
          <template #fallback>
            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
              <span class="text-muted">{{ content.map_loading }}</span>
            </div>
          </template>
        </ClientOnly>
      </div>

      <!-- Gradient Overlay -->
      <div class="position-absolute top-0 start-0 end-0 bottom-0 gradient-overlay pe-none"></div>

      <!-- Close Button -->
      <button class="position-absolute top-0 end-0 mt-4 me-4 close-btn rounded-circle bg-brand-yellow d-flex align-items-center justify-content-center border border-2 border-dark shadow-organic">
        <LucideX style="width: 1.25rem; height: 1.25rem;" />
      </button>

      <!-- Venue Info Card -->
      <div v-if="currentVenue" class="position-absolute bottom-0 start-0 mb-4 ms-3 me-3 venue-info-card">
        <div class="bg-white border-organic p-4 shadow-organic">
          <span class="tag-lime small mb-3 d-inline-block">{{ content.hero_section.tag }}</span>

          <h1 class="font-heading fs-2 fw-bold lh-sm mb-3">
            {{ content.hero_section.title_template.replace('{venue_name}', currentVenue.name) }}
          </h1>

          <div class="d-flex align-items-center gap-3 text-muted">
            <div class="d-flex align-items-center gap-2">
              <LucideMapPin class="text-brand-pink" style="width: 1rem; height: 1rem;" />
              <span class="small">{{ currentVenue.location }}</span>
            </div>
            <span class="rounded-circle bg-brand-lime" style="width: 0.375rem; height: 0.375rem;"></span>
            <span class="small fw-bold">{{ currentVenue.open_positions }} {{ content.hero_section.open_positions_suffix }}</span>
          </div>
        </div>
      </div>

      <!-- Venue Pills -->
      <div class="position-absolute top-0 start-0 mt-4 ms-3 d-flex gap-2 flex-wrap venue-pills">
        <button
          v-for="venue in content.venues"
          :key="venue.id"
          @click="selectVenue(venue.id)"
          :class="[
            'px-3 py-2 small fw-bold border border-2 border-dark venue-pill',
            selectedVenue === venue.id
              ? 'bg-brand-pink text-white shadow-organic-sm'
              : 'bg-white'
          ]"
        >
          {{ venue.name }}
        </button>
      </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="container search-bar-section position-relative">
      <div class="bg-brand-dark border-organic p-3 d-flex flex-column flex-md-row gap-3 shadow-organic">
        <!-- Search Input -->
        <div class="flex-grow-1 position-relative">
          <div class="d-flex align-items-center gap-3 px-3">
            <LucideSearch class="text-white opacity-75" style="width: 1.25rem; height: 1.25rem;" />
            <input
              v-model="searchQuery"
              type="text"
              :placeholder="content.search_section.search_placeholder"
              class="bg-transparent text-white border-0 flex-grow-1 py-2 font-body search-input"
            />
          </div>
        </div>

        <!-- Job Type Dropdown -->
        <div class="position-relative">
          <button
            @click="showFilters = !showFilters"
            class="bg-brand-dark border-start border-white border-opacity-25 px-4 py-2 d-flex align-items-center gap-3 text-white w-100 w-md-auto justify-content-between dropdown-btn"
          >
            <span>{{ selectedJobType }}</span>
            <LucideChevronDown style="width: 1rem; height: 1rem;" :class="{ 'rotate-180': showFilters }" />
          </button>

          <!-- Dropdown Menu -->
          <Transition
            enter-active-class="transition-fade-in"
            leave-active-class="transition-fade-out"
          >
            <div v-if="showFilters" class="position-absolute top-100 end-0 mt-2 bg-white border-organic shadow-organic-lg dropdown-menu-custom">
              <button
                v-for="type in content.search_section.job_types"
                :key="type"
                @click="selectedJobType = type; showFilters = false"
                :class="[
                  'w-100 text-start px-3 py-2 border-0 fw-medium dropdown-item-custom',
                  selectedJobType === type ? 'active' : ''
                ]"
              >
                {{ type }}
              </button>
            </div>
          </Transition>
        </div>
      </div>
    </section>

    <!-- Job Grid -->
    <section class="container py-5">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-heading fs-4 fw-bold">
          {{ filteredJobs.length }} {{ filteredJobs.length === 1 ? content.job_listing.positions_available_singular : content.job_listing.positions_available_plural }} {{ content.job_listing.positions_available_suffix }}
        </h2>
      </div>

      <div class="row row-cols-1 row-cols-md-2 g-4">
        <div
          v-for="job in filteredJobs"
          :key="job.id"
          class="col"
        >
          <div class="bg-white border-organic p-4 h-100 job-card">
            <div class="d-flex flex-column h-100 justify-content-between">
              <!-- Header -->
              <div>
                <h3 class="font-heading fw-bold fs-5 lh-sm mb-1">{{ job.title }}</h3>
                <p class="small text-muted fw-medium mb-3">{{ content.job_listing.posted_prefix }} {{ job.posted_time }}</p>

                <!-- Tags Row -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="tag-blue">{{ content.job_listing.department_prefix }} · {{ job.department }}</span>
                  <span class="tag-lime d-flex align-items-center gap-1">
                    <span class="small">🌿</span> {{ job.type }}
                  </span>
                  <span class="tag-yellow d-flex align-items-center gap-1">
                    <span class="small">💰</span> {{ job.salary }}
                  </span>
                </div>

                <!-- Description -->
                <p class="small text-muted mb-4 line-clamp-3 font-body lh-lg">
                  {{ job.description }}
                </p>
              </div>

              <!-- Buttons -->
              <div class="d-flex gap-3 mt-auto">
                <NuxtLink :to="`/jobs/${job.id}`" class="btn-primary flex-grow-1 text-center small">
                  {{ content.job_listing.apply_button }}
                </NuxtLink>
                <NuxtLink :to="`/jobs/${job.id}`" class="btn-secondary flex-grow-1 text-center small">
                  {{ content.job_listing.view_details_button }}
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- No Results -->
      <div v-if="filteredJobs.length === 0" class="text-center py-5 bg-white border-organic">
        <p class="fs-5 text-muted mb-2">{{ content.no_results.title }}</p>
        <p class="text-secondary mb-3">{{ content.no_results.description }}</p>
        <button @click="searchQuery = ''; selectedJobType = content.search_section.job_types[0]" class="text-brand-pink fw-bold btn btn-link text-decoration-none">
          {{ content.no_results.clear_filters_button }}
        </button>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-brand-dark py-5 text-center">
      <div class="container">
        <h2 class="font-heading display-5 fw-bold text-white mb-4">
          {{ content.cta_section.title }}
        </h2>
        <p class="text-secondary mx-auto mb-4 font-body" style="max-width: 42rem;">
          {{ content.cta_section.description }}
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
          <button class="btn-lime fs-5 px-5 py-3">
            {{ content.cta_section.explore_venues_button }}
          </button>
          <button class="btn-secondary fs-5 px-5 py-3">
            {{ content.cta_section.general_application_button }}
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.hero-map-section {
  height: 500px;
}

@media (min-width: 768px) {
  .hero-map-section {
    height: 600px;
  }
}

.gradient-overlay {
  background: linear-gradient(to bottom, transparent, transparent 50%, var(--brand-gray, #F5F5F0));
}

.close-btn {
  width: 3rem;
  height: 3rem;
  z-index: 20;
  transition: transform 0.3s ease;
}

.close-btn:hover {
  transform: scale(1.1);
}

.venue-info-card {
  z-index: 10;
}

@media (min-width: 768px) {
  .venue-info-card {
    max-width: 28rem;
    margin-right: auto;
  }
}

.venue-pills {
  max-width: 60%;
  z-index: 20;
}

.venue-pill {
  border-radius: 50px 10px 45px 10px / 10px 45px 10px 50px;
  transition: all 0.3s ease;
}

.venue-pill:hover {
  background-color: var(--brand-lime, #C8F560);
}

.search-bar-section {
  margin-top: -1.5rem;
  z-index: 20;
}

.search-input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

.search-input:focus {
  outline: none;
}

.dropdown-btn {
  transition: background-color 0.3s ease;
}

.dropdown-btn:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

.rotate-180 {
  transform: rotate(180deg);
}

.dropdown-menu-custom {
  width: 12rem;
  z-index: 30;
}

.dropdown-item-custom {
  background: transparent;
  transition: background-color 0.3s ease;
}

.dropdown-item-custom:hover {
  background-color: rgba(200, 245, 96, 0.3);
}

.dropdown-item-custom.active {
  background-color: rgba(200, 245, 96, 0.5);
}

.job-card {
  transition: all 0.3s ease;
}

.job-card:hover {
  box-shadow: 4px 4px 0 rgba(0, 0, 0, 1);
}

.transition-fade-in {
  animation: fadeIn 0.2s ease-out;
}

.transition-fade-out {
  animation: fadeOut 0.15s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
    transform: scale(1);
  }
  to {
    opacity: 0;
    transform: scale(0.95);
  }
}
</style>
