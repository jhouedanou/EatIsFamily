<template>
  <div class="legal-page">
    <div class="container py-5">
      <h1 class="font-heading display-4 fw-bold mb-4">Conditions Générales d'Utilisation</h1>
      <p class="text-muted mb-4">Dernière mise à jour : Janvier 2025</p>

      <section class="mb-5">
        <h2 class="h3 mb-3">1. Acceptation des Conditions</h2>
        <p>
          En accédant et en utilisant le site web Eat Is Family, vous acceptez d'être lié par ces Conditions Générales d'Utilisation. 
          Si vous n'êtes pas d'accord avec une partie de ces conditions, veuillez ne pas utiliser notre site web.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">2. Utilisation du Site</h2>
        <p>Vous vous engagez à utiliser notre site uniquement à des fins légales et de manière à ne pas :</p>
        <ul>
          <li>Porter atteinte aux droits d'autrui</li>
          <li>Restreindre ou empêcher l'utilisation du site par quiconque</li>
          <li>Introduire des virus ou autres logiciels malveillants</li>
          <li>Tenter un accès non autorisé à nos systèmes</li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">3. Candidatures</h2>
        <p>Lors de la soumission de candidatures via notre site :</p>
        <ul>
          <li>Vous devez fournir des informations exactes et véridiques</li>
          <li>Vous confirmez que tous les documents soumis sont authentiques</li>
          <li>Vous nous autorisez à vérifier les informations fournies</li>
          <li>La soumission d'une candidature ne garantit pas un emploi</li>
        </ul>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">4. Propriété Intellectuelle</h2>
        <p>
          Tout le contenu de ce site, y compris mais sans s'y limiter, les textes, graphiques, logos, images et logiciels, 
          est la propriété d'Eat Is Family et est protégé par les lois sur la propriété intellectuelle. Vous ne pouvez pas reproduire, 
          distribuer ou créer des œuvres dérivées sans notre consentement écrit.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">5. Liens vers des Sites Tiers</h2>
        <p>
          Notre site peut contenir des liens vers des sites externes. Nous ne sommes pas responsables du contenu, 
          des pratiques de confidentialité ou de la disponibilité de ces sites tiers.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">6. Limitation de Responsabilité</h2>
        <p>
          Dans les limites permises par la loi, Eat Is Family ne sera pas responsable des dommages directs, indirects, 
          accessoires ou consécutifs résultant de votre utilisation de ce site.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">7. Modifications</h2>
        <p>
          Nous nous réservons le droit de modifier ces Conditions Générales à tout moment. Les modifications prendront effet immédiatement 
          après leur publication sur cette page. Votre utilisation continue du site constitue une acceptation des conditions modifiées.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">8. Droit Applicable</h2>
        <p>
          Ces Conditions Générales sont régies et interprétées conformément aux lois françaises. 
          Tout litige sera soumis à la compétence exclusive des tribunaux français.
        </p>
      </section>

      <section class="mb-5">
        <h2 class="h3 mb-3">9. Contact</h2>
        <p>
          Pour toute question concernant ces Conditions Générales, veuillez nous contacter à :
        </p>
        <address>
          <strong>Eat Is Family</strong><br>
          Email : <a href="mailto:legal@eatisfamily.com">legal@eatisfamily.com</a>
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
  title: 'Conditions Générales d\'Utilisation - Eat Is Family',
  meta: [
    { name: 'description', content: 'Conditions Générales d\'Utilisation du site web et des services Eat Is Family.' }
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
