<template>
  <div class="about-page">
    <section v-if="siteContent" id="aboutHero" class="page-hero">
      <div class="container-fluid d-flex flex-column justify-content-center align-items-center h-100 mt-5">
        <h1 class="heroTitle">{{ siteContent.about.hero.title }}</h1>
        <div class="d-flex">
          <div class="row">
            <div class="col-5 d-flex flex-column">
              <div class="heroTextContainer d-flex flex-column">
                <h2 class="heroSubtitle">{{ siteContent.about.hero.subtitle }}</h2>
                <p class="heroDescription">{{ siteContent.about.hero.description }}</p>
                <nuxt-link to="/">
                  <nuxt-img :src="siteContent.about.hero.buttonContact" alt="Contact Us" class="contactButton img-fluid fluid-img" />
                  
                </nuxt-link>
              </div>
            </div>
            <div class="col-7">
              <img :src="siteContent.about.hero.image.src" :alt="siteContent.about.hero.image.alt"
                class="img-fluid heroImage" />
            </div>
          </div>
        </div>
      </div>

    </section>
    <section v-if="siteContent" id="timeline">
      <h3 class="timeline-title white-text recoleta preserve-lines">{{ siteContent.about.timeline.title }}</h3>

      <div class="timeline-wrapper">
        <!-- Sound Wave with Playhead -->
        <div ref="soundWaveContainer" class="sound-wave-container">
          <!-- Playhead (moves horizontally like a cursor) -->
          <div
            class="playhead"
            :class="{ 'dragging': isDragging }"
            :style="{ left: playheadX + '%' }"
            @mousedown="startDrag"
            @touchstart="startDrag"
          >
            <!-- Top square -->
            <div class="playhead-top-marker" :style="{ background: playheadColor }"></div>
            <!-- Vertical line -->
            <div class="playhead-line" :style="{ background: playheadColor }"></div>
            <!-- Circle on the wave line -->
            <div class="playhead-circle" :style="{ background: playheadColor }"></div>
            <!-- Year label at bottom -->
            <div class="playhead-year" :style="{ background: playheadColor }">{{ selectedEvent ? getYearFromDate(selectedEvent.year) : '2015' }}</div>

            <!-- Event Card (positioned relative to playhead) -->
            <div v-if="selectedEvent && isCardVisible" class="event-card" :class="{ 'event-card-left': isLastEvent }">
              <div class="event-card-content">
                <!-- Close button -->
                <button class="event-card-close" @click.stop="closeEventCard">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <div class="event-card-image">
                  <nuxt-img v-if="selectedEvent.eventImage" :src="selectedEvent.eventImage" :alt="selectedEvent.title" />
                </div>
                <div class="event-card-info">
                  <h4 class="event-card-title">{{ selectedEvent.title }}</h4>
                  <p class="event-card-date">{{ selectedEvent.year }}</p>
                  <p class="event-card-description">{{ selectedEvent.event }}</p>
                  <button class="share-button">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M12 5.5C13.1046 5.5 14 4.60457 14 3.5C14 2.39543 13.1046 1.5 12 1.5C10.8954 1.5 10 2.39543 10 3.5C10 4.60457 10.8954 5.5 12 5.5Z" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M4 10C5.10457 10 6 9.10457 6 8C6 6.89543 5.10457 6 4 6C2.89543 6 2 6.89543 2 8C2 9.10457 2.89543 10 4 10Z" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M12 14.5C13.1046 14.5 14 13.6046 14 12.5C14 11.3954 13.1046 10.5 12 10.5C10.8954 10.5 10 11.3954 10 12.5C10 13.6046 10.8954 14.5 12 14.5Z" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M5.7 9L10.3 11.5M10.3 4.5L5.7 7" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    Share This Story
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Sound wave bars -->
          <div class="sound-wave">
            <div v-for="i in 120" :key="i" class="wave-bar-wrapper">
              <div class="wave-bar wave-bar-top" :style="getWaveBarStyle(i - 1)"></div>
              <div class="wave-bar wave-bar-bottom" :style="getWaveBarStyle(i - 1)"></div>
            </div>
          </div>

          <!-- Event markers (dots on the center line) -->
          <div
            v-for="(event, index) in allEvents"
            :key="'marker-' + event.id"
            class="wave-marker"
            :class="{ active: selectedEvent?.id === event.id }"
            :style="{ left: getMarkerPosition(index as number) + '%' }"
            @click="selectEvent(event, index as number)"
          >
            <div class="wave-marker-dot" :style="{ background: getMarkerColor(index as number) }"></div>
          </div>

          <!-- Navigation arrows for prev/next event -->
          <button
            v-if="allEvents.length > 1"
            class="timeline-nav timeline-nav-prev"
            :class="{ disabled: !canGoPrev }"
            :disabled="!canGoPrev"
            @click="goToPrevEvent"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <button
            v-if="allEvents.length > 1"
            class="timeline-nav timeline-nav-next"
            :class="{ disabled: !canGoNext }"
            :disabled="!canGoNext"
            @click="goToNextEvent"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
      </div>

      <h4 class="timeline-subtitle white-text recoleta preserve-lines">{{ siteContent.about.timeline.subtitle }}</h4>
    </section>
    <section v-if="siteContent?.about?.mission" id="mission">
    <div class="container-fluid">
      <h3 class="font-heading preserve-lines">{{ siteContent.about.mission.title }}</h3>

    </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'

const { getSiteContent } = useSiteContent()
const { getContentByPath } = usePageContent()

const siteContent = ref<any>(null)
const pageContent = ref<any>(null)
const selectedEvent = ref<any>(null)
const playheadX = ref<number>(10) // Initial position at first event
const isLastEvent = ref<boolean>(false) // To position card on left for last event
const playheadColor = ref<string>('#FF6B6B') // Default playhead color (first event color)
const selectedEventIndex = ref<number>(0) // Track current event index
const isCardVisible = ref<boolean>(false) // Control card visibility

// Drag state
const isDragging = ref<boolean>(false)
const soundWaveContainer = ref<HTMLElement | null>(null)

// All events (no pagination)
const allEvents = computed(() => siteContent.value?.about?.timeline?.events || [])

// Navigation computed properties
const canGoNext = computed(() => selectedEventIndex.value < allEvents.value.length - 1)
const canGoPrev = computed(() => selectedEventIndex.value > 0)

onMounted(async () => {
  siteContent.value = await getSiteContent()
  pageContent.value = await getContentByPath('about')

  // Initialize playhead position to first event (but don't show card)
  if (siteContent.value?.about?.timeline?.events?.length > 0) {
    const firstEvent = siteContent.value.about.timeline.events[0]
    selectedEvent.value = firstEvent
    selectedEventIndex.value = 0
    playheadX.value = getMarkerPosition(0)
    playheadColor.value = getMarkerColor(0)
    isCardVisible.value = false // Don't show card initially
  }

  // Add global mouse event listeners for drag
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
  document.addEventListener('touchmove', handleTouchMove)
  document.addEventListener('touchend', handleTouchEnd)
})

onUnmounted(() => {
  // Clean up event listeners
  document.removeEventListener('mousemove', handleMouseMove)
  document.removeEventListener('mouseup', handleMouseUp)
  document.removeEventListener('touchmove', handleTouchMove)
  document.removeEventListener('touchend', handleTouchEnd)
})

// Extract year from date string like "13 JUNE 2015"
const getYearFromDate = (dateStr: string) => {
  const parts = dateStr.split(' ')
  return parts[parts.length - 1] || '2015'
}

// Get marker position as percentage (evenly distributed)
const getMarkerPosition = (index: number): number => {
  const count = allEvents.value.length
  if (count <= 1) return 50
  // Distribute markers from 8% to 92%
  return 8 + (84 / (count - 1)) * index
}

// Get wave bar height style - PYRAMID/TRIANGLE shape (linear, not curved)
const getWaveBarStyle = (barIndex: number) => {
  const totalBars = 120
  const numEvents = allEvents.value.length || 6

  // Calculate bar position as percentage (0-100)
  const barPosition = (barIndex / totalBars) * 100

  // Find the nearest marker and calculate distance to it
  let minDistance = Infinity
  for (let i = 0; i < numEvents; i++) {
    const markerPos = getMarkerPosition(i)
    const distance = Math.abs(barPosition - markerPos)
    if (distance < minDistance) {
      minDistance = distance
    }
  }

  // Max distance between markers
  const maxDistance = 84 / (numEvents - 1) / 2

  // LINEAR interpolation for pyramid shape (not cosine!)
  // Distance 0 = 100% height, Distance max = 5% height
  const normalizedDistance = Math.min(minDistance / maxDistance, 1)
  const heightPercent = 100 - (normalizedDistance * 95) // Linear from 100 to 5

  return {
    height: heightPercent + '%'
  }
}

// Get marker color based on index (matching reference image)
const getMarkerColor = (index: number): string => {
  const colors: string[] = ['#FFD700', '#4ECDC4', '#FF6B6B', '#FFD700', '#45B7D1', '#4ECDC4']
  return colors[index % colors.length] || '#FFD700'
}

// Select event and move playhead
const selectEvent = (event: any, index: number) => {
  selectedEvent.value = event
  selectedEventIndex.value = index
  // Move playhead to the marker position
  playheadX.value = getMarkerPosition(index)
  // Update playhead color based on index
  playheadColor.value = getMarkerColor(index)
  // Check if this is the last event (for card positioning)
  isLastEvent.value = index >= allEvents.value.length - 2
  // Show the card
  isCardVisible.value = true
}

// Close the event card
const closeEventCard = () => {
  isCardVisible.value = false
}

// Drag handlers
const startDrag = (e: MouseEvent | TouchEvent) => {
  e.preventDefault()
  isDragging.value = true
}

const handleMouseMove = (e: MouseEvent) => {
  if (!isDragging.value) return
  updatePlayheadPosition(e.clientX)
}

const handleTouchMove = (e: TouchEvent) => {
  if (!isDragging.value || !e.touches[0]) return
  updatePlayheadPosition(e.touches[0].clientX)
}

const handleMouseUp = () => {
  if (isDragging.value) {
    isDragging.value = false
    snapToNearestEvent()
  }
}

const handleTouchEnd = () => {
  if (isDragging.value) {
    isDragging.value = false
    snapToNearestEvent()
  }
}

const updatePlayheadPosition = (clientX: number) => {
  const container = soundWaveContainer.value
  if (!container) return

  const rect = container.getBoundingClientRect()
  const x = clientX - rect.left
  const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100))
  playheadX.value = percentage
}

const snapToNearestEvent = () => {
  const events = allEvents.value
  if (!events || events.length === 0) return

  // Find nearest event marker
  let nearestIndex = 0
  let nearestDistance = Infinity

  events.forEach((_event: any, index: number) => {
    const markerX = getMarkerPosition(index)
    const distance = Math.abs(playheadX.value - markerX)
    if (distance < nearestDistance) {
      nearestDistance = distance
      nearestIndex = index
    }
  })

  // Select the nearest event
  selectEvent(events[nearestIndex], nearestIndex)
}

// Navigation functions - navigate between individual events
const goToNextEvent = () => {
  if (canGoNext.value) {
    const nextIndex = selectedEventIndex.value + 1
    selectEvent(allEvents.value[nextIndex], nextIndex)
  }
}

const goToPrevEvent = () => {
  if (canGoPrev.value) {
    const prevIndex = selectedEventIndex.value - 1
    selectEvent(allEvents.value[prevIndex], prevIndex)
  }
}

useHead(() => ({
  title: pageContent.value?.seo?.title || 'About Us - Eat Is Family',
  meta: [
    { name: 'description', content: pageContent.value?.seo?.meta_description || 'Learn about Eat Is Family, our mission, vision, and the team behind our culinary experiences.' }
  ]
}))
</script>

<style scoped lang="scss">
#aboutHero {
  height: 930px;
  margin: 0 0 14px;
  padding: 22px 60.4px 56px 70px;
  border-radius: 2px;
  background: url(images/vectorBgAbout.svg) no-repeat center 20% #a7f49d !important;

  h1 {
    font-family: FONTSPRINGDEMO-RecoletaBold;
    font-size: 75px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.2;
    letter-spacing: normal;
    text-align: center;
    color: #000b0f;
  }
}

.content-section {
  margin-bottom: 3rem;

  h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2d3748;
  }

  p {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #4a5568;
  }
}

.values-section,
.team-section {
  margin-top: 4rem;

  h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    color: #2d3748;
  }
}

.value-card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

  h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #FF4D6D;
  }
}

.team-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;

  img {
    width: 100%;
    height: 250px;
    object-fit: cover;
  }

  h3 {
    font-size: 1.25rem;
    margin: 1rem 0 0.5rem;
    color: #2d3748;
  }

  .role {
    color: #FF4D6D;
    font-weight: 600;
    margin-bottom: 1rem;
  }

  p:last-child {
    padding: 0 1.5rem 1.5rem;
    color: #4a5568;
  }
}

/* Timeline Styles */
#timeline {
  padding: 80px 20px 120px;
  background: #1a1a1a;
  min-height: 100vh;
  position: relative;
  overflow: hidden;

  .timeline-title {
    font-size: 48px;
    text-align: center;
    margin-bottom: 60px;
    line-height: 1.3;
    color: white;
  }

  .timeline-subtitle {
    font-size: 20px;
    text-align: center;
    margin-top: 60px;
    opacity: 0.7;
    color: white;
  }
}

.timeline-wrapper {
  position: relative;
  width: 100%;
  max-width: 1600px;
  margin: 0 auto;
  padding: 0 40px;
}

/* Event Card - positioned relative to playhead */
.event-card {
  position: absolute;
  left: 30px; /* Right of the playhead line */
  top: 30px;
  display: flex;
  align-items: flex-start;
  gap: 0;
  z-index: 15;
  animation: fadeIn 0.4s ease-out;
}

/* When last event, position card to the left */
.event-card.event-card-left {
  left: auto;
  right: 30px;
  flex-direction: row-reverse;
}

.event-card-content {
  background: white;
  border-radius: 20px;
  padding: 20px;
  display: flex;
  gap: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  width: 420px;
  position: relative;
}

.event-card-close {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.1);
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  color: #333;
  z-index: 10;

  &:hover {
    background: rgba(0, 0, 0, 0.2);
    transform: scale(1.1);
  }

  svg {
    width: 16px;
    height: 16px;
  }
}

.event-card-decoration {
  height: 250px;
  width: auto;
  margin-left: -10px;
  opacity: 0.9;
}

.event-card-decoration.decoration-left {
  margin-left: 0;
  margin-right: -10px;
  transform: scaleX(-1);
}

.event-card-image {
  flex-shrink: 0;
  width: 160px;
  height: 180px;
  border-radius: 14px;
  overflow: hidden;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.event-card-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 8px 0;
}

.event-card-title {
  font-family: FONTSPRINGDEMO-RecoletaBold, serif;
  font-size: 22px;
  color: #000b0f;
  margin: 0 0 6px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.event-card-date {
  font-size: 12px;
  color: #FF2E84;
  font-weight: 700;
  margin: 0 0 12px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.event-card-description {
  font-size: 13px;
  line-height: 1.5;
  color: #333;
  margin: 0 0 14px 0;
  flex: 1;
}

.share-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: transparent;
  border: none;
  color: #FF2E84;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  transition: all 0.3s ease;

  &:hover {
    gap: 12px;
  }

  svg {
    width: 16px;
    height: 16px;
  }
}

/* Sound Wave Visualization */
.sound-wave-container {
  position: relative;
  width: 100%;
  height: 250px;
  margin-top: 320px;
}

.sound-wave {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
  width: 100%;
  gap: 2px;
  position: absolute;
  top: 0;
  left: 0;
  padding: 0 2%;
}

.wave-bar-wrapper {
  height: 100%;
  flex: 1;
  min-width: 3px;
  position: relative;
}

.wave-bar {
  width: 100%;
  max-width: 4px;
  background: #D6246E;
  border-radius: 2px;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.wave-bar-top {
  bottom: 50%;
}

.wave-bar-bottom {
  top: 50%;
}

/* Playhead - moves horizontally */
.playhead {
  position: absolute;
  top: -280px;
  transform: translateX(-50%);
  z-index: 30;
  transition: left 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: grab;
  user-select: none;
  pointer-events: auto;

  &.dragging {
    cursor: grabbing;
    transition: none;
  }
}

.playhead-top-marker {
  width: 12px;
  height: 12px;
}

.playhead-line {
  width: 3px;
  height: 380px;
}

.playhead-circle {
  display: none;
}

.playhead-year {
  font-family: FONTSPRINGDEMO-RecoletaMedium, sans-serif;
  font-size: 14px;
  font-weight: bold;
  padding: 8px 12px;
  color: #000;
  margin-top: 4px;
}


.wave-marker {
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  cursor: pointer;
  z-index: 25;
  transition: all 0.3s ease;

  &:hover {
    transform: translate(-50%, -50%) scale(1.3);
    z-index: 26;
  }

  &.active {
    z-index: 27;

    .wave-marker-dot {
      width: 18px;
      height: 18px;
      box-shadow: 0 0 15px currentColor;
    }
  }
}

.wave-marker-dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  border: none;
  transition: all 0.3s ease;
}

/* Timeline Navigation Arrows */
.timeline-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  border: 2px solid rgba(255, 255, 255, 0.3);
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  z-index: 25;

  &:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-50%) scale(1.1);
  }

  &.disabled {
    opacity: 0.3;
    cursor: not-allowed;
  }

  svg {
    width: 24px;
    height: 24px;
  }
}

.timeline-nav-prev {
  left: -60px;
}

.timeline-nav-next {
  right: -60px;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-50%) translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(-50%) translateX(0);
  }
}

/* Responsive */
@media (max-width: 1400px) {
  .event-card-content {
    width: 380px;
  }

  .event-card-image {
    width: 140px;
    height: 160px;
  }

  .event-card-decoration {
    height: 220px;
  }
}

@media (max-width: 1200px) {
  .event-card-content {
    width: 350px;
  }

  .event-card-image {
    width: 120px;
    height: 140px;
  }

  .event-card-decoration {
    display: none;
  }

  .sound-wave-container {
    height: 280px;
    margin-top: 280px;
  }

  .playhead {
    top: -240px;
  }

  .playhead-line {
    height: 240px;
  }

  .playhead-year {
    margin-top: 240px;
  }
}

@media (max-width: 992px) {
  .timeline-wrapper {
    padding: 0 20px;
  }

  .event-card {
    left: 50%;
    transform: translateX(-50%);
  }

  .event-card.event-card-left {
    right: auto;
    left: 50%;
    transform: translateX(-50%);
  }

  .event-card-content {
    width: 320px;
  }

  .sound-wave-container {
    height: 200px;
    margin-top: 320px;
  }

  .playhead {
    top: -280px;
  }

  .playhead-line {
    height: 280px;
  }

  .playhead-year {
    margin-top: 200px;
  }
}

@media (max-width: 992px) {
  .timeline-nav-prev {
    left: -10px;
  }

  .timeline-nav-next {
    right: -10px;
  }
}

@media (max-width: 768px) {
  #timeline {
    padding: 40px 15px 80px;

    .timeline-title {
      font-size: 28px;
      margin-bottom: 40px;
    }
  }

  .event-card-content {
    width: 280px;
    flex-direction: column;
    padding: 15px;
  }

  .event-card-image {
    width: 100%;
    height: 150px;
  }

  .event-card-title {
    font-size: 18px;
  }

  .event-card-description {
    font-size: 12px;
  }

  .sound-wave-container {
    height: 150px;
    margin-top: 300px;
  }

  .playhead {
    top: -260px;
  }

  .playhead-line {
    height: 260px;
  }

  .playhead-year {
    margin-top: 150px;
    font-size: 14px;
    padding: 8px 12px;
  }

  .timeline-nav {
    width: 36px;
    height: 36px;

    svg {
      width: 18px;
      height: 18px;
    }
  }

  .timeline-nav-prev {
    left: 5px;
  }

  .timeline-nav-next {
    right: 5px;
  }
}
</style>
