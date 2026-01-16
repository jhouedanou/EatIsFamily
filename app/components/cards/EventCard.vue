<template>
  <div class="event-card">
    <div class="card-image">
      <img :src="event.image" :alt="event.title" loading="lazy" />
      <span class="event-type-badge">{{ event.event_type }}</span>
    </div>
    <div class="card-content">
      <h3>{{ event.title }}</h3>
      <p class="description">{{ event.description }}</p>
      <NuxtLink :to="`/events/${event.slug}`" class="btn-view">
        View Event
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Event } from '~/composables/useEvents'

defineProps<{
  event: Event
}>()
</script>

<style scoped>
.event-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 4px 4px 0 rgba(0, 0, 0, 1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card:hover {
  transform: translate(-2px, -2px);
  box-shadow: 6px 6px 0 rgba(0, 0, 0, 1);
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
  background: #FF4D6D;
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
  font-family: 'Recoleta', serif;
  font-size: 1.25rem;
  margin-bottom: 0.75rem;
  color: #2d3748;
}

.description {
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}

.btn-view {
  display: inline-block;
  background: #FF4D6D;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: background 0.3s ease;
}

.btn-view:hover {
  background: #e63956;
}
</style>
