<template>
  <div class="activity-card">
    <div class="card-image">
      <img :src="activity.featured_media" :alt="activity.title.rendered" loading="lazy" />
      <span class="category-badge">{{ activity.category }}</span>
    </div>
    <div class="card-content">
      <h3>{{ activity.title.rendered }}</h3>
      <p class="description">{{ activity.description }}</p>
      <div class="activity-meta">
        <span>📅 {{ formatDate(activity.date) }}</span>
        <span>📍 {{ activity.location }}</span>
        <span>⏱️ {{ activity.duration }}</span>
        <span>💵 {{ activity.price }}</span>
      </div>
      <div class="availability">
        <span class="spots">{{ activity.available_spots }} / {{ activity.capacity }} spots available</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Activity } from '~/composables/useActivities'

defineProps<{
  activity: Activity
}>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.activity-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.activity-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.card-image {
  position: relative;
  height: 220px;
  overflow: hidden;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.activity-card:hover .card-image img {
  transform: scale(1.05);
}

.category-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(118, 75, 162, 0.9);
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
  margin-bottom: 0.75rem;
  color: #2d3748;
}

.description {
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.activity-meta {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.5rem;
  margin-bottom: 1rem;
  font-size: 0.875rem;
  color: #718096;
}

.availability {
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.spots {
  font-weight: 600;
  color: #667eea;
}
</style>
