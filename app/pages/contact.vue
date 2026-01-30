<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LucideX } from 'lucide-vue-next'

const router = useRouter()
const { getContactContent } = usePageContent()

const contactContent = ref<any>(null)

const form = ref({
  name: '',
  email: '',
  eventType: '',
  location: '',
  date: '',
  guests: '',
  message: ''
})

const isSubmitting = ref(false)
const submitSuccess = ref(false)

const goBack = () => {
  router.back()
}

const submitForm = async () => {
  isSubmitting.value = true
  await new Promise(resolve => setTimeout(resolve, 1500))
  isSubmitting.value = false
  submitSuccess.value = true
  form.value = {
    name: '',
    email: '',
    eventType: '',
    location: '',
    date: '',
    guests: '',
    message: ''
  }
}

onMounted(async () => {
  contactContent.value = await getContactContent()
})

useHead(() => ({
  title: 'Contact Us - Eat Is Family',
  meta: [
    { name: 'description', content: contactContent.value?.hero_section?.description || 'Get in touch with Eat Is Family. Share your vision and we\'ll bring it to life.' }
  ]
}))
</script>

<template>
  <div class="contact-page">
    <!-- Loading Screen -->
    <LoadingScreen v-if="!contactContent" />
    
    <template v-else>
    <!-- Decorative squiggle -->
    <div class="squiggle-decoration">
      <svg width="120" height="80" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 35C25 15 35 55 50 35C65 15 75 55 90 35" stroke="#1a1a1a" stroke-width="3" stroke-linecap="round" fill="none"/>
        <path d="M85 30L95 45L105 25" stroke="#1a1a1a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      </svg>
    </div>

    <!-- Close Button -->
    <button class="close-btn" @click="goBack" aria-label="Close">
      <LucideX :size="24" />
    </button>

    <!-- Decorative line under squiggle -->
    <div class="top-line"></div>

    <!-- Main Content -->
    <div class="contact-content">
      <!-- Left: Title and Subtitle -->
      <div class="contact-header">
        <h1 class="contact-title">
          <template v-if="contactContent?.hero_section?.title">
            {{ contactContent.hero_section.title.line_1 }} <span class="highlight">{{ contactContent.hero_section.title.highlight || 'Out' }}</span> {{ contactContent.hero_section.title.line_2 }}<br/>
            {{ contactContent.hero_section.title.line_3 }}
          </template>
          <template v-else>
            Reach <span class="highlight">Out</span> Let's Create<br/>
            Something Amazing
          </template>
        </h1>
        <p class="contact-subtitle">
          {{ contactContent?.hero_section?.description || 'Ready to elevate your event with exceptional catering? Share your vision and we\'ll bring it to life.' }}
        </p>
      </div>

      <!-- Right: Oval Image -->
      <div class="contact-image">
        <div class="oval-image">
          <img 
            :src="contactContent?.hero_section?.image?.src || 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=600&auto=format&fit=crop'" 
            :alt="contactContent?.hero_section?.image?.alt || 'Delicious catering food'" 
          />
        </div>
      </div>
    </div>

    <!-- Form Section -->
    <div class="form-container">
      <form v-if="!submitSuccess" @submit.prevent="submitForm" class="contact-form">
        <!-- Row 1: Name & Email -->
        <div class="form-row">
          <div class="form-field">
            <input
              v-model="form.name"
              type="text"
              :placeholder="contactContent?.form?.name_placeholder || 'Enter Name'"
              required
            />
          </div>
          <div class="form-field">
            <input
              v-model="form.email"
              type="email"
              :placeholder="contactContent?.form?.email_placeholder || 'Enter Email Address'"
              required
            />
          </div>
        </div>

        <!-- Row 2: Event Type & Location -->
        <div class="form-row">
          <div class="form-field">
            <input
              v-model="form.eventType"
              type="text"
              :placeholder="contactContent?.form?.event_type_placeholder || 'Event Type'"
            />
          </div>
          <div class="form-field">
            <input
              v-model="form.location"
              type="text"
              :placeholder="contactContent?.form?.location_placeholder || 'Enter Location'"
            />
          </div>
        </div>

        <!-- Row 3: Date & Guests -->
        <div class="form-row">
          <div class="form-field">
            <input
              v-model="form.date"
              type="text"
              :placeholder="contactContent?.form?.date_placeholder || 'Date of Event'"
              onfocus="(this.type='date')"
              onblur="if(!this.value)(this.type='text')"
            />
          </div>
          <div class="form-field">
            <input
              v-model="form.guests"
              type="text"
              :placeholder="contactContent?.form?.guests_placeholder || 'Number of Guests'"
            />
          </div>
        </div>

        <!-- Row 4: Message -->
        <div class="form-row full-width">
          <div class="form-field textarea-field">
            <textarea
              v-model="form.message"
              :placeholder="contactContent?.form?.message_placeholder || 'Message'"
              rows="5"
              required
            ></textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="form-row full-width">
          <button type="submit" class="submit-btn" :disabled="isSubmitting">
            <span v-if="isSubmitting">Sending...</span>
            <span v-else>{{ contactContent?.form?.submit_button || 'Send Message' }}</span>
          </button>
        </div>
      </form>

      <!-- Success Message -->
      <div v-else class="success-message">
        <div class="success-icon">✓</div>
        <h3>Message Sent!</h3>
        <p>Thank you for reaching out. We'll get back to you within 24-48 hours.</p>
        <button @click="submitSuccess = false" class="reset-btn">Send Another Message</button>
      </div>
    </div>
    </template>
  </div>
</template>

<style scoped lang="scss">
.contact-page {
  min-height: 100vh;
  background-color: #b8e986;
  padding: 2rem 3rem 4rem;
  position: relative;
}

// Squiggle decoration
.squiggle-decoration {
  position: absolute;
  top: 1.5rem;
  left: 2rem;
  z-index: 10;
}

// Top decorative line
.top-line {
  position: absolute;
  top: 5.5rem;
  left: 0;
  right: 0;
  height: 2px;
  background-color: #1a1a1a;
}

// Close button
.close-btn {
  position: absolute;
  top: 1.5rem;
  right: 2rem;
  width: 48px;
  height: 48px;
  background: #FFDD00;
  border: 2px solid #1a1a1a;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 20;

  &:hover {
    transform: scale(1.05);
    box-shadow: 2px 2px 0 #1a1a1a;
  }
}

// Main content area
.contact-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding-top: 5rem;
  max-width: 1200px;
  margin: 0 auto;
  gap: 2rem;
}

// Header section
.contact-header {
  flex: 1;
  max-width: 650px;
}

.contact-title {
  font-family: var(--font-heading, 'Recoleta', serif);
  font-size: clamp(2.5rem, 5vw, 3.75rem);
  font-weight: 700;
  line-height: 1.1;
  color: #1a1a1a;
  margin: 0 0 1.5rem;

  .highlight {
    position: relative;
    display: inline;

    &::after {
      content: '';
      position: absolute;
      bottom: 0.1em;
      left: -0.1em;
      right: -0.1em;
      height: 0.35em;
      background: #a0c4ff;
      z-index: -1;
      transform: rotate(-1deg);
    }
  }
}

.contact-subtitle {
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
  font-size: 1rem;
  color: #1a1a1a;
  line-height: 1.6;
  max-width: 500px;
}

// Oval image
.contact-image {
  flex-shrink: 0;
}

.oval-image {
  width: 280px;
  height: 200px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #1a1a1a;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

// Form container
.form-container {
  max-width: 1000px;
  margin: 3rem auto 0;
  padding: 2rem;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.25rem;

  &.full-width {
    grid-template-columns: 1fr;
  }
}

.form-field {
  position: relative;

  input,
  textarea {
    width: 100%;
    padding: 1.125rem 1.25rem;
    font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
    font-size: 1rem;
    color: #1a1a1a;
    background: transparent;
    border: 2px solid #1a1a1a;
    border-radius: 20px;
    outline: none;
    transition: all 0.2s ease;

    // Organic hand-drawn border effect
    border-radius: 25px 15px 20px 18px / 18px 22px 15px 25px;

    &::placeholder {
      color: #1a1a1a;
      opacity: 0.7;
    }

    &:focus {
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.15);
    }
  }

  textarea {
    resize: none;
    min-height: 140px;
  }
}

// Submit button
.submit-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 1rem 3rem;
  background: #FF4D6D;
  color: white;
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
  font-size: 1.125rem;
  font-weight: 600;
  border: 2px solid #1a1a1a;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 1rem;

  &:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 #1a1a1a;
  }

  &:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }
}

// Success message
.success-message {
  text-align: center;
  padding: 3rem;
  background: rgba(255, 255, 255, 0.5);
  border-radius: 20px;
  border: 2px solid #1a1a1a;
}

.success-icon {
  width: 80px;
  height: 80px;
  background: #FFDD00;
  border: 2px solid #1a1a1a;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  margin: 0 auto 1.5rem;
}

.success-message h3 {
  font-family: var(--font-heading, 'Recoleta', serif);
  font-size: 1.75rem;
  margin: 0 0 0.75rem;
  color: #1a1a1a;
}

.success-message p {
  color: #1a1a1a;
  margin: 0 0 2rem;
}

.reset-btn {
  padding: 0.75rem 1.5rem;
  background: transparent;
  border: 2px solid #1a1a1a;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    background: #1a1a1a;
    color: white;
  }
}

// Responsive
@media (max-width: 900px) {
  .contact-page {
    padding: 1.5rem;
  }

  .contact-content {
    flex-direction: column;
    padding-top: 4rem;
  }

  .contact-image {
    order: -1;
    align-self: flex-end;
  }

  .oval-image {
    width: 200px;
    height: 140px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .form-container {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .squiggle-decoration {
    transform: scale(0.7);
    top: 1rem;
    left: 0.5rem;
  }

  .close-btn {
    width: 40px;
    height: 40px;
    top: 1rem;
    right: 1rem;
  }

  .top-line {
    top: 4.5rem;
  }

  .contact-title {
    font-size: 2rem;
  }

  .oval-image {
    width: 150px;
    height: 100px;
  }
}
</style>
