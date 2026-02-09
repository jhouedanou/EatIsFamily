<template>
  <div class="demo-page">
    <div class="demo-container">
      <h1 class="demo-title">Boutons Pilule - Démo</h1>
      <p class="demo-subtitle">Composants de boutons avec bordures irrégulières "sketchy"</p>

      <!-- Section fond clair -->
      <section class="demo-section demo-section--light">
        <h2 class="section-title">Sur fond clair</h2>

        <div class="button-group">
          <div class="button-demo">
            <h3>White</h3>
            <p class="color-code">Fond: #FFFFFF | Bordure: #000000</p>
            <PillButton color="white" to="/contact">Contactez nous</PillButton>
          </div>

          <div class="button-demo">
            <h3>Yellow</h3>
            <p class="color-code">Fond: #FFE600 | Bordure: #000000</p>
            <PillButton color="yellow" to="/register">S'inscrire</PillButton>
          </div>

          <div class="button-demo">
            <h3>Pink</h3>
            <p class="color-code">Fond: #F9375B | Bordure: #000000</p>
            <PillButton color="pink" to="/promo">Découvrir l'offre</PillButton>
          </div>
        </div>
      </section>

      <!-- Section fond sombre -->
      <section class="demo-section demo-section--dark">
        <h2 class="section-title">Sur fond sombre</h2>

        <div class="button-group">
          <div class="button-demo">
            <h3>Transparent</h3>
            <p class="color-code">Fond: transparent | Bordure: #FFFFFF</p>
            <PillButton color="transparent" to="/about">En savoir plus</PillButton>
          </div>

          <div class="button-demo">
            <h3>Pink</h3>
            <p class="color-code">Fond: #F9375B | Bordure: #000000</p>
            <PillButton color="pink" to="/events">Nos événements</PillButton>
          </div>

          <div class="button-demo">
            <h3>Yellow</h3>
            <p class="color-code">Fond: #FFE600 | Bordure: #000000</p>
            <PillButton color="yellow" to="/menu">Voir le menu</PillButton>
          </div>
        </div>
      </section>

      <!-- Section avec fond coloré -->
      <section class="demo-section demo-section--green">
        <h2 class="section-title">Sur fond vert (comme la page Contact)</h2>

        <div class="button-group">
          <div class="button-demo">
            <h3>White</h3>
            <PillButton color="white" to="/contact">Nous contacter</PillButton>
          </div>

          <div class="button-demo">
            <h3>Pink</h3>
            <PillButton color="pink" to="/careers">Rejoignez-nous</PillButton>
          </div>
        </div>
      </section>

      <!-- Section états -->
      <section class="demo-section demo-section--light">
        <h2 class="section-title">États des boutons</h2>

        <div class="button-group">
          <div class="button-demo">
            <h3>Normal</h3>
            <PillButton color="yellow" to="/test">Bouton actif</PillButton>
          </div>

          <div class="button-demo">
            <h3>Désactivé</h3>
            <PillButton color="yellow" to="/test" disabled>Bouton désactivé</PillButton>
          </div>
        </div>
      </section>

      <!-- Section combinaisons -->
      <section class="demo-section demo-section--light">
        <h2 class="section-title">Combinaisons de boutons</h2>

        <div class="button-row">
          <PillButton color="yellow" to="/primary">Action principale</PillButton>
          <PillButton color="white" to="/secondary">Action secondaire</PillButton>
        </div>

        <div class="button-row" style="margin-top: 2rem;">
          <PillButton color="pink" to="/cta">Offre spéciale</PillButton>
          <PillButton color="white" to="/info">Plus d'infos</PillButton>
        </div>
      </section>

      <!-- Outline variants -->
      <section class="demo-section demo-section--dark">
        <h2 class="section-title">Outline</h2>

        <div class="button-group">
          <div class="button-demo">
            <h3>Outline light</h3>
            <PillButton variant="outline" to="/test">Outline clair</PillButton>
          </div>

          <div class="button-demo">
            <h3>Outline dark</h3>
            <PillButton variant="outline" color="dark" to="/test">Outline sombre</PillButton>
          </div>
        </div>
      </section>

      <!-- Régénérer -->
      <section class="demo-section demo-section--light">
        <h2 class="section-title">Régénérer les bordures</h2>
        <p class="demo-info">Les bordures sont générées aléatoirement au chargement de la page. Rechargez la page pour voir de nouvelles variations.</p>
        <button class="regenerate-btn" @click="regenerate">🎲 Recharger la page</button>
      </section>

      <!-- Retour -->
      <div class="back-link">
        <NuxtLink to="/">← Retour à l'accueil</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Démo Boutons Pilule - Eat Is Family',
  meta: [
    { name: 'description', content: 'Démonstration des composants de boutons pilule avec bordures irrégulières' }
  ]
})

const regenerate = () => {
  window.location.reload()
}
</script>

<style scoped lang="scss">
.demo-page {
  min-height: 100vh;
  background: #f5f5f5;
  padding: 2rem;
}

.demo-container {
  max-width: 1200px;
  margin: 0 auto;
}

.demo-title {
  font-size: 3rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 0.5rem;
  color: #1a1a1a;
}

.demo-subtitle {
  text-align: center;
  color: #666;
  font-size: 1.2rem;
  margin-bottom: 3rem;
}

.demo-section {
  padding: 3rem;
  border-radius: 1.5rem;
  margin-bottom: 2rem;
}

.demo-section--light {
  background: #ffffff;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.demo-section--dark {
  background: #1a1a2e;

  .section-title,
  h3,
  .color-code {
    color: #fff;
  }

  .color-code {
    color: #aaa;
  }
}

.demo-section--green {
  background: #b8e986;

  .section-title,
  h3 {
    color: #1a1a1a;
  }
}

.section-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 2rem;
  color: #333;
}

.button-group {
  display: flex;
  flex-wrap: wrap;
  gap: 3rem;
  justify-content: center;
}

.button-demo {
  text-align: center;

  h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
  }

  .color-code {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 1rem;
    font-family: monospace;
  }
}

.button-row {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex-wrap: wrap;
}

.demo-info {
  text-align: center;
  color: #666;
  margin-bottom: 1.5rem;
}

.regenerate-btn {
  display: block;
  margin: 0 auto;
  padding: 1rem 2rem;
  font-size: 1.1rem;
  font-weight: bold;
  background: #333;
  color: #fff;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    background: #555;
    transform: translateY(-2px);
  }
}

.back-link {
  text-align: center;
  margin-top: 3rem;

  a {
    color: #333;
    text-decoration: none;
    font-weight: 500;

    &:hover {
      text-decoration: underline;
    }
  }
}

@media (max-width: 768px) {
  .demo-page {
    padding: 1rem;
  }

  .demo-title {
    font-size: 2rem;
  }

  .demo-section {
    padding: 1.5rem;
  }

  .button-group {
    gap: 2rem;
  }
}
</style>
