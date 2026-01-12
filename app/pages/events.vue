<template>
  <div class="events-page">
    <section v-if="content" class="page-hero">
      <div class="container">
        <h1>{{ content.page_hero.title }}</h1>
        <p>{{ content.page_hero.subtitle }}</p>
      </div>
    </section>

    <section class="page-content">
      <div class="container">
        <div v-if="events" class="grid grid-2">
          <EventCard v-for="event in events" :key="event.id" :event="event" />
        </div>
        <div v-else-if="content" class="loading">{{ content.loading_text }}</div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const { getEvents } = useEvents()
const { getContentByPath } = usePageContent()

const events = ref<any>(null)
const content = ref<any>(null)

onMounted(async () => {
  content.value = await getContentByPath('events')
  events.value = await getEvents()
})

useHead(() => ({
  title: content.value?.seo?.title || 'Events - Eat Is Family',
  meta: [
    { name: 'description', content: content.value?.seo?.meta_description || '' }
  ]
}))
</script>

<style scoped lang="scss">
.loading {
  text-align: center;
  padding: 4rem 0;
  font-size: 1.25rem;
  color: #718096;
}
</style>
