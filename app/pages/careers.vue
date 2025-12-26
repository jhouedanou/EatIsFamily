<script setup lang="ts">
import { ref, computed } from 'vue'
import { LucideSearch, LucideMapPin, LucideX, LucideChevronDown } from 'lucide-vue-next'

const venues = [
  {
    id: 'stade-jean-bouin',
    name: 'Stade Jean Bouin',
    location: 'Paris, France',
    lat: 48.8427,
    lng: 2.2536,
    openPositions: 6,
    image: 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 'parc-des-princes',
    name: 'Parc des Princes',
    location: 'Paris, France',
    lat: 48.8414,
    lng: 2.2530,
    openPositions: 4,
    image: 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 'stade-de-france',
    name: 'Stade de France',
    location: 'Saint-Denis, France',
    lat: 48.9244,
    lng: 2.3601,
    openPositions: 8,
    image: 'https://images.unsplash.com/photo-1577223625816-7546f13df25d?q=80&w=600&auto=format&fit=crop'
  }
]

const jobs = [
  {
    id: '1',
    title: 'Head Chef At VIP Hospitality',
    department: 'Culinary',
    type: 'Part time',
    salary: '$150 - $200 / Hour',
    description: 'Lead our VIP culinary team delivering exceptional fine dining experiences for luxury boxes and corporate hospitality suites during major sporting events and concerts.',
    postedTime: '3 hours ago',
    venueId: 'stade-jean-bouin'
  },
  {
    id: '2',
    title: 'Concession Sales Associate',
    department: 'Operations',
    type: 'Part time',
    salary: '$50 - $80 / Hour',
    description: 'Serve fans at concession stands, handle transactions, and provide friendly customer service at our stadium locations.',
    postedTime: '3 hours ago',
    venueId: 'stade-jean-bouin'
  },
  {
    id: '3',
    title: 'Event Coordinator',
    department: 'Events',
    type: 'Full time',
    salary: '$200 - $300 / Day',
    description: 'Coordinate logistics for large-scale events, managing vendors, schedules, and on-site troubleshooting.',
    postedTime: '5 hours ago',
    venueId: 'parc-des-princes'
  },
  {
    id: '4',
    title: 'Sous Chef',
    department: 'Culinary',
    type: 'Full time',
    salary: '$120 - $160 / Hour',
    description: 'Assist the Head Chef in kitchen management, menu planning, and ensuring food quality standards are met.',
    postedTime: '1 day ago',
    venueId: 'stade-de-france'
  }
]

const searchQuery = ref('')
const selectedJobType = ref('All job types')
const selectedVenue = ref('stade-jean-bouin')
const showFilters = ref(false)

const jobTypes = ['All job types', 'Full time', 'Part time', 'Contract', 'Freelance']

const currentVenue = computed(() => venues.find(v => v.id === selectedVenue.value) || venues[0])

const filteredJobs = computed(() => {
  return jobs.filter(job => {
    const matchesSearch = job.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          job.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesType = selectedJobType.value === 'All job types' || job.type === selectedJobType.value
    const matchesVenue = job.venueId === selectedVenue.value
    return matchesSearch && matchesType && matchesVenue
  })
})

const selectVenue = (venueId: string) => {
  selectedVenue.value = venueId
}
</script>

<template>
  <div class="min-h-screen bg-brand-gray">
    <!-- Hero Section with Map -->
    <section class="relative h-[500px] md:h-[600px]">
      <!-- Interactive Map Background -->
      <div class="absolute inset-0">
        <ClientOnly>
          <VenueMap 
            :venues="venues" 
            :selected-venue="selectedVenue"
            @select-venue="selectVenue"
          />
          <template #fallback>
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
              <span class="text-gray-500">Loading map...</span>
            </div>
          </template>
        </ClientOnly>
      </div>

      <!-- Gradient Overlay -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-brand-gray pointer-events-none"></div>

      <!-- Close Button -->
      <button class="absolute top-6 right-6 z-20 w-12 h-12 bg-brand-yellow rounded-full flex items-center justify-center border-2 border-black hover:scale-110 transition-transform shadow-organic">
        <LucideX class="w-5 h-5" />
      </button>

      <!-- Venue Info Card -->
      <div class="absolute bottom-8 left-4 right-4 md:left-8 md:right-auto md:max-w-md z-10">
        <div class="bg-white border-organic p-6 shadow-organic">
          <span class="tag-lime text-xs mb-3 inline-block">NOW HIRING</span>
          
          <h1 class="font-heading text-3xl md:text-4xl font-bold leading-tight mb-3">
            Join Our Team At {{ currentVenue.name }}
          </h1>
          
          <div class="flex items-center gap-4 text-gray-600">
            <div class="flex items-center gap-2">
              <LucideMapPin class="w-4 h-4 text-brand-pink" />
              <span class="text-sm">{{ currentVenue.location }}</span>
            </div>
            <span class="w-1.5 h-1.5 rounded-full bg-brand-lime"></span>
            <span class="text-sm font-bold">{{ currentVenue.openPositions }} Open Positions</span>
          </div>
        </div>
      </div>

      <!-- Venue Pills -->
      <div class="absolute top-6 left-4 md:left-8 z-20 flex gap-2 flex-wrap max-w-[60%]">
        <button 
          v-for="venue in venues" 
          :key="venue.id"
          @click="selectVenue(venue.id)"
          :class="[
            'px-4 py-2 text-sm font-bold border-2 border-black transition-all',
            selectedVenue === venue.id 
              ? 'bg-brand-pink text-white shadow-organic-sm' 
              : 'bg-white hover:bg-brand-lime'
          ]"
          style="border-radius: 50px 10px 45px 10px / 10px 45px 10px 50px;"
        >
          {{ venue.name }}
        </button>
      </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="container mx-auto px-4 -mt-6 relative z-20">
      <div class="bg-brand-dark border-organic p-4 flex flex-col md:flex-row gap-4 shadow-organic">
        <!-- Search Input -->
        <div class="flex-1 relative">
          <div class="flex items-center gap-3 px-4">
            <LucideSearch class="w-5 h-5 text-white/70" />
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Search job title and category..." 
              class="bg-transparent text-white placeholder:text-white/50 outline-none flex-1 py-3 font-body"
            />
          </div>
        </div>
        
        <!-- Job Type Dropdown -->
        <div class="relative">
          <button 
            @click="showFilters = !showFilters"
            class="bg-brand-dark border-l border-white/20 px-6 py-3 flex items-center gap-3 text-white hover:bg-white/5 transition-colors w-full md:w-auto justify-between"
          >
            <span>{{ selectedJobType }}</span>
            <LucideChevronDown class="w-4 h-4" :class="{ 'rotate-180': showFilters }" />
          </button>
          
          <!-- Dropdown Menu -->
          <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <div v-if="showFilters" class="absolute top-full right-0 mt-2 w-48 bg-white border-organic shadow-organic-lg z-30">
              <button 
                v-for="type in jobTypes" 
                :key="type"
                @click="selectedJobType = type; showFilters = false"
                :class="[
                  'w-full text-left px-4 py-3 hover:bg-brand-lime/30 transition-colors font-medium',
                  selectedJobType === type ? 'bg-brand-lime/50' : ''
                ]"
              >
                {{ type }}
              </button>
            </div>
          </Transition>
        </div>
      </div>
    </section>

    <!-- Job Grid -->
    <section class="container mx-auto px-4 py-12">
      <div class="flex items-center justify-between mb-8">
        <h2 class="font-heading text-2xl font-bold">
          {{ filteredJobs.length }} {{ filteredJobs.length === 1 ? 'Position' : 'Positions' }} Available
        </h2>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <div 
          v-for="job in filteredJobs" 
          :key="job.id"
          class="bg-white border-organic p-6 hover:shadow-organic transition-all duration-300 group"
        >
          <div class="flex flex-col h-full justify-between">
            <!-- Header -->
            <div>
              <h3 class="font-heading font-bold text-xl leading-tight mb-1">{{ job.title }}</h3>
              <p class="text-xs text-gray-500 font-medium mb-4">Posted {{ job.postedTime }}</p>

              <!-- Tags Row -->
              <div class="flex flex-wrap gap-2 mb-4">
                <span class="tag-blue">Department · {{ job.department }}</span>
                <span class="tag-lime flex items-center gap-1">
                  <span class="text-sm">🌿</span> {{ job.type }}
                </span>
                <span class="tag-yellow flex items-center gap-1">
                  <span class="text-sm">💰</span> {{ job.salary }}
                </span>
              </div>

              <!-- Description -->
              <p class="text-sm text-gray-600 mb-6 line-clamp-3 font-body leading-relaxed">
                {{ job.description }}
              </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-auto">
              <NuxtLink :to="`/jobs/${job.id}`" class="btn-primary flex-1 text-center text-sm">
                Apply Now
              </NuxtLink>
              <NuxtLink :to="`/jobs/${job.id}`" class="btn-secondary flex-1 text-center text-sm">
                View details
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>

      <!-- No Results -->
      <div v-if="filteredJobs.length === 0" class="text-center py-20 bg-white border-organic">
        <p class="text-xl text-gray-500 mb-2">No jobs found at this venue</p>
        <p class="text-gray-400 mb-4">Try selecting a different venue or adjusting your filters</p>
        <button @click="searchQuery = ''; selectedJobType = 'All job types'" class="text-brand-pink font-bold hover:underline">
          Clear all filters
        </button>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-brand-dark py-16 text-center">
      <div class="container mx-auto px-4">
        <h2 class="font-heading text-3xl md:text-5xl font-bold text-white mb-6">
          Don't See Your Perfect Role?
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto mb-8 font-body">
          We are always looking for talented individuals to join our team. Discover and explore positions at other venues or submit a general application.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button class="btn-lime text-lg px-8 py-4">
            Explore all venues
          </button>
          <button class="btn-secondary text-lg px-8 py-4">
            Submit general application
          </button>
        </div>
      </div>
    </section>
  </div>
</template>
