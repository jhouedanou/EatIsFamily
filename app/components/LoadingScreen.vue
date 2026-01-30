<template>
  <div class="loading-container d-flex justify-content-center align-items-center min-vh-100">
    <img 
      src="/images/imageLogo.png" 
      alt="Chargement..." 
      class="loading-logo"
    />
  </div>
</template>

<script setup lang="ts">
// Composant de chargement réutilisable
</script>

<style scoped lang="scss">
.loading-container {
  background-color: #fff;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
}

.loading-logo {
  max-width: 200px;
  height: auto;
  animation: fadeInOut 1.5s ease-in-out infinite;
}

@keyframes fadeInOut {
  0% {
    opacity: 0.3;
    transform: scale(0.95);
  }
  50% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0.3;
    transform: scale(0.95);
  }
}
</style>
