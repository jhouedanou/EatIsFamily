<template>
  <div class="about-page">
    <section v-if="content" class="page-hero">
      <div class="container">
        <h1>{{ content.about.hero.title }}</h1>
        <p>{{ content.about.hero.subtitle }}</p>
      </div>
    </section>

    <section v-if="content" class="page-content">
      <div class="container">
        <div class="content-section">
          <h2>{{ content.about.mission.title }}</h2>
          <p>{{ content.about.mission.content }}</p>
        </div>

        <div class="content-section">
          <h2>{{ content.about.vision.title }}</h2>
          <p>{{ content.about.vision.content }}</p>
        </div>

        <div class="values-section">
          <h2>Our Values</h2>
          <div class="grid grid-2">
            <div v-for="value in content.about.values" :key="value.title" class="value-card">
              <h3>{{ value.title }}</h3>
              <p>{{ value.description }}</p>
            </div>
          </div>
        </div>

        <div class="team-section">
          <h2>Meet Our Team</h2>
          <div class="grid grid-3">
            <div v-for="member in content.about.team" :key="member.name" class="team-card">
              <img :src="member.image" :alt="member.name" />
              <h3>{{ member.name }}</h3>
              <p class="role">{{ member.role }}</p>
              <p>{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { getSiteContent } = useSiteContent()
const content = ref<any>(null)

onMounted(async () => {
  content.value = await getSiteContent()
})

useHead({
  title: 'About Us - Eat Is Friday',
  meta: [
    { name: 'description', content: 'Learn about Eat Is Friday, our mission, vision, and the team behind our culinary experiences.' }
  ]
})
</script>

<style scoped>
.content-section {
  margin-bottom: 3rem;
}

.content-section h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
  color: #2d3748;
}

.content-section p {
  font-size: 1.125rem;
  line-height: 1.8;
  color: #4a5568;
}

.values-section,
.team-section {
  margin-top: 4rem;
}

.values-section h2,
.team-section h2 {
  font-size: 2rem;
  margin-bottom: 2rem;
  text-align: center;
  color: #2d3748;
}

.value-card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.value-card h3 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #667eea;
}

.team-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.team-card img {
  width: 100%;
  height: 250px;
  object-fit: cover;
}

.team-card h3 {
  font-size: 1.25rem;
  margin: 1rem 0 0.5rem;
  color: #2d3748;
}

.team-card .role {
  color: #667eea;
  font-weight: 600;
  margin-bottom: 1rem;
}

.team-card p:last-child {
  padding: 0 1.5rem 1.5rem;
  color: #4a5568;
}
</style>
