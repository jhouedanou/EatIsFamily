<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="close">
    <div class="modal-container">
      <!-- Close Button -->
      <button class="modal-close" @click="close" aria-label="Fermer">
        <LucideX />
      </button>

      <!-- Modal Header -->
      <div class="modal-header">
        <div class="modal-icon">
          <LucideBriefcase />
        </div>
        <h2 class="modal-title">Postuler à cette offre</h2>
        <p class="modal-subtitle">{{ jobTitle }}</p>
        <span class="modal-location">
          <LucideMapPin style="width: 1rem; height: 1rem;" />
          {{ jobLocation }}
        </span>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form v-if="!submitSuccess" @submit.prevent="handleSubmit" class="apply-form">
          <div class="form-row">
            <div class="form-group">
              <label for="apply-name">Nom complet *</label>
              <input
                    v-model="form.name"
                    type="text"
                    id="apply-name"
                    placeholder="Entrez votre nom complet"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="apply-email">Adresse email *</label>
                  <input
                    v-model="form.email"
                    type="email"
                    id="apply-email"
                    placeholder="votre.email@exemple.com"
                    required
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="apply-phone">Numéro de téléphone *</label>
                  <input
                    v-model="form.phone"
                    type="tel"
                    id="apply-phone"
                    placeholder="+33 6 00 00 00 00"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="apply-linkedin">Profil LinkedIn</label>
                  <input
                    v-model="form.linkedin"
                    type="url"
                    id="apply-linkedin"
                    placeholder="https://linkedin.com/in/votreprofil"
                  />
                </div>
              </div>

              <div class="form-group">
                <label for="apply-resume">CV *</label>
                <div class="file-upload" :class="{ 'has-file': form.resumeName }">
                  <input
                    type="file"
                    id="apply-resume"
                    accept=".pdf,.doc,.docx"
                    required
                    @change="handleFileChange"
                  />
                  <div class="file-upload-content">
                    <LucideUpload />
                    <span v-if="form.resumeName">{{ form.resumeName }}</span>
                    <span v-else>
                      <strong>Cliquez pour télécharger</strong> ou glissez-déposez<br/>
                      PDF, DOC, DOCX (max 5 Mo)
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="apply-cover">Lettre de motivation</label>
                <textarea
                  v-model="form.coverLetter"
                  id="apply-cover"
                  rows="4"
                  placeholder="Dites-nous pourquoi vous seriez parfait pour ce poste..."
                ></textarea>
              </div>

              <!-- Honeypot field (hidden from users, trap for bots) -->
              <div class="form-group honeypot-field" aria-hidden="true">
                <label for="apply-website">Site web</label>
                <input
                  v-model="form.website"
                  type="text"
                  id="apply-website"
                  name="website"
                  autocomplete="off"
                  tabindex="-1"
                />
              </div>

              <div class="form-group checkbox-group">
                <label class="checkbox-label">
                  <input
                    v-model="form.consent"
                    type="checkbox"
                    required
                  />
                  <span class="checkbox-text">
                    J'accepte le traitement de mes données personnelles conformément à la <a href="/privacy" target="_blank">Politique de confidentialité</a> *
                  </span>
                </label>
              </div>

              <!-- Error Message Display -->
              <div v-if="submitError" class="error-message">
                <LucideAlertCircle style="width: 1.25rem; height: 1.25rem;" />
                <span>{{ submitError }}</span>
              </div>

              <button type="submit" class="btn-submit" :disabled="isSubmitting">
                <span v-if="isSubmitting" class="spinner"></span>
                <span v-else>
                  <LucideSend style="width: 1.25rem; height: 1.25rem;" />
                  Envoyer ma candidature
                </span>
              </button>
            </form>

            <!-- Success State -->
            <div v-else class="success-state">
              <div class="success-icon">
                <LucideCheck />
              </div>
              <h3>Candidature envoyée !</h3>
              <p>Merci d'avoir postulé pour le poste <strong>{{ jobTitle }}</strong>. Nous avons bien reçu votre candidature et l'examinerons rapidement.</p>
              <p class="success-note">Vous recevrez un email de confirmation à <strong>{{ form.email }}</strong></p>
              <button @click="close" class="btn-close-success">Fermer</button>
            </div>
          </div>
        </div>
      </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { LucideX, LucideBriefcase, LucideMapPin, LucideUpload, LucideSend, LucideCheck, LucideAlertCircle } from 'lucide-vue-next'

const props = defineProps<{
  isOpen: boolean
  jobTitle: string
  jobLocation: string
  jobSlug: string
}>()

console.log('========== JobApplyModal COMPONENT LOADED ==========')
console.log('Initial isOpen:', props.isOpen)
console.log('jobTitle:', props.jobTitle)

watch(() => props.isOpen, (newVal, oldVal) => {
  console.log('>>> JobApplyModal isOpen CHANGED:', oldVal, '->', newVal)
  if (newVal) {
    console.log('MODAL SHOULD BE VISIBLE NOW!')
  }
}, { immediate: true })

const emit = defineEmits(['close'])

// Utiliser le composable CF7 pour les candidatures
const { submitJobApplication, validateForm, validateFile } = useJobApplicationForm()

const form = ref({
  name: '',
  email: '',
  phone: '',
  linkedin: '',
  resume: null as File | null,
  resumeName: '',
  coverLetter: '',
  consent: false,
  website: '' // Honeypot field
})

const isSubmitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref<string | null>(null)

const close = () => {
  emit('close')
}

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    
    // Valider le fichier
    const validation = validateFile(file)
    if (!validation.valid) {
      submitError.value = validation.error || 'Fichier invalide'
      return
    }
    
    form.value.resume = file
    form.value.resumeName = file.name
    submitError.value = null
  }
}

const handleSubmit = async () => {
  // Vérifier le honeypot (si rempli, c'est un bot)
  if (form.value.website) {
    console.log('[JobApplyModal] Honeypot detected, ignoring submission')
    submitSuccess.value = true // Fake success pour le bot
    return
  }
  
  isSubmitting.value = true
  submitError.value = null
  
  // Valider les données du formulaire
  const formData = {
    name: form.value.name,
    email: form.value.email,
    phone: form.value.phone,
    linkedin: form.value.linkedin,
    resume: form.value.resume,
    coverLetter: form.value.coverLetter,
    jobTitle: props.jobTitle,
    jobLocation: props.jobLocation,
    jobSlug: props.jobSlug,
    consent: form.value.consent
  }
  
  const validation = validateForm(formData)
  if (!validation.valid) {
    isSubmitting.value = false
    submitError.value = validation.errors.join('. ')
    return
  }
  
  try {
    // Soumettre via Contact Form 7 API
    const response = await submitJobApplication(formData)
    
    if (response.status === 'mail_sent') {
      isSubmitting.value = false
      submitSuccess.value = true
      console.log('[JobApplyModal] Application submitted successfully')
    } else if (response.status === 'validation_failed' && response.invalid_fields) {
      isSubmitting.value = false
      const fieldErrors = response.invalid_fields.map(f => f.message).join('. ')
      submitError.value = fieldErrors || response.message
    } else {
      isSubmitting.value = false
      submitError.value = response.message || 'Une erreur est survenue. Veuillez réessayer.'
    }
  } catch (error: any) {
    isSubmitting.value = false
    
    // Handle API errors
    if (error.data?.message) {
      submitError.value = error.data.message
    } else if (error.data?.errors && Array.isArray(error.data.errors)) {
      submitError.value = error.data.errors.join(', ')
    } else if (error.statusCode === 429) {
      submitError.value = 'Trop de candidatures envoyées. Veuillez réessayer plus tard.'
    } else {
      submitError.value = 'Une erreur est survenue. Veuillez réessayer.'
    }
    
    console.error('Application submission error:', error)
  }
}

// Reset form when modal opens
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    submitSuccess.value = false
    submitError.value = null
    form.value = {
      name: '',
      email: '',
      phone: '',
      linkedin: '',
      resume: null,
      resumeName: '',
      coverLetter: '',
      consent: false,
      website: ''
    }
  }
})

// Close on escape key
const handleEscape = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && props.isOpen) {
    close()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleEscape)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscape)
})

// Prevent body scroll when modal is open
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>

<style scoped lang="scss">
/* Honeypot field - hidden from users but visible to bots */
.honeypot-field {
  position: absolute;
  left: -9999px;
  opacity: 0;
  height: 0;
  width: 0;
  overflow: hidden;
  pointer-events: none;
}

/* Error message styling */
.error-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.875rem 1rem;
  background: #FEE2E2;
  border: 1px solid #F87171;
  border-radius: 0.5rem;
  color: #DC2626;
  font-size: 0.875rem;
  margin-bottom: 1rem;
  
  svg {
    flex-shrink: 0;
    color: #DC2626;
  }
}
</style>
