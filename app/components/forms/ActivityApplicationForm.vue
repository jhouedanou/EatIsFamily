<template>
  <form @submit.prevent="handleSubmit" class="application-form">
    <h3>Register for this Activity</h3>
    
    <div class="form-group">
      <label for="name">Full Name *</label>
      <input 
        v-model="formData.name" 
        type="text" 
        id="name" 
        required 
        placeholder="John Doe"
      />
    </div>

    <div class="form-group">
      <label for="email">Email *</label>
      <input 
        v-model="formData.email" 
        type="email" 
        id="email" 
        required 
        placeholder="john@example.com"
      />
    </div>

    <div class="form-group">
      <label for="phone">Phone Number *</label>
      <input 
        v-model="formData.phone" 
        type="tel" 
        id="phone" 
        required 
        placeholder="+33 6 12 34 56 78"
      />
    </div>

    <div class="form-group">
      <label for="participants">Number of Participants *</label>
      <input 
        v-model.number="formData.participants" 
        type="number" 
        id="participants" 
        min="1" 
        max="10" 
        required 
      />
    </div>

    <div class="form-group">
      <label for="dietaryRestrictions">Dietary Restrictions</label>
      <textarea 
        v-model="formData.dietaryRestrictions" 
        id="dietaryRestrictions" 
        rows="3"
        placeholder="Any allergies or dietary preferences..."
      ></textarea>
    </div>

    <div class="form-group">
      <label for="message">Additional Information</label>
      <textarea 
        v-model="formData.message" 
        id="message" 
        rows="4"
        placeholder="Any questions or special requests..."
      ></textarea>
    </div>

    <button type="submit" class="btn-submit" :disabled="isSubmitting">
      {{ isSubmitting ? 'Submitting...' : 'Register Now' }}
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
  phone: '',
  participants: 1,
  dietaryRestrictions: '',
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
    submitMessage.value = 'Registration successful! Check your email for confirmation.'
    
    // Reset form
    formData.value = {
      name: '',
      email: '',
      phone: '',
      participants: 1,
      dietaryRestrictions: '',
      message: ''
    }
  }, 1500)
}
</script>

<style scoped>
.application-form {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.application-form h3 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #2d3748;
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
