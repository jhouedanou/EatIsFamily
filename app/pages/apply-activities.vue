<template>
  <div class="apply-activities-page">
    <section class="page-hero">
      <div class="container">
        <h1>Culinary Activities</h1>
        <p>Join our exciting cooking workshops and food experiences</p>
      </div>
    </section>

    <section class="page-content">
      <div class="container">
        <div v-if="activities" class="grid grid-2">
          <ActivityCard v-for="activity in activities" :key="activity.id" :activity="activity" />
        </div>
        <div v-else class="loading">Loading activities...</div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { getActivities } = useActivities()
const activities = ref<any>(null)

onMounted(async () => {
  activities.value = await getActivities()
})

useHead({
  title: 'Activities - Eat Is Friday',
  meta: [
    { name: 'description', content: 'Explore our culinary workshops, cooking classes, and food experiences. Learn from expert chefs and discover new skills.' }
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
