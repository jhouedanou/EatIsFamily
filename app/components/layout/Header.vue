<template>
  <header class="header">
    <div class="container header-grid">
      <!-- Left Navigation -->
      <nav class="nav-left desktop-only">
        <NuxtLink to="/careers" class="nav-link">Careers</NuxtLink>
        <NuxtLink to="/blog" class="nav-link">Blogs</NuxtLink>
        <button class="btn-cta" @click="openContactModal">Get in touch</button>
      </nav>

      <!-- Center Logo -->
      <div class="logo-container">
        <NuxtLink to="/" class="logo">
          LOGO
        </NuxtLink>
      </div>

      <!-- Right Navigation -->
      <nav class="nav-right desktop-only">
        <NuxtLink to="/about" class="nav-link">About</NuxtLink>
        <NuxtLink to="/apply-activities" class="nav-link">Activities</NuxtLink>
        <NuxtLink to="/events" class="nav-link">Our Events</NuxtLink>
      </nav>

      <!-- Mobile Menu Toggle -->
      <div class="mobile-toggle">
        <Navigation @open-contact="openContactModal" />
      </div>
    </div>

    <ContactModal :is-open="isContactModalOpen" @close="closeContactModal" />
  </header>
</template>

<script setup lang="ts">
const isContactModalOpen = ref(false)

const openContactModal = () => {
  isContactModalOpen.value = true
}

const closeContactModal = () => {
  isContactModalOpen.value = false
}
</script>

<style scoped>
.header {
  background: white;
  padding: 1.5rem 0;
  position: sticky;
  top: 0;
  z-index: 1000;
  border-bottom: 1px solid #f1f2f6;
}

.header-grid {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 2rem;
  justify-content: flex-start;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 2rem;
  justify-content: flex-end;
}

.nav-link {
  font-weight: 500;
  color: var(--color-text-dark);
  transition: color 0.2s ease;
  font-family: var(--font-body);
}

.nav-link:hover {
  color: var(--color-primary-blue);
}

.logo {
  font-family: var(--font-heading);
  font-size: 2rem;
  font-weight: 900;
  color: var(--color-text-dark);
  text-decoration: none;
  letter-spacing: -1px;
}

.mobile-toggle {
  display: none;
}

/* Button override for header */
.btn-cta {
  font-size: 0.9rem;
  padding: 0.5rem 1.25rem;
}

@media (max-width: 968px) {
  .header-grid {
    display: flex;
    justify-content: space-between;
  }
  
  .desktop-only {
    display: none;
  }
  
  .mobile-toggle {
    display: block;
  }
}
</style>
