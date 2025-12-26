<template>
  <div class="events-page">
    <section class="page-hero">
      <div class="container">
        <h1>Food Events</h1>
        <p>Discover and join our upcoming culinary events and celebrations</p>
      </div>
    </section>

    <section class="page-content">
      <div class="container">
        <div v-if="events" class="grid grid-2">
          <EventCard v-for="event in events" :key="event.id" :event="event" />
        </div>
        <div v-else class="loading">Loading events...</div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { getEvents } = useEvents()
const events = ref<any>(null)

onMounted(async () => {
  events.value = await getEvents()
})

useHead({
  title: 'Events - Eat Is Friday',
  meta: [
    { name: 'description', content: 'Join our food festivals, tastings, and culinary celebrations. Discover upcoming events and book your spot.' }
  ]
})
</script>

<style scoped>
.loading {
  text-align: center;
  padding: 4rem 0;
  font-size: 1.25rem;
  color: #718096;
}
</style>
