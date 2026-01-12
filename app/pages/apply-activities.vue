<template>
  <div class="apply-activities-page">
    <section v-if="content" class="page-hero">
      <div class="container">
        <h1>{{ content.page_hero.title }}</h1>
        <p>{{ content.page_hero.subtitle }}</p>
      </div>
    </section>

    <section class="page-content">
      <div class="container">
        <div v-if="activities" class="grid grid-2">
          <ActivityCard v-for="activity in activities" :key="activity.id" :activity="activity" />
        </div>
        <div v-else-if="content" class="loading">{{ content.loading_text }}</div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const { getActivities } = useActivities()
const { getContentByPath } = usePageContent()

const activities = ref<any>(null)
const content = ref<any>(null)

onMounted(async () => {
  content.value = await getContentByPath('apply_activities')
  activities.value = await getActivities()
})

useHead(() => ({
  title: content.value?.seo?.title || 'Activities - Eat Is Family',
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
