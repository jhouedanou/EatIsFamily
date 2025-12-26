<script setup lang="ts">
import { LucideMapPin } from 'lucide-vue-next'

defineProps({
  label: {
    type: String,
    required: true
  },
  top: {
    type: String,
    required: true
  },
  left: {
    type: String,
    required: true
  }
})
</script>

<template>
  <div 
    class="absolute transform -translate-x-1/2 -translate-y-full group cursor-pointer z-10"
    :style="{ top, left }"
  >
    <div class="relative flex flex-col items-center">
      <div class="bg-brand-rose text-white px-3 py-1 rounded-lg shadow-hand mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-bold whitespace-nowrap text-sm border-2 border-black">
        {{ label }}
      </div>
      <LucideMapPin class="w-8 h-8 text-brand-black fill-brand-yellow drop-shadow-md transition-transform duration-300 group-hover:-translate-y-1" />
    </div>
  </div>
</template>
