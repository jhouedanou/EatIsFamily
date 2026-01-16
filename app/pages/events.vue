<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Event } from '~/composables/useEvents'

const { getContentByPath } = usePageContent()

const content = ref<any>(null)
const events = ref<Event[]>([])
const loading = ref(true)

onMounted(async () => {
  // Charger le contenu de la page
  content.value = await getContentByPath('events')

  // Charger les événements depuis events.json
  try {
    const response = await fetch('/api/events.json')
    const data = await response.json()
    events.value = data
    console.log('Events loaded:', data)
  } catch (error) {
    console.error('Error loading events:', error)
  }

  loading.value = false
})

useHead({
  title: 'Events - Eat Is Family',
  meta: [
    { name: 'description', content: 'Discover our food events and culinary experiences across France.' }
  ]
})
</script>

<template>
  <div class="events-page">
    <!-- Hero Section -->
    <section v-if="content" class="page-hero">
      <div class="container">
        <h1>{{ content.page_hero.title }}</h1>
        <p class="subtitle">{{ content.page_hero.subtitle }}</p>
        <NuxtLink v-if="content.page_hero.link" :to="content.page_hero.link" class="btn-primary">
          Contact Us
        </NuxtLink>
      </div>
    </section>

    <!-- Intro Section -->
    <section v-if="content?.section2" class="intro-section">
      <div class="container">
        <p class="intro-text">{{ content.section2 }}</p>
      </div>
    </section>

    <!-- Events List Section -->
    <section class="events-section">
      <div class="container">
        <div v-if="content?.eventslist" class="section-header">
          <p class="section-description">{{ content.eventslist.description }}</p>
        </div>

        <div v-if="loading" class="loading">
          Loading events...
        </div>

        <div v-else-if="events.length > 0" class="events-grid">
          <CardsEventCard v-for="event in events" :key="event.id" :event="event" />
        </div>

        <div v-else class="no-events">
          No events found. ({{ events.length }} events)
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped lang="scss">
.events-page {
  min-height: 100vh;
}

.page-hero {
  background: linear-gradient(135deg, #FF4D6D 0%, #ff6b88 100%);
  color: white;
  padding: 6rem 0 4rem;
  text-align: center;

  h1 {
    font-family: 'Recoleta', serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    margin-bottom: 1rem;
    white-space: pre-line;
  }

  .subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 2rem;
    white-space: pre-line;
  }

  .btn-primary {
    display: inline-block;
    background: white;
    color: #FF4D6D;
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);

    &:hover {
      transform: translate(-2px, -2px);
      box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.2);
    }
  }
}

.intro-section {
  padding: 4rem 0;
  background: #fafafa;

  .intro-text {
    max-width: 800px;
    margin: 0 auto;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #4a5568;
    text-align: center;
    white-space: pre-line;
  }
}

.events-section {
  padding: 4rem 0;

  .section-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .section-description {
    font-size: 1.25rem;
    color: #2d3748;
    font-family: 'Recoleta', serif;
  }
}

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

.loading,
.no-events {
  text-align: center;
  padding: 4rem 0;
  font-size: 1.25rem;
  color: #718096;
}
</style>
