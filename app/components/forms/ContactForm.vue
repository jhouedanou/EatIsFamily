<template>
  <form @submit.prevent="handleSubmit" class="contact-form">
    <div class="form-group">
      <label for="name">Name *</label>
      <input 
        v-model="formData.name" 
        type="text" 
        id="name" 
        required 
        placeholder="Your name"
      />
    </div>

    <div class="form-group">
      <label for="email">Email *</label>
      <input 
        v-model="formData.email" 
        type="email" 
        id="email" 
        required 
        placeholder="your@email.com"
      />
    </div>

    <div class="form-group">
      <label for="subject">Subject *</label>
      <input 
        v-model="formData.subject" 
        type="text" 
        id="subject" 
        required 
        placeholder="How can we help?"
      />
    </div>

    <div class="form-group">
      <label for="message">Message *</label>
      <textarea 
        v-model="formData.message" 
        id="message" 
        rows="6"
        required
        placeholder="Your message..."
      ></textarea>
    </div>

    <button type="submit" class="btn-submit" :disabled="isSubmitting">
      {{ isSubmitting ? 'Sending...' : 'Send Message' }}
    </button>

    <p v-if="submitMessage" :class="['submit-message', submitStatus]">
      {{ submitMessage }}
    </p>
  </form>
</template>

<script setup lang="ts">
const formData = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)
const submitMessage = ref('')
const submitStatus = ref('')

const handleSubmit = async () => {
  isSubmitting.value = true
  submitMessage.value = ''
  
  // Simulate API call
  setTimeout(() => {
    isSubmitting.value = false
    submitStatus.value = 'success'
    submitMessage.value = 'Message sent successfully! We\'ll get back to you soon.'
    
    // Reset form
    formData.value = {
      name: '',
      email: '',
      subject: '',
      message: ''
    }
  }, 1500)
}
</script>

<style scoped>
.contact-form {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #4a5568;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
}

.btn-submit {
  width: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 1rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.submit-message {
  margin-top: 1rem;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
}

.submit-message.success {
  background: #c6f6d5;
  color: #22543d;
}

.submit-message.error {
  background: #fed7d7;
  color: #742a2a;
}
</style>
