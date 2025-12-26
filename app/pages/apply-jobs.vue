<template>
  <div class="apply-jobs-page">
    <section class="page-hero">
      <div class="container">
        <h1>Job Opportunities</h1>
        <p>Find your next career opportunity in the food industry</p>
      </div>
    </section>

    <section class="page-content">
      <div class="container">
        <div v-if="jobs" class="grid grid-2">
          <JobCard v-for="job in jobs" :key="job.id" :job="job" />
        </div>
        <div v-else class="loading">Loading jobs...</div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { getJobs } = useJobs()
const jobs = ref<any>(null)

onMounted(async () => {
  jobs.value = await getJobs()
})

useHead({
  title: 'Job Opportunities - Eat Is Friday',
  meta: [
    { name: 'description', content: 'Explore career opportunities in the food industry. Join our team and build your culinary career.' }
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
