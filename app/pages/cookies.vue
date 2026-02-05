<template>
  <div class="legal-page">
    <div class="container py-5">
      <h1 class="font-heading display-4 fw-bold mb-4">Politique des Cookies</h1>
      <p class="text-muted mb-4">Dernière mise à jour : Janvier 2025</p>

      <section class="mb-5">
        <h2 class="h3 mb-3">1. Qu'est-ce qu'un Cookie ?</h2>
        <p>
          Les cookies sont de petits fichiers texte placés sur votre appareil lorsque vous visitez notre site web. 
          Ils nous aident à vous offrir une meilleure expérience en mémorisant vos préférences et en comprenant comment vous utilisez notre site.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">2. Types de Cookies que Nous Utilisons</h2>
        
        <h3 class="h5 mt-4 mb-2">Cookies Essentiels</h3>
        <p>
          Ces cookies sont nécessaires au bon fonctionnement du site. Ils permettent des fonctionnalités de base comme la navigation 
          et l'accès aux zones sécurisées. Le site ne peut pas fonctionner correctement sans ces cookies.
        </p>

        <h3 class="h5 mt-4 mb-2">Cookies Analytiques</h3>
        <p>
          Nous utilisons des cookies analytiques pour comprendre comment les visiteurs interagissent avec notre site. Cela nous aide à améliorer 
          les fonctionnalités et l'expérience utilisateur. Ces cookies collectent des informations de manière anonyme.
        </p>

        <h3 class="h5 mt-4 mb-2">Cookies Fonctionnels</h3>
        <p>
          Ces cookies permettent au site de mémoriser vos choix (comme votre préférence de langue) et de fournir 
          des fonctionnalités améliorées et plus personnalisées.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">3. Cookies que Nous Utilisons</h2>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Nom du Cookie</th>
                <th>Finalité</th>
                <th>Durée</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>_session</td>
                <td>Gestion de session</td>
                <td>Session</td>
              </tr>
              <tr>
                <td>_ga</td>
                <td>Google Analytics - suivi</td>
                <td>2 ans</td>
              </tr>
              <tr>
                <td>_gid</td>
                <td>Google Analytics - distinction des utilisateurs</td>
                <td>24 heures</td>
              </tr>
              <tr>
                <td>cookie_consent</td>
                <td>Stocke vos préférences de cookies</td>
                <td>1 an</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">4. Gérer les Cookies</h2>
        <p>
          Vous pouvez contrôler et gérer les cookies de différentes manières. La plupart des navigateurs vous permettent de :
        </p>
        <ul>
          <li>Voir quels cookies sont stockés et les supprimer individuellement</li>
          <li>Bloquer les cookies tiers</li>
          <li>Bloquer les cookies de sites spécifiques</li>
          <li>Bloquer tous les cookies</li>
          <li>Supprimer tous les cookies à la fermeture de votre navigateur</li>
        </ul>
        <p>
          Veuillez noter que le blocage de tous les cookies peut affecter votre expérience sur notre site et limiter certaines fonctionnalités.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">5. Paramètres du Navigateur</h2>
        <p>Voici comment gérer les cookies dans les navigateurs populaires :</p>
        <ul>
          <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
          <li><a href="https://support.mozilla.org/fr/kb/cookies-informations-sites-enregistrent" target="_blank" rel="noopener">Mozilla Firefox</a></li>
          <li><a href="https://support.apple.com/fr-fr/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Apple Safari</a></li>
          <li><a href="https://support.microsoft.com/fr-fr/help/17442/windows-internet-explorer-delete-manage-cookies" target="_blank" rel="noopener">Microsoft Edge</a></li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">6. Modifications de cette Politique</h2>
        <p>
          Nous pouvons mettre à jour cette Politique des Cookies de temps à autre. Toute modification sera publiée sur cette page avec une 
          date de "Dernière mise à jour" actualisée.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">7. Nous Contacter</h2>
        <p>
          Si vous avez des questions concernant notre utilisation des cookies, veuillez nous contacter à :
        </p>
        <address>
          <strong>Eat Is Family</strong><br>
          Email : <a href="mailto:privacy@eatisfamily.com">privacy@eatisfamily.com</a>
        </address>
      </section>

      <div class="mt-5">
        <NuxtLink to="/" class="btn btn-outline-dark">← Retour à l'accueil</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Politique des Cookies - Eat Is Family',
  meta: [
    { name: 'description', content: 'Politique des Cookies d\'Eat Is Family. Découvrez comment nous utilisons les cookies sur notre site.' }
  ]
})
</script>

<style scoped lang="scss">
.legal-page {
  background-color: #f8f9fa;
  min-height: 100vh;

  h1 {
    color: #1a1a1a;
  }

  h2 {
    color: #333;
  }

  h3 {
    color: #444;
  }

  p, li, td {
    line-height: 1.8;
    color: #444;
  }

  ul {
    padding-left: 1.5rem;
  }

  li {
    margin-bottom: 0.5rem;
  }

  a {
    color: #667eea;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }

  .table {
    th {
      font-weight: 600;
    }
  }
}
</style>
