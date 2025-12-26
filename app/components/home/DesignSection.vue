<template>
  <section :class="['section', backgroundColor]">
    <div class="container">
      <div v-if="title || subtitle" class="section-header">
        <h2 v-if="title" class="section-title">{{ title }}</h2>
        <p v-if="subtitle" class="section-subtitle">{{ subtitle }}</p>
      </div>
      <slot />
    </div>
  </section>
</template>

<script setup lang="ts">
defineProps<{
  title?: string
  subtitle?: string
  backgroundColor?: 'white' | 'light' | 'pink' | 'blue' | 'yellow'
}>()
</script>

<style scoped>
.section {
  padding: 5rem 0;
}

.section.white { background-color: #ffffff; }
.section.light { background-color: var(--color-bg-light); }
.section.pink { background-color: var(--color-primary-pink); color: white; }
.section.blue { background-color: var(--color-primary-blue); color: white; }
.section.yellow { background-color: var(--color-highlight-yellow); }

.section-header {
  margin-bottom: 3rem;
  max-width: 800px;
}

.section-title {
  font-size: 2.5rem; /* Recoleta defaults to heading font */
  margin-bottom: 1rem;
}

.section-subtitle {
  font-size: 1.125rem;
  opacity: 0.9;
}

/* Specific overrides for colored sections */
.section.pink .section-title,
.section.blue .section-title {
  color: white;
}

@media (max-width: 768px) {
  .section {
    padding: 3rem 0;
  }
  
  .section-title {
    font-size: 2rem;
  }
}
</style>
