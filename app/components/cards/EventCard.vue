<template>
  <div class="event-card">
    <div class="card-image">
      <img :src="event.featured_media" :alt="event.title.rendered" loading="lazy" />
      <span class="event-type-badge">{{ event.event_type }}</span>
    </div>
    <div class="card-content">
      <h3>{{ event.title.rendered }}</h3>
      <div class="excerpt" v-html="event.excerpt.rendered"></div>
      <div class="event-meta">
        <span>📅 {{ formatDate(event.date) }}</span>
        <span>📍 {{ event.location }}</span>
        <span>🎫 {{ event.ticket_price }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Event } from '~/composables/useEvents'

defineProps<{
  event: Event
}>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<style scoped>
.event-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card:hover {
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

.event-card:hover .card-image img {
  transform: scale(1.05);
}

.event-type-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(237, 100, 166, 0.9);
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

.excerpt {
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.excerpt :deep(p) {
  margin: 0;
}

.event-meta {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #718096;
}
</style>
