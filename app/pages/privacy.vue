<template>
  <div class="legal-page">
    <div class="container py-5">
      <h1 class="font-heading display-4 fw-bold mb-4">Politique de Confidentialité</h1>
      <p class="text-muted mb-4">Dernière mise à jour : Janvier 2025</p>

      <section class="mb-5">
        <h2 class="h3 mb-3">1. Introduction</h2>
        <p>
          Eat Is Family ("nous", "notre" ou "nos") s'engage à protéger vos données personnelles et à respecter votre vie privée. 
          Cette Politique de Confidentialité explique comment nous collectons, utilisons et protégeons vos informations lorsque vous visitez notre site web ou utilisez nos services.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">2. Informations que Nous Collectons</h2>
        <p>Nous pouvons collecter les types d'informations suivants :</p>
        <ul>
          <li><strong>Informations personnelles :</strong> Nom, adresse e-mail, numéro de téléphone et autres coordonnées que vous fournissez lors de candidatures ou en nous contactant.</li>
          <li><strong>Données d'utilisation :</strong> Informations sur la façon dont vous utilisez notre site web, y compris l'adresse IP, le type de navigateur, les pages visitées et le temps passé sur notre site.</li>
          <li><strong>Cookies :</strong> Petits fichiers de données stockés sur votre appareil pour améliorer votre expérience de navigation.</li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">3. Comment Nous Utilisons Vos Informations</h2>
        <p>Nous utilisons vos informations pour :</p>
        <ul>
          <li>Traiter les candidatures et communiquer avec les candidats</li>
          <li>Répondre à vos demandes et fournir un support client</li>
          <li>Améliorer notre site web et nos services</li>
          <li>Envoyer des mises à jour pertinentes sur les opportunités d'emploi (avec votre consentement)</li>
          <li>Respecter les obligations légales</li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">4. Partage des Données</h2>
        <p>
          Nous ne vendons pas vos données personnelles. Nous pouvons partager vos informations avec :
        </p>
        <ul>
          <li>Nos partenaires établissements pour les besoins de recrutement</li>
          <li>Les prestataires de services qui nous aident à exploiter notre site web</li>
          <li>Les autorités légales lorsque la loi l'exige</li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">5. Vos Droits</h2>
        <p>En vertu du RGPD, vous avez le droit de :</p>
        <ul>
          <li>Accéder à vos données personnelles</li>
          <li>Corriger les données inexactes</li>
          <li>Demander la suppression de vos données</li>
          <li>Vous opposer au traitement des données</li>
          <li>Portabilité des données</li>
        </ul>
        <p>Pour exercer ces droits, veuillez nous contacter à <a href="mailto:privacy@eatisfamily.com">privacy@eatisfamily.com</a>.</p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">6. Sécurité des Données</h2>
        <p>
          Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre l'accès non autorisé, 
          l'altération, la divulgation ou la destruction.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">7. Nous Contacter</h2>
        <p>
          Si vous avez des questions concernant cette Politique de Confidentialité, veuillez nous contacter à :
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
  title: 'Politique de Confidentialité - Eat Is Family',
  meta: [
    { name: 'description', content: 'Politique de Confidentialité d\'Eat Is Family. Découvrez comment nous collectons, utilisons et protégeons vos informations personnelles.' }
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

  p, li {
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
}
</style>
