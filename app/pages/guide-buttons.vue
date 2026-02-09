<template>
  <div class="guide-page">
    <div class="guide-container">
      <h1 class="guide-title">Guide PillButton</h1>
      <p class="guide-subtitle">Composant unifié pour tous les boutons pilule</p>

      <!-- Props -->
      <section class="guide-section">
        <h2>Props</h2>
        <table class="props-table">
          <thead>
            <tr>
              <th>Prop</th>
              <th>Type</th>
              <th>Défaut</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>color</code></td>
              <td><code>'pink' | 'yellow' | 'white' | 'transparent' | 'fuchsia' | 'dark' | 'light'</code></td>
              <td><code>'pink'</code></td>
              <td>Couleur du bouton</td>
            </tr>
            <tr>
              <td><code>variant</code></td>
              <td><code>'filled' | 'outline'</code></td>
              <td><code>'filled'</code></td>
              <td>Plein (clip-path) ou contour (SVG)</td>
            </tr>
            <tr>
              <td><code>width</code></td>
              <td><code>string</code></td>
              <td>-</td>
              <td>Largeur personnalisée (ex: "300px")</td>
            </tr>
            <tr>
              <td><code>to</code></td>
              <td><code>string</code></td>
              <td>-</td>
              <td>URL de destination (NuxtLink)</td>
            </tr>
            <tr>
              <td><code>label</code></td>
              <td><code>string</code></td>
              <td>-</td>
              <td>Texte du bouton (ou via slot)</td>
            </tr>
            <tr>
              <td><code>disabled</code></td>
              <td><code>boolean</code></td>
              <td><code>false</code></td>
              <td>Desactive le bouton</td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Couleurs filled -->
      <section class="guide-section">
        <h2>Couleurs (variant="filled")</h2>
        <div class="demo-grid">
          <div v-for="c in filledColors" :key="c.name" class="demo-card" :class="c.bgClass">
            <code class="demo-code">&lt;PillButton color="{{ c.name }}" /&gt;</code>
            <PillButton :color="c.name" to="#" :label="c.label" />
            <span class="demo-hex">{{ c.hex }}</span>
          </div>
        </div>
      </section>

      <!-- Outline -->
      <section class="guide-section">
        <h2>Outline (variant="outline")</h2>
        <div class="demo-grid">
          <div class="demo-card demo-card--dark">
            <code class="demo-code">&lt;PillButton variant="outline" /&gt;</code>
            <PillButton variant="outline" to="#" label="Outline light" />
            <span class="demo-hex">stroke: #fff</span>
          </div>
          <div class="demo-card">
            <code class="demo-code">&lt;PillButton variant="outline" color="dark" /&gt;</code>
            <PillButton variant="outline" color="dark" to="#" label="Outline dark" />
            <span class="demo-hex">stroke: #000</span>
          </div>
        </div>
      </section>

      <!-- Width -->
      <section class="guide-section">
        <h2>Largeur personnalisee (width)</h2>
        <div class="demo-grid">
          <div class="demo-card">
            <code class="demo-code">defaut (max 250px)</code>
            <PillButton color="pink" to="#" label="Normal" />
          </div>
          <div class="demo-card">
            <code class="demo-code">width="350px"</code>
            <PillButton color="pink" to="#" label="Plus large" width="350px" />
          </div>
          <div class="demo-card">
            <code class="demo-code">width="180px"</code>
            <PillButton color="yellow" to="#" label="Petit" width="180px" />
          </div>
          <div class="demo-card">
            <code class="demo-code">width="400px"</code>
            <PillButton color="fuchsia" to="#" label="Tres large" width="400px" />
          </div>
        </div>
      </section>

      <!-- Disabled -->
      <section class="guide-section">
        <h2>Etats</h2>
        <div class="demo-grid">
          <div class="demo-card">
            <code class="demo-code">normal</code>
            <PillButton color="yellow" to="#" label="Actif" />
          </div>
          <div class="demo-card">
            <code class="demo-code">disabled</code>
            <PillButton color="yellow" to="#" label="Desactive" disabled />
          </div>
        </div>
      </section>

      <!-- Slot -->
      <section class="guide-section">
        <h2>Slot vs label</h2>
        <div class="demo-grid">
          <div class="demo-card">
            <code class="demo-code">label="Texte"</code>
            <PillButton color="white" to="#" label="Via label" />
          </div>
          <div class="demo-card">
            <code class="demo-code">&lt;PillButton&gt;Slot&lt;/PillButton&gt;</code>
            <PillButton color="white" to="#">Via slot</PillButton>
          </div>
        </div>
      </section>

      <!-- Combinaisons -->
      <section class="guide-section demo-section--dark">
        <h2>Combinaisons sur fond sombre</h2>
        <div class="button-row">
          <PillButton color="pink" to="#" label="Action" />
          <PillButton variant="outline" to="#" label="Secondaire" />
        </div>
        <div class="button-row" style="margin-top: 1.5rem;">
          <PillButton color="fuchsia" to="#" label="Fuchsia" />
          <PillButton color="transparent" to="#" label="Transparent" />
          <PillButton color="yellow" to="#" label="Yellow" />
        </div>
      </section>

      <div class="back-link">
        <NuxtLink to="/">Retour a l'accueil</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Guide PillButton - Eat Is Family',
})

const filledColors = [
  { name: 'pink' as const, label: 'Pink', hex: '#f9375b', bgClass: '' },
  { name: 'yellow' as const, label: 'Yellow', hex: '#FFE600', bgClass: '' },
  { name: 'white' as const, label: 'White', hex: '#ffffff', bgClass: '' },
  { name: 'fuchsia' as const, label: 'Fuchsia', hex: '#ff2e84', bgClass: '' },
  { name: 'transparent' as const, label: 'Transparent', hex: 'transparent', bgClass: 'demo-card--dark' },
]
</script>

<style scoped lang="scss">
.guide-page {
  min-height: 100vh;
  background: #f5f5f0;
  padding: 2rem;
  padding-top: 120px;
}

.guide-container {
  max-width: 1000px;
  margin: 0 auto;
}

.guide-title {
  font-size: 2.5rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 0.25rem;
  color: #1a1a1a;
}

.guide-subtitle {
  text-align: center;
  color: #666;
  margin-bottom: 3rem;
}

.guide-section {
  background: #fff;
  border-radius: 1rem;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);

  h2 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1a1a1a;
  }
}

.props-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;

  th, td {
    text-align: left;
    padding: 0.6rem 0.8rem;
    border-bottom: 1px solid #eee;
  }

  th {
    font-weight: 600;
    color: #333;
    background: #fafafa;
  }

  code {
    background: #f0f0f0;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
    font-size: 0.82rem;
  }
}

.demo-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.demo-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  padding: 1.5rem;
  border-radius: 0.75rem;
  background: #fafafa;
  min-width: 200px;
  flex: 1;
}

.demo-card--dark {
  background: #1a1a2e;

  .demo-code, .demo-hex {
    color: #ccc;
  }
}

.demo-section--dark {
  background: #1a1a2e !important;

  h2 {
    color: #fff;
  }
}

.demo-code {
  font-family: monospace;
  font-size: 0.78rem;
  color: #666;
  text-align: center;
}

.demo-hex {
  font-family: monospace;
  font-size: 0.75rem;
  color: #999;
}

.button-row {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.back-link {
  text-align: center;
  margin-top: 2rem;

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
  .guide-page {
    padding: 1rem;
    padding-top: 100px;
  }

  .guide-title {
    font-size: 1.8rem;
  }

  .demo-grid {
    flex-direction: column;
  }

  .props-table {
    font-size: 0.8rem;

    th, td {
      padding: 0.4rem;
    }
  }
}
</style>
