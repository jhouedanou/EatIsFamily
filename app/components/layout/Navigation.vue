<template>
  <div v-if="content" class="mobile-nav">
    <button class="menu-toggle" @click="toggleMenu" aria-label="Toggle menu">
      <span :class="{ open: isOpen }"></span>
      <span :class="{ open: isOpen }"></span>
      <span :class="{ open: isOpen }"></span>
    </button>

    <div :class="['nav-overlay', { open: isOpen }]">
      <div class="nav-content">
        <NuxtLink to="/careers" @click="closeMenu">{{ content.nav_links.careers }}</NuxtLink>
        <!-- Blog masqué temporairement -->
        <!-- <NuxtLink to="/blog" @click="closeMenu">{{ content.nav_links.blogs }}</NuxtLink> -->
        <NuxtLink to="/about" @click="closeMenu">{{ content.nav_links.about }}</NuxtLink>
        <NuxtLink to="/apply-activities" @click="closeMenu">{{ content.nav_links.activities }}</NuxtLink>
        <NuxtLink to="/events" @click="closeMenu">{{ content.nav_links.events }}</NuxtLink>
        <NuxtLink to="/contact" class="btn-cta mobile-cta" @click="closeMenu">{{ content.nav_links.get_in_touch }}</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { getHeaderContent } = usePageContent()
const content = ref<any>(null)
const isOpen = ref(false)

onMounted(async () => {
  content.value = await getHeaderContent()
})

const toggleMenu = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

const closeMenu = () => {
  isOpen.value = false
  document.body.style.overflow = ''
}
</script>

<style scoped lang="scss">
.menu-toggle {
  display: flex;
  flex-direction: column;
  gap: 5px;
  z-index: 1002;
  position: relative;
}

.menu-toggle span {
  width: 25px;
  height: 3px;
  background-color: var(--color-text-dark);
  border-radius: 2px;
  transition: all 0.3s ease;
}

.menu-toggle span.open:nth-child(1) {
  transform: rotate(45deg) translate(5px, 6px);
}

.menu-toggle span.open:nth-child(2) {
  opacity: 0;
}

.menu-toggle span.open:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -6px);
}

.nav-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: white;
  z-index: 1001;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.nav-overlay.open {
  opacity: 1;
  visibility: visible;
}

.nav-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  text-align: center;
}

.nav-content a {
  font-family: var(--font-heading);
  font-size: 2rem;
  color: var(--color-text-dark);
  font-weight: 700;
}

.mobile-cta {
  margin-top: 1rem;
  font-size: 1.25rem;
  padding: 1rem 2rem;
}
</style>
