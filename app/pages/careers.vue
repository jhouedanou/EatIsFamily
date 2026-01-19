<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { LucideSearch, LucideMapPin, LucideX, LucideChevronDown, LucideChevronLeft, LucideChevronRight } from 'lucide-vue-next'
import type { CareersContent } from '~/composables/usePageContent'
import type { Job } from '~/composables/useJobs'

const { getCareersContent } = usePageContent()
const { getJobs } = useJobs()
const content = ref<CareersContent | null>(null)
const allJobs = ref<Job[]>([])

const searchQuery = ref('')
const selectedJobType = ref('')
const selectedVenue = ref('')
const showJobTypeDropdown = ref(false)
const showVenueDropdown = ref(false)

// Pagination
const currentPage = ref(1)
const itemsPerPage = 6

onMounted(async () => {
  content.value = await getCareersContent()
  const fetchedJobs = await getJobs()
  if (fetchedJobs) {
    allJobs.value = fetchedJobs
  }
  if (content.value) {
    selectedJobType.value = content.value.search_section.job_types[0]
    // Ne pas pré-sélectionner de venue - afficher tous les jobs par défaut
    selectedVenue.value = ''
  }
})

// Extraire toutes les venues uniques des jobs
const venueOptions = computed(() => {
  const locations = new Set<string>()
  allJobs.value.forEach(job => {
    if (job.location) {
      locations.add(job.location)
    }
  })
  return ['All locations', ...Array.from(locations)]
})

const currentVenue = computed(() => {
  if (!content.value || !selectedVenue.value) return null
  return content.value.venues.find(v => v.id === selectedVenue.value) || null
})

// Helper pour obtenir le titre du job
const getJobTitle = (job: Job) => {
  return typeof job.title === 'string' ? job.title : job.title?.rendered || ''
}

// Helper pour obtenir l'extrait du job
const getJobExcerpt = (job: Job) => {
  return typeof job.excerpt === 'string' ? job.excerpt : job.excerpt?.rendered || ''
}

// Helper pour formater la date relative
const getPostedTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffMs = now.getTime() - date.getTime()
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
  const diffHours = Math.floor(diffMs / (1000 * 60 * 60))
  
  if (diffHours < 1) return 'Just now'
  if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`
  if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

const filteredJobs = computed(() => {
  if (!content.value) return []
  return allJobs.value.filter(job => {
    const title = getJobTitle(job)
    const excerpt = getJobExcerpt(job)

    const matchesSearch = !searchQuery.value ||
                          title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          excerpt.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          job.location.toLowerCase().includes(searchQuery.value.toLowerCase())

    // Normaliser les types de job pour la comparaison
    const normalizedJobType = job.job_type.toLowerCase().replace('-', ' ')
    const normalizedSelectedType = selectedJobType.value.toLowerCase().replace('-', ' ')
    
    const matchesType = selectedJobType.value === content.value!.search_section.job_types[0] ||
                        normalizedJobType.includes(normalizedSelectedType)

    // Filtre par venue/location depuis le dropdown
    const matchesVenueFilter = selectedVenue.value === '' || 
                               selectedVenue.value === 'All locations' ||
                               job.location.toLowerCase().includes(selectedVenue.value.toLowerCase())

    return matchesSearch && matchesType && matchesVenueFilter
  })
})

// Pagination computed
const totalPages = computed(() => Math.ceil(filteredJobs.value.length / itemsPerPage))

const paginatedJobs = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredJobs.value.slice(start, end)
})

// Reset to page 1 when filters change
watch([searchQuery, selectedJobType, selectedVenue], () => {
  currentPage.value = 1
})

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const selectVenue = (venueId: string) => {
  // Toggle: si on clique sur la même venue, désélectionner
  selectedVenue.value = selectedVenue.value === venueId ? '' : venueId
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

        <!-- Venue Dropdown -->
        <div class="position-relative">
          <button
            @click="showVenueDropdown = !showVenueDropdown; showJobTypeDropdown = false"
            class="bg-brand-dark border-start border-white border-opacity-25 px-4 py-2 d-flex align-items-center gap-3 text-white w-100 w-md-auto justify-content-between dropdown-btn"
          >
            <LucideMapPin style="width: 1rem; height: 1rem;" class="opacity-75" />
            <span>{{ selectedVenue || 'All locations' }}</span>
            <LucideChevronDown style="width: 1rem; height: 1rem;" :class="{ 'rotate-180': showVenueDropdown }" />
          </button>

          <!-- Venue Dropdown Menu -->
          <Transition
            enter-active-class="transition-fade-in"
            leave-active-class="transition-fade-out"
          >
            <div v-if="showVenueDropdown" class="position-absolute top-100 end-0 mt-2 bg-white border-organic shadow-organic-lg dropdown-menu-custom dropdown-menu-venue">
              <button
                v-for="venue in venueOptions"
                :key="venue"
                @click="selectedVenue = venue === 'All locations' ? '' : venue; showVenueDropdown = false"
                :class="[
                  'w-100 text-start px-3 py-2 border-0 fw-medium dropdown-item-custom',
                  (selectedVenue === venue || (venue === 'All locations' && !selectedVenue)) ? 'active' : ''
                ]"
              >
                {{ venue }}
              </button>
            </div>
          </Transition>
        </div>

        <!-- Job Type Dropdown -->
        <div class="position-relative">
          <button
            @click="showJobTypeDropdown = !showJobTypeDropdown; showVenueDropdown = false"
            class="bg-brand-dark border-start border-white border-opacity-25 px-4 py-2 d-flex align-items-center gap-3 text-white w-100 w-md-auto justify-content-between dropdown-btn"
          >
            <span>{{ selectedJobType }}</span>
            <LucideChevronDown style="width: 1rem; height: 1rem;" :class="{ 'rotate-180': showJobTypeDropdown }" />
          </button>

          <!-- Dropdown Menu -->
          <Transition
            enter-active-class="transition-fade-in"
            leave-active-class="transition-fade-out"
          >
            <div v-if="showJobTypeDropdown" class="position-absolute top-100 end-0 mt-2 bg-white border-organic shadow-organic-lg dropdown-menu-custom">
              <button
                v-for="type in content.search_section.job_types"
                :key="type"
                @click="selectedJobType = type; showJobTypeDropdown = false"
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
        <p v-if="totalPages > 1" class="text-muted small mb-0">
          Page {{ currentPage }} of {{ totalPages }}
        </p>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <div
          v-for="job in paginatedJobs"
          :key="job.id"
          class="col"
        >
          <div class="bg-white border-organic p-4 h-100 job-card">
            <div class="d-flex flex-column h-100 justify-content-between">
              <!-- Header -->
              <div>
                <h3 class="font-heading fw-bold fs-5 lh-sm mb-1">{{ getJobTitle(job) }}</h3>
                <p class="small text-muted fw-medium mb-3">{{ content.job_listing.posted_prefix }} {{ getPostedTime(job.date) }}</p>

                <!-- Tags Row -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="tag-blue">🍳 {{ job.department }}</span>
                  <span class="tag-outline d-flex align-items-center gap-1">
                    <LucideMapPin style="width: 0.75rem; height: 0.75rem;" /> {{ job.location }}
                  </span>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="tag-lime d-flex align-items-center gap-1">
                    {{ job.job_type }}
                  </span>
                  <span class="tag-yellow d-flex align-items-center gap-1">
                    <span class="small">💰</span> {{ job.salary }}
                  </span>
                </div>

                <!-- Description -->
                <p class="small text-muted mb-4 line-clamp-3 font-body lh-lg">
                  {{ getJobExcerpt(job) }}
                </p>
              </div>

              <!-- Buttons -->
              <div class="d-flex gap-3 mt-auto">
                <NuxtLink :to="`/jobs/${job.slug}`" class="btn-primary flex-grow-1 text-center small">
                  {{ content.job_listing.apply_button }}
                </NuxtLink>
                <NuxtLink :to="`/jobs/${job.slug}`" class="btn-secondary flex-grow-1 text-center small">
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
        <button @click="searchQuery = ''; selectedJobType = content.search_section.job_types[0]; selectedVenue = ''" class="text-brand-pink fw-bold btn btn-link text-decoration-none">
          {{ content.no_results.clear_filters_button }}
        </button>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="d-flex justify-content-center align-items-center gap-2 mt-5">
        <button 
          @click="goToPage(currentPage - 1)" 
          :disabled="currentPage === 1"
          class="pagination-btn"
          :class="{ 'disabled': currentPage === 1 }"
        >
          <LucideChevronLeft style="width: 1.25rem; height: 1.25rem;" />
        </button>
        
        <template v-for="page in totalPages" :key="page">
          <button 
            v-if="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
            @click="goToPage(page)"
            class="pagination-btn"
            :class="{ 'active': currentPage === page }"
          >
            {{ page }}
          </button>
          <span v-else-if="page === currentPage - 2 || page === currentPage + 2" class="pagination-dots">...</span>
        </template>
        
        <button 
          @click="goToPage(currentPage + 1)" 
          :disabled="currentPage === totalPages"
          class="pagination-btn"
          :class="{ 'disabled': currentPage === totalPages }"
        >
          <LucideChevronRight style="width: 1.25rem; height: 1.25rem;" />
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

.dropdown-menu-venue {
  width: 16rem;
  max-height: 300px;
  overflow-y: auto;
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

/* Pagination styles */
.pagination-btn {
  width: 40px;
  height: 40px;
  border: 2px solid #1a1a1a;
  background: #fff;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(.disabled) {
  background: var(--brand-lime, #C8F560);
}

.pagination-btn.active {
  background: var(--brand-pink, #FF4D6D);
  color: #fff;
  border-color: var(--brand-pink, #FF4D6D);
}

.pagination-btn.disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-dots {
  padding: 0 0.5rem;
  color: #666;
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
