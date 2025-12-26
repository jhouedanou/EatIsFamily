<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-section">
          <h3>Eat Is Friday</h3>
          <p v-if="content">{{ content.footer.about_text }}</p>
        </div>
        
        <div class="footer-section">
          <h4>Quick Links</h4>
          <ul v-if="content">
            <li v-for="link in content.footer.quick_links" :key="link.link">
              <NuxtLink :to="link.link">{{ link.text }}</NuxtLink>
            </li>
          </ul>
        </div>
        
        <div class="footer-section">
          <h4>Contact</h4>
          <ul v-if="content">
            <li>{{ content.contact.info.address }}</li>
            <li>{{ content.contact.info.phone }}</li>
            <li>{{ content.contact.info.email }}</li>
          </ul>
        </div>
        
        <div class="footer-section">
          <h4>Follow Us</h4>
          <div class="social-links" v-if="content">
            <a :href="content.contact.social.facebook" target="_blank" rel="noopener">Facebook</a>
            <a :href="content.contact.social.instagram" target="_blank" rel="noopener">Instagram</a>
            <a :href="content.contact.social.twitter" target="_blank" rel="noopener">Twitter</a>
          </div>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p v-if="content">{{ content.footer.copyright }}</p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const { getSiteContent } = useSiteContent()
const content = ref<any>(null)

onMounted(async () => {
  content.value = await getSiteContent()
})
</script>

<style scoped>
.footer {
  background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
  color: white;
  padding: 3rem 0 1rem;
  margin-top: 4rem;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h3 {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.footer-section h4 {
  font-size: 1.125rem;
  margin-bottom: 1rem;
  color: #a0aec0;
}

.footer-section p {
  line-height: 1.6;
  color: #cbd5e0;
}

.footer-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-section li {
  margin-bottom: 0.5rem;
  color: #cbd5e0;
}

.footer-section a {
  color: #cbd5e0;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-section a:hover {
  color: #667eea;
}

.social-links {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 1.5rem;
  text-align: center;
  color: #a0aec0;
}

@media (max-width: 768px) {
  .footer-grid {
    grid-template-columns: 1fr;
  }
}
</style>
