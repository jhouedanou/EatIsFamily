<script setup lang="ts">
// Default layout
</script>

<template>
  <div class="d-flex flex-column min-vh-100 bg-brand-gray overflow-hidden">
    <LayoutHeader />

    <main class="flex-grow-1 pt-0">
      <slot />
    </main>

    <LayoutFooter />
  </div>
</template>
