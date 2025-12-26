<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { LucideSearch, LucideMapPin, LucideX } from 'lucide-vue-next'

const props = defineProps({
  locations: {
    type: Array as () => Array<{
      id: number | string
      name: string
      address: string
      lat: number
      lng: number
      jobs?: number
    }>,
    default: () => [
      { id: 1, name: 'Stade Jean Bouin', address: 'Paris, France', lat: 48.8423, lng: 2.2536, jobs: 6 },
      { id: 2, name: 'Accor Arena', address: 'Paris, France', lat: 48.8387, lng: 2.3789, jobs: 4 },
      { id: 3, name: 'Parc des Princes', address: 'Paris, France', lat: 48.8414, lng: 2.2530, jobs: 8 },
      { id: 4, name: 'Stade de France', address: 'Saint-Denis, France', lat: 48.9244, lng: 2.3601, jobs: 12 },
    ]
  },
  height: {
    type: String,
    default: '600px'
  }
})

const emit = defineEmits(['locationSelect'])

const searchQuery = ref('')
const selectedLocation = ref<any>(null)
const mapReady = ref(false)
const center = ref<[number, number]>([48.8566, 2.3522]) // Paris center
const zoom = ref(11)

// CartoDB Positron - Clean, light tiles perfect for the design
const tileUrl = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'
const tileAttribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'

const filteredLocations = computed(() => {
  if (!searchQuery.value) return props.locations
  return props.locations.filter(loc => 
    loc.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    loc.address.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const selectLocation = (location: any) => {
  selectedLocation.value = location
  center.value = [location.lat, location.lng]
  zoom.value = 14
  emit('locationSelect', location)
}

const closePanel = () => {
  selectedLocation.value = null
  zoom.value = 11
}

onMounted(() => {
  mapReady.value = true
})
</script>

<template>
  <div class="relative w-full overflow-hidden rounded-3xl border-2 border-brand-dark" :style="{ height }">
    <!-- Search Bar Overlay -->
    <div class="absolute top-4 left-4 right-4 z-[1000] flex gap-4">
      <div class="flex-1 relative">
        <div class="bg-brand-dark border-organic flex items-center px-4 py-3 gap-3">
          <LucideSearch class="w-5 h-5 text-white/70" />
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search job title and category here" 
            class="bg-transparent text-white placeholder:text-white/50 outline-none flex-1 font-body"
          />
        </div>
      </div>
      
      <div class="bg-brand-dark border-organic px-4 py-3 flex items-center gap-2 cursor-pointer hover:bg-brand-dark/90 transition-colors">
        <span class="text-white font-medium">All job types</span>
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>

    <!-- Map -->
    <div v-if="mapReady" class="w-full h-full">
      <LMap 
        :zoom="zoom" 
        :center="center"
        :use-global-leaflet="false"
        class="w-full h-full z-0"
      >
        <LTileLayer
          :url="tileUrl"
          :attribution="tileAttribution"
        />
        
        <!-- Custom Markers -->
        <LMarker 
          v-for="location in filteredLocations" 
          :key="location.id"
          :lat-lng="[location.lat, location.lng]"
          @click="selectLocation(location)"
        >
          <LIcon :icon-size="[32, 32]" :icon-anchor="[16, 32]">
            <div class="custom-marker flex flex-col items-center">
              <div 
                :class="[
                  'w-8 h-8 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-xs font-bold transition-all duration-300',
                  selectedLocation?.id === location.id ? 'bg-brand-yellow scale-125' : 'bg-brand-pink'
                ]"
              >
                {{ location.jobs || '•' }}
              </div>
              <div class="w-0 h-0 border-l-4 border-r-4 border-t-8 border-l-transparent border-r-transparent"
                   :class="selectedLocation?.id === location.id ? 'border-t-brand-yellow' : 'border-t-brand-pink'">
              </div>
            </div>
          </LIcon>
        </LMarker>
      </LMap>
    </div>

    <!-- Location Panel (when selected) -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-full opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform translate-y-full opacity-0"
    >
      <div v-if="selectedLocation" class="absolute bottom-4 left-4 right-4 z-[1000]">
        <div class="bg-white border-organic p-6 relative">
          <button 
            @click="closePanel" 
            class="absolute top-4 right-4 w-8 h-8 bg-brand-yellow rounded-full flex items-center justify-center border-2 border-black hover:scale-110 transition-transform"
          >
            <LucideX class="w-4 h-4" />
          </button>
          
          <div class="flex items-start gap-3 mb-4">
            <div class="w-8 h-8 bg-brand-pink rounded-full flex items-center justify-center">
              <LucideMapPin class="w-4 h-4 text-white" />
            </div>
            <div>
              <h3 class="font-heading text-xl font-bold">{{ selectedLocation.name }}</h3>
              <p class="text-gray-500 text-sm">{{ selectedLocation.address }}</p>
            </div>
          </div>
          
          <div class="flex items-center gap-4">
            <span class="text-brand-lime font-bold">•</span>
            <span class="text-sm font-medium">{{ selectedLocation.jobs }} Open Positions</span>
          </div>
          
          <div class="mt-4 flex gap-3">
            <button class="btn-primary flex-1">View All Jobs</button>
            <button class="btn-secondary">Get Directions</button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>
