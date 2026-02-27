<template>
  <Transition name="slide-up">
    <div v-if="showBanner" class="cookie-banner">
      <div class="cookie-banner__inner">
        <div class="cookie-banner__content">
          <h3 class="cookie-banner__title">Gérer le consentement aux cookies</h3>
          <p class="cookie-banner__text">
            Pour offrir les meilleures expériences, nous utilisons des technologies telles que les cookies pour stocker
            et/ou accéder aux informations des appareils. Le fait de consentir à ces technologies nous permettra de
            traiter des données telles que le comportement de navigation ou les ID uniques sur ce site. Le fait de ne
            pas consentir ou de retirer son consentement peut avoir un effet négatif sur certaines caractéristiques et
            fonctions.
          </p>
        </div>
        <div class="cookie-banner__actions">
          <button class="cookie-banner__btn cookie-banner__btn--accept" @click="acceptAll">Accepter</button>
          <button class="cookie-banner__btn cookie-banner__btn--reject" @click="rejectAll">Refuser</button>
          <NuxtLink to="/cookies" class="cookie-banner__link">Politique de cookies</NuxtLink>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'

const { showBanner, acceptAll, rejectAll, init } = useCookieConsent()

onMounted(() => {
  init()
})
</script>

<style scoped lang="scss">
.cookie-banner {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 9999;
  padding: 1.5rem;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(8px);
}

.cookie-banner__inner {
  max-width: 1400px;
  margin: 0 auto;
  background: #fff;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 2rem;
}

.cookie-banner__content {
  flex: 1;
}

.cookie-banner__title {
  font-family: FONTSPRINGDEMO-RecoletaBold, Georgia, serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 0.5rem;
}

.cookie-banner__text {
  font-family: FONTSPRINGDEMO-Recoleta, Georgia, serif;
  font-size: 0.9rem;
  line-height: 1.5;
  color: #444;
  margin: 0;
}

.cookie-banner__actions {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  flex-shrink: 0;
}

.cookie-banner__btn {
  font-family: FONTSPRINGDEMO-RecoletaBold, Georgia, serif;
  font-size: 0.95rem;
  font-weight: 700;
  padding: 0.75rem 2rem;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;

  &--accept {
    background: #f9375b;
    color: #fff;

    &:hover {
      background: #e02e4f;
      transform: translateY(-2px);
    }
  }

  &--reject {
    background: #1a1a1a;
    color: #fff;

    &:hover {
      background: #333;
      transform: translateY(-2px);
    }
  }
}

.cookie-banner__link {
  font-family: FONTSPRINGDEMO-Recoleta, Georgia, serif;
  font-size: 0.85rem;
  color: #f9375b;
  text-align: center;
  text-decoration: underline;
  transition: color 0.2s ease;

  &:hover {
    color: #e02e4f;
  }
}

// Slide-up transition
.slide-up-enter-active {
  transition: all 0.4s ease-out;
}

.slide-up-leave-active {
  transition: all 0.3s ease-in;
}

.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

// Responsive
@media (max-width: 768px) {
  .cookie-banner {
    padding: 1rem;
  }

  .cookie-banner__inner {
    flex-direction: column;
    padding: 1.5rem;
    gap: 1.25rem;
  }

  .cookie-banner__actions {
    width: 100%;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
  }

  .cookie-banner__btn {
    flex: 1;
    min-width: 120px;
  }

  .cookie-banner__link {
    width: 100%;
  }
}
</style>
