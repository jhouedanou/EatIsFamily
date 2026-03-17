<template>
  <form v-if="content" @submit.prevent="handleSubmit" class="contact-form">
    <div class="form-group">
      <label for="name">{{ content.name_label }}</label>
      <input
        v-model="formData.name"
        type="text"
        id="name"
        required
        :placeholder="content.name_placeholder"
      />
    </div>

    <div class="form-group">
      <label for="email">{{ content.email_label }}</label>
      <input
        v-model="formData.email"
        type="email"
        id="email"
        required
        :placeholder="content.email_placeholder"
      />
    </div>

    <div class="form-group">
      <label for="subject">{{ content.subject_label }}</label>
      <input
        v-model="formData.subject"
        type="text"
        id="subject"
        required
        :placeholder="content.subject_placeholder"
      />
    </div>

    <div class="form-group">
      <label for="message">{{ content.message_label }}</label>
      <textarea
        v-model="formData.message"
        id="message"
        rows="6"
        required
        :placeholder="content.message_placeholder"
      ></textarea>
    </div>

    <button type="submit" class="btn-submit" :disabled="isSubmitting">
      {{ isSubmitting ? content.submitting_button : content.submit_button }}
    </button>

    <p v-if="submitMessage" :class="['submit-message', submitStatus]">
      {{ submitMessage }}
    </p>
  </form>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const { getFormsContent } = usePageContent()
const content = ref<any>(null)

const formData = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)
const submitMessage = ref('')
const submitStatus = ref('')
const { trackFormSubmit, trackFormStart } = useAnalytics()

onMounted(async () => {
  const formsContent = await getFormsContent()
  content.value = formsContent?.contact_form || null
  trackFormStart('contact')
})

const handleSubmit = async () => {
  isSubmitting.value = true
  submitMessage.value = ''

  // Simulate API call
  setTimeout(() => {
    isSubmitting.value = false
    submitStatus.value = 'success'
    submitMessage.value = content.value?.success_message || 'Message sent successfully!'
    trackFormSubmit('contact', { subject: formData.value.subject })

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

<style scoped lang="scss">
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

  &:focus {
    outline: none;
    border-color: #FF4D6D;
  }
}

.btn-submit {
  width: 100%;
  background: linear-gradient(135deg, #FF4D6D 0%, #e63956 100%);
  color: white;
  padding: 1rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 77, 109, 0.4);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
}

.submit-message {
  margin-top: 1rem;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;

  &.success {
    background: #c6f6d5;
    color: #22543d;
  }

  &.error {
    background: #fed7d7;
    color: #742a2a;
  }
}
</style>
