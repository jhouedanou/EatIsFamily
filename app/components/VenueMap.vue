<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'

const props = defineProps<{
  venues: Array<{
    id: string
    name: string
    location: string
    lat: number
    lng: number
    openPositions: number
    image?: string
  }>
  selectedVenue?: string
}>()

const emit = defineEmits(['select-venue'])

const mapContainer = ref<HTMLElement | null>(null)
let map: any = null
let markers: any[] = []

onMounted(async () => {
  if (typeof window !== 'undefined') {
    // Dynamically import Leaflet
    const L = await import('leaflet')
    await import('leaflet/dist/leaflet.css')
    
    if (mapContainer.value) {
      // Initialize map centered on Paris
      map = L.map(mapContainer.value, {
        zoomControl: false,
        scrollWheelZoom: false
      }).setView([48.8566, 2.3522], 11)
      
      // Add custom map tiles (CartoDB Positron for clean look)
      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
      }).addTo(map)
      
      // Add zoom control to bottom right
      L.control.zoom({
        position: 'bottomright'
      }).addTo(map)
      
      // Add markers for each venue
      props.venues.forEach((venue) => {
        const customIcon = L.divIcon({
          className: 'custom-map-marker',
          html: `
            <div class="marker-ping"></div>
            <div class="marker-dot"></div>
          `,
          iconSize: [24, 24],
          iconAnchor: [12, 12]
        })
        
        const marker = L.marker([venue.lat, venue.lng], { icon: customIcon })
          .addTo(map)
          .on('click', () => {
            emit('select-venue', venue.id)
          })
        
        // Add popup
        marker.bindPopup(`
          <div class="venue-popup">
            <strong>${venue.name}</strong>
            <p>${venue.openPositions} open positions</p>
          </div>
        `)
        
        markers.push({ id: venue.id, marker })
      })
    }
  }
})

// Watch for venue selection changes
watch(() => props.selectedVenue, (newId) => {
  if (map && newId) {
    const venue = props.venues.find(v => v.id === newId)
    if (venue) {
      map.flyTo([venue.lat, venue.lng], 14, { duration: 0.5 })
    }
  }
})
</script>

<template>
  <div class="relative w-full h-full">
    <div ref="mapContainer" class="w-full h-full rounded-3xl overflow-hidden"></div>
    
    <!-- Map Overlay Gradient -->
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/20 to-transparent rounded-3xl"></div>
  </div>
</template>

<style>
.custom-map-marker {
  position: relative;
}

.marker-dot {
  width: 16px;
  height: 16px;
  background-color: #FF4D6D;
  border: 3px solid white;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.marker-ping {
  width: 24px;
  height: 24px;
  background-color: rgba(255, 77, 109, 0.3);
  border-radius: 50%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
  75%, 100% {
    transform: translate(-50%, -50%) scale(2);
    opacity: 0;
  }
}

.venue-popup {
  font-family: 'Plus Jakarta Sans', sans-serif;
  padding: 4px;
}

.venue-popup strong {
  font-family: 'Recoleta', Georgia, serif;
  font-size: 14px;
  display: block;
  margin-bottom: 2px;
}

.venue-popup p {
  font-size: 12px;
  color: #666;
  margin: 0;
}

/* Leaflet popup customization */
.leaflet-popup-content-wrapper {
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.leaflet-popup-tip {
  background: white;
}
</style>
