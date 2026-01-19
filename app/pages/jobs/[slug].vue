<script setup lang="ts">
import { LucideX, LucideMapPin, LucideBriefcase, LucideClock, LucideDollarSign, LucideHeart, LucideShare2, LucideChevronRight } from 'lucide-vue-next'
import type { Job } from '~/composables/useJobs'

const route = useRoute()
const router = useRouter()
const { getJobBySlug, getJobs } = useJobs()

const job = ref<Job | null>(null)
const isLoading = ref(true)
const showApplyModal = ref(false)

// Gallery images for "Life at" section
const galleryImages = [
  '/images/gallery/stadium.jpg',
  '/images/gallery/team.jpg',
  '/images/gallery/kitchen.jpg',
  '/images/gallery/venue.jpg'
]

onMounted(async () => {
  const slug = route.params.slug as string
  job.value = await getJobBySlug(slug)
  isLoading.value = false
})

const getJobTitle = (j: Job) => {
  return typeof j.title === 'string' ? j.title : j.title?.rendered || ''
}

const getJobExcerpt = (j: Job) => {
  return typeof j.excerpt === 'string' ? j.excerpt : j.excerpt?.rendered || ''
}

const openApplyModal = () => {
  showApplyModal.value = true
}

const closeApplyModal = () => {
  showApplyModal.value = false
}

const goBack = () => {
  router.push('/careers')
}

const shareJob = () => {
  if (navigator.share) {
    navigator.share({
      title: job.value ? getJobTitle(job.value) : 'Job Opening',
      url: window.location.href
    })
  } else {
    navigator.clipboard.writeText(window.location.href)
    alert('Link copied to clipboard!')
  }
}

// What you'll do - responsibilities
const responsibilities = [
  'Lead daily operations and ensure quality standards',
  'Collaborate with team members to deliver excellence',
  'Maintain safety and hygiene protocols',
  'Contribute to menu planning and innovation',
  'Train and mentor junior staff members'
]

useHead(() => ({
  title: job.value ? `${getJobTitle(job.value)} - Careers | Eat Is Family` : 'Job Details',
  meta: [
    { name: 'description', content: job.value ? getJobExcerpt(job.value) : '' }
  ]
}))
</script>

<template>
  <div class="job-detail-page">
    <!-- Loading State -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Loading job details...</p>
    </div>

    <!-- Job Not Found -->
    <div v-else-if="!job" class="not-found-container">
      <h1>Job Not Found</h1>
      <p>The position you're looking for doesn't exist or has been filled.</p>
      <NuxtLink to="/careers" class="btn-back">
        Browse All Jobs
      </NuxtLink>
    </div>

    <!-- Job Details -->
    <div v-else class="job-detail-content">
      <!-- Header Bar -->
      <header class="detail-header">
        <div class="header-inner">
          <div class="location-info">
            <LucideMapPin class="location-icon" />
            <span>{{ job.location }}</span>
          </div>
          <button class="close-btn" @click="goBack" aria-label="Close">
            <LucideX :size="20" :stroke-width="2.5" />
          </button>
        </div>
        <div class="header-divider"></div>
      </header>

      <!-- Main Content -->
      <main class="detail-main">
        <div class="container">
          <!-- Job Title & Tags -->
          <section class="job-intro">
            <h1 class="job-title">{{ getJobTitle(job) }}</h1>

            <div class="job-tags">
              <span class="tag tag-blue">
                Department - {{ job.department || 'Culinary' }}
              </span>
              <span class="tag tag-lime">
                <LucideBriefcase :size="14" />
                {{ job.job_type }}
              </span>
              <span class="tag tag-yellow">
                <LucideDollarSign :size="14" />
                {{ job.salary }}
              </span>
            </div>

            <p class="job-excerpt">{{ getJobExcerpt(job) }}</p>
          </section>

          <!-- CTA Banner - Ready To Join -->
          <div class="cta-banner">
            <div class="cta-content">
              <h3>Ready To Join Our Team?</h3>
              <p>Apply now and be part of something special</p>
            </div>
            <button class="btn-apply-pink" @click="openApplyModal">
              Apply for this position
            </button>
          </div>

          <!-- Life at Location Section -->
          <section class="life-section">
            <h2 class="section-title">Life at {{ job.location }}</h2>
            <div class="gallery-grid">
              <div v-for="(img, index) in galleryImages" :key="index" class="gallery-item">
                <img :src="job.featured_media || `/images/placeholder-${index + 1}.jpg`" :alt="`Life at ${job.location}`" />
              </div>
            </div>
          </section>

          <!-- Job Description And Requirement -->
          <section class="description-section">
            <h2 class="section-title">Job Description And Requirement</h2>

            <div class="description-grid">
              <!-- What You'll Do -->
              <div class="description-card">
                <h3>What You'll Do</h3>
                <p class="card-intro">You'll do the following:</p>
                <ul class="description-list">
                  <li v-for="(item, index) in responsibilities" :key="index">
                    <LucideChevronRight :size="16" class="list-icon" />
                    <span>{{ item }}</span>
                  </li>
                </ul>
              </div>

              <!-- What We're Looking For -->
              <div class="description-card">
                <h3>What We're Looking For</h3>
                <p class="card-intro">The following requirement is what we are looking for</p>
                <ul class="description-list">
                  <li v-for="(req, index) in job.requirements" :key="index">
                    <LucideChevronRight :size="16" class="list-icon" />
                    <span>{{ req }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </section>

          <!-- Three Cards Row -->
          <div class="info-cards-grid">
            <!-- Why Join Us - Pink Card -->
            <div class="info-card card-pink">
              <div class="card-header">
                <LucideHeart :size="20" class="card-icon" />
                <h3>Why Join Us</h3>
              </div>
              <ul class="benefits-list">
                <li v-for="(benefit, index) in job.benefits" :key="index">
                  <LucideChevronRight :size="14" class="list-icon" />
                  <span>{{ benefit }}</span>
                </li>
              </ul>
            </div>

            <!-- Quick Facts - White Card -->
            <div class="info-card card-white">
              <h3>Quick Facts</h3>
              <div class="facts-list">
                <div class="fact-item">
                  <span class="fact-label">LOCATION</span>
                  <span class="fact-value">{{ job.location }}</span>
                </div>
                <div class="fact-item">
                  <span class="fact-label">DEPARTMENT</span>
                  <span class="fact-value">{{ job.department || 'Culinary' }}</span>
                </div>
                <div class="fact-item">
                  <span class="fact-label">EMPLOYMENT TYPE</span>
                  <span class="fact-value">{{ job.job_type }}</span>
                </div>
                <div class="fact-item">
                  <span class="fact-label">AVAILABLE POSITIONS</span>
                  <span class="fact-value fact-highlight">{{ job.positions || '2 Slots' }}</span>
                </div>
              </div>
            </div>

            <!-- Share Job - Blue Card -->
            <div class="info-card card-blue">
              <h3>Do You Know Someone That Is Perfect For This Position?</h3>
              <p>Kindly Share This Job To The Person</p>
              <button class="btn-share" @click="shareJob">
                Share this job
              </button>
            </div>
          </div>

          <!-- Bottom CTA Section -->
          <section class="bottom-cta">
            <div class="bottom-cta-content">
              <h2>Ready To Make An Impact?</h2>
              <p>Join our team and be part of creating unforgettable experiences at one of France's most exciting venues.</p>
              <div class="bottom-cta-buttons">
                <button class="btn-apply-bottom" @click="openApplyModal">
                  Apply for this position
                </button>
                <button class="btn-back-jobs" @click="goBack">
                  Go back to jobs
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>

      <!-- Apply Modal -->
      <JobApplyModal
        :is-open="showApplyModal"
        :job-title="getJobTitle(job)"
        :job-location="job.location"
        :job-slug="job.slug"
        @close="closeApplyModal"
      />
    </div>
  </div>
</template>

<style scoped lang="scss">
.job-detail-page {
  min-height: 100vh;
  background: #FAFAFA;
}

// Loading & Not Found
.loading-container,
.not-found-container {
  min-height: 60vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 2rem;
}

.loading-spinner {
  width: 48px;
  height: 48px;
  border: 4px solid #E5E5E5;
  border-top-color: var(--brand-pink);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.btn-back {
  margin-top: 1.5rem;
  padding: 0.875rem 1.5rem;
  background: var(--brand-pink);
  color: white;
  border-radius: 0.5rem;
  text-decoration: none;
  font-weight: 600;
}

// Header - White background like design
.detail-header {
  background: white;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.location-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--brand-dark);
  font-weight: 500;
  font-size: 0.9375rem;
}

.location-icon {
  width: 18px;
  height: 18px;
  color: var(--brand-pink);
}

.close-btn {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--brand-yellow);
  border: 2px solid var(--brand-dark);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    transform: rotate(90deg);
  }
}

.header-divider {
  height: 3px;
  background: var(--brand-blue);
}

// Main Content
.detail-main {
  padding: 2.5rem 0 0;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

// Job Intro Section
.job-intro {
  margin-bottom: 2rem;
}

.job-title {
  font-family: var(--font-heading);
  font-size: clamp(1.75rem, 4vw, 2.5rem);
  font-weight: 700;
  color: var(--brand-dark);
  margin: 0 0 1rem;
  line-height: 1.2;
}

.job-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tag {
  padding: 0.5rem 0.875rem;
  border-radius: 100px;
  font-size: 0.8125rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  border: 2px solid var(--brand-dark);
}

.tag-blue {
  background: var(--brand-blue);
  color: var(--brand-dark);
}

.tag-lime {
  background: var(--brand-lime);
  color: var(--brand-dark);
}

.tag-yellow {
  background: var(--brand-yellow);
  color: var(--brand-dark);
}

.job-excerpt {
  font-size: 0.9375rem;
  line-height: 1.7;
  color: rgba(0, 0, 0, 0.7);
  margin: 0;
}

// CTA Banner
.cta-banner {
  background: var(--brand-blue);
  border-radius: 1rem;
  padding: 1.5rem 2rem;
  margin-bottom: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.cta-content {
  h3 {
    font-family: var(--font-heading);
    font-size: 1.375rem;
    font-weight: 700;
    margin: 0 0 0.25rem;
    color: var(--brand-dark);
  }

  p {
    margin: 0;
    color: rgba(0, 0, 0, 0.6);
    font-size: 0.9375rem;
  }
}

.btn-apply-pink {
  background: var(--brand-pink);
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 100px;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 77, 109, 0.3);
  }
}

// Section Title
.section-title {
  font-family: var(--font-heading);
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--brand-dark);
  margin: 0 0 1.25rem;
}

// Life at Section - Gallery
.life-section {
  margin-bottom: 2.5rem;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.gallery-item {
  aspect-ratio: 1;
  border-radius: 1rem;
  overflow: hidden;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

// Description Section
.description-section {
  margin-bottom: 2rem;
}

.description-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.description-card {
  background: white;
  border: 2px dashed rgba(0, 0, 0, 0.2);
  border-radius: 1rem;
  padding: 1.5rem;

  h3 {
    font-family: var(--font-heading);
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
    color: var(--brand-dark);
  }

  .card-intro {
    font-size: 0.875rem;
    color: rgba(0, 0, 0, 0.6);
    margin: 0 0 1rem;
  }
}

.description-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.625rem;

  li {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: rgba(0, 0, 0, 0.75);
    line-height: 1.4;
  }

  .list-icon {
    flex-shrink: 0;
    margin-top: 2px;
    color: rgba(0, 0, 0, 0.4);
  }
}

// Three Info Cards
.info-cards-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 1rem;
  margin-bottom: 2.5rem;
}

.info-card {
  border-radius: 1rem;
  padding: 1.5rem;

  h3 {
    font-family: var(--font-heading);
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 1rem;
    color: var(--brand-dark);
  }
}

.card-pink {
  background: var(--brand-pink);

  h3, .benefits-list li {
    color: white;
  }

  .card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;

    h3 {
      margin: 0;
    }
  }

  .card-icon {
    color: white;
  }
}

.benefits-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;

  li {
    display: flex;
    align-items: flex-start;
    gap: 0.375rem;
    font-size: 0.8125rem;
    line-height: 1.4;
  }

  .list-icon {
    flex-shrink: 0;
    margin-top: 2px;
    opacity: 0.8;
  }
}

.card-white {
  background: white;
  border: 2px solid var(--brand-dark);
}

.facts-list {
  display: flex;
  flex-direction: column;
  gap: 0.875rem;
}

.fact-item {
  display: flex;
  flex-direction: column;
  gap: 0.125rem;
}

.fact-label {
  font-size: 0.6875rem;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.5);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.fact-value {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--brand-dark);
}

.fact-highlight {
  color: var(--brand-pink);
}

.card-blue {
  background: var(--brand-blue);

  h3 {
    font-size: 1rem;
    line-height: 1.3;
    margin-bottom: 0.5rem;
  }

  p {
    font-size: 0.8125rem;
    color: rgba(0, 0, 0, 0.6);
    margin: 0 0 1rem;
    line-height: 1.4;
  }
}

.btn-share {
  background: var(--brand-pink);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 100px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s ease;

  &:hover {
    transform: translateY(-2px);
  }
}

// Bottom CTA
.bottom-cta {
  background: #2D3748;
  border-radius: 1.5rem;
  padding: 3rem 2rem;
  text-align: center;
  margin-bottom: 3rem;
}

.bottom-cta-content {
  max-width: 500px;
  margin: 0 auto;

  h2 {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.75rem;
  }

  p {
    font-size: 0.9375rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0 0 1.5rem;
    line-height: 1.5;
  }
}

.bottom-cta-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn-apply-bottom {
  background: var(--brand-pink);
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 100px;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 77, 109, 0.3);
  }
}

.btn-back-jobs {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 100px;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    background: rgba(255, 255, 255, 0.2);
  }
}

// Responsive
@media (max-width: 900px) {
  .gallery-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .info-cards-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .description-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .cta-banner {
    flex-direction: column;
    text-align: center;
    padding: 1.5rem;
  }

  .gallery-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
  }

  .detail-main {
    padding: 2rem 0 0;
  }

  .job-title {
    font-size: 1.5rem;
  }

  .bottom-cta {
    padding: 2rem 1.5rem;
  }

  .bottom-cta-content h2 {
    font-size: 1.5rem;
  }
}
</style>
