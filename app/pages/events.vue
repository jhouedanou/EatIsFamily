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
      <div class="container d-flex">
        <div class="loris">
          <h1>{{ content.page_hero.title }}</h1>
          <p class="subtitle">{{ content.page_hero.subtitle }}</p>
          <NuxtLink v-if="content.page_hero.link" :to="content.page_hero.link" class="mt-4">
            <nuxt-img :src="content.page_hero.btn" />
          </NuxtLink>
        </div>

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
  background: url('/images/events-hero.jpg') center/cover no-repeat;
  height: 90vh;
  margin: 0 0 0 0;
  justify-content: flex-end;
  display: flex;
  align-items: flex-end;
  a{  
     width: 250px;
  height: 70px;
    img{
    width: 250px;
height: 70px;
  }
  }
  .loris {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    background: url("/images/loris.svg");
    background-size: cover;
    background-repeat: no-repeat;
    max-width: 915px;
    max-height: 472px;
    padding: 3em;
    height: 100vh;
    width: 100vw;
  }

  h1 {
    font-family: FONTSPRINGDEMO-RecoletaBold;
    font-size: 75px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.2;
    letter-spacing: normal;
    text-align: left;
    color: #000;

  }

  .subtitle {
    font-family: FONTSPRINGDEMO-Recoleta;
    font-size: 18px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: normal;
    text-align: left;
    color: #161616;
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
