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
                <label for="apply-resume">Votre CV *</label>
                <div class="file-upload-zone">
                  <label v-if="!form.resumeName" class="file-drop-label" for="apply-resume">
                    <LucideUpload class="upload-icon" />
                    <span class="upload-main-text">Cliquez pour sélectionner votre CV</span>
                    <span class="upload-sub-text">PDF, DOC, DOCX — max 2MB</span>
                    <input
                      type="file"
                      id="apply-resume"
                      accept=".pdf,.doc,.docx"
                      @change="handleResumeChange"
                      class="file-input-hidden"
                    />
                  </label>
                  <div v-else class="file-selected-display">
                    <span class="file-info">📄 {{ form.resumeName }}</span>
                    <button type="button" class="file-remove-btn" @click="removeResume">✕</button>
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
              <div class="form-group honeypot-field" aria-hidden="true" style="opacity:0;position:absolute;top:0;left:0;height:0;width:0;z-index:-1;overflow:hidden;">
                <label for="apply-fax-number">Fax</label>
                <input
                  v-model="form.website"
                  type="text"
                  id="apply-fax-number"
                  name="fax_number"
                  autocomplete="nope"
                  tabindex="-1"
                  data-1p-ignore
                  data-lpignore="true"
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
const { submitJobApplication, validateForm } = useJobApplicationForm()
const { trackJobApplySuccess, trackFormSubmit } = useAnalytics()

const form = ref({
  name: '',
  email: '',
  phone: '',
  linkedin: '',
  resumeFile: null as File | null,
  resumeName: '',
  coverLetter: '',
  consent: false,
  website: '' // Honeypot field
})

const handleResumeChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.value.resumeFile = target.files[0]
    form.value.resumeName = target.files[0].name
  }
}

const removeResume = () => {
  form.value.resumeFile = null
  form.value.resumeName = ''
}

const isSubmitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref<string | null>(null)

const close = () => {
  emit('close')
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
    resumeFile: form.value.resumeFile,
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
    const response = await submitJobApplication(formData)

    console.log('[JobApplyModal] API response:', response)

    if (response.success) {
      isSubmitting.value = false
      submitSuccess.value = true
      trackJobApplySuccess(props.jobTitle, props.jobSlug)
      trackFormSubmit('job_apply_modal', { job_title: props.jobTitle, job_slug: props.jobSlug })
      console.log('[JobApplyModal] Application submitted successfully')
    } else {
      isSubmitting.value = false
      submitError.value = response.message || 'Une erreur est survenue. Veuillez réessayer.'
    }
  } catch (error: any) {
    isSubmitting.value = false

    if (error.data?.message) {
      submitError.value = error.data.message
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
      resumeFile: null,
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

/* File Upload Zone */
.file-upload-zone {
  width: 100%;
}

.file-drop-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 1.5rem;
  border: 2px dashed #D1D5DB;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.2s ease;
  background: #F9FAFB;

  &:hover {
    border-color: #FF4D6D;
    background: #FFF5F7;
  }

  .upload-icon {
    width: 2rem;
    height: 2rem;
    color: #9CA3AF;
  }

  .upload-main-text {
    font-weight: 600;
    font-size: 0.9rem;
    color: #374151;
  }

  .upload-sub-text {
    font-size: 0.75rem;
    color: #9CA3AF;
  }
}

.file-input-hidden {
  position: absolute;
  width: 0;
  height: 0;
  opacity: 0;
  pointer-events: none;
}

.file-selected-display {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.875rem 1rem;
  background: #F0FDF4;
  border: 1px solid #86EFAC;
  border-radius: 0.5rem;

  .file-info {
    font-size: 0.9rem;
    color: #166534;
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: calc(100% - 40px);
  }

  .file-remove-btn {
    background: none;
    border: none;
    font-size: 1rem;
    color: #DC2626;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease;

    &:hover {
      background: #FEE2E2;
    }
  }
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

/* Responsive adjustments */
@media (max-width: 640px) {
  .file-drop-label {
    padding: 1rem;
  }
}
</style>
