<template>
  <div v-if="job" class="job-detail-page">
    <section class="job-header">
      <img :src="job.featured_media" :alt="job.title.rendered" class="header-image" />
      <div class="header-overlay">
        <div class="container">
          <h1>{{ job.title.rendered }}</h1>
          <div class="job-meta">
            <span class="meta-item">📍 {{ job.location }}</span>
            <span class="meta-item">💼 {{ job.job_type }}</span>
            <span class="meta-item">💰 {{ job.salary }}</span>
          </div>
        </div>
      </div>
    </section>

    <section class="job-content">
      <div class="container">
        <div class="content-grid">
          <div class="main-content">
            <div class="section">
              <h2>About the Position</h2>
              <div v-html="job.content.rendered"></div>
            </div>

            <div class="section">
              <h2>Requirements</h2>
              <ul class="requirements-list">
                <li v-for="(req, index) in job.requirements" :key="index">{{ req }}</li>
              </ul>
            </div>

            <div class="section">
              <h2>Benefits</h2>
              <ul class="benefits-list">
                <li v-for="(benefit, index) in job.benefits" :key="index">{{ benefit }}</li>
              </ul>
            </div>
          </div>

          <div class="sidebar">
            <div class="apply-card">
              <h3>Interested?</h3>
              <p>Apply for this position and join our team!</p>
              <FormsJobApplicationForm />
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div v-else class="loading">
    <div class="container">Loading job details...</div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { getJobBySlug } = useJobs()
const job = ref<any>(null)

onMounted(async () => {
  const slug = route.params.slug as string
  job.value = await getJobBySlug(slug)
})

useHead(() => ({
  title: job.value ? `${job.value.title.rendered} - Eat Is Friday` : 'Job Details',
  meta: [
    { name: 'description', content: job.value?.excerpt.rendered || '' }
  ]
}))
</script>

<style scoped>
.job-header {
  position: relative;
  height: 400px;
}

.header-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.header-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.header-overlay h1 {
  color: white;
  font-size: 3rem;
  margin-bottom: 1rem;
}

.job-meta {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}

.meta-item {
  color: white;
  font-size: 1.125rem;
  font-weight: 500;
}

.job-content {
  padding: 4rem 0;
}

.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 3rem;
}

.section {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

.section h2 {
  font-size: 1.75rem;
  margin-bottom: 1rem;
  color: #2d3748;
}

.section :deep(p) {
  line-height: 1.8;
  color: #4a5568;
  margin-bottom: 1rem;
}

.requirements-list,
.benefits-list {
  list-style: none;
  padding: 0;
}

.requirements-list li,
.benefits-list li {
  padding: 0.75rem 0;
  padding-left: 2rem;
  position: relative;
  color: #4a5568;
  border-bottom: 1px solid #e2e8f0;
}

.requirements-list li:last-child,
.benefits-list li:last-child {
  border-bottom: none;
}

.requirements-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #667eea;
  font-weight: bold;
}

.benefits-list li::before {
  content: '★';
  position: absolute;
  left: 0;
  color: #764ba2;
}

.sidebar {
  position: sticky;
  top: 2rem;
  height: fit-content;
}

.apply-card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.apply-card h3 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #2d3748;
}

.apply-card p {
  color: #4a5568;
  margin-bottom: 1.5rem;
}

.loading {
  padding: 4rem 0;
  text-align: center;
  font-size: 1.25rem;
  color: #718096;
}

@media (max-width: 968px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
  
  .sidebar {
    position: static;
  }
  
  .header-overlay h1 {
    font-size: 2rem;
  }
}
</style>
