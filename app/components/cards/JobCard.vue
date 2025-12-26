<template>
  <div class="job-card">
    <div class="card-image">
      <img :src="job.featured_media" :alt="job.title.rendered" loading="lazy" />
      <span class="job-type-badge">{{ job.job_type }}</span>
    </div>
    <div class="card-content">
      <h3>{{ job.title.rendered }}</h3>
      <div class="job-meta">
        <span class="location">📍 {{ job.location }}</span>
        <span class="salary">💰 {{ job.salary }}</span>
      </div>
      <div class="excerpt" v-html="job.excerpt.rendered"></div>
      <NuxtLink :to="`/jobs/${job.slug}`" class="btn-primary">View Details</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Job } from '~/composables/useJobs'

defineProps<{
  job: Job
}>()
</script>

<style scoped>
.job-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.job-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.card-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.job-card:hover .card-image img {
  transform: scale(1.05);
}

.job-type-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(102, 126, 234, 0.9);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
}

.card-content {
  padding: 1.5rem;
}

.card-content h3 {
  font-size: 1.25rem;
  margin-bottom: 1rem;
  color: #2d3748;
}

.job-meta {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 1rem;
  font-size: 0.875rem;
  color: #718096;
}

.excerpt {
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.excerpt :deep(p) {
  margin: 0;
}

.btn-primary {
  display: inline-block;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}
</style>
