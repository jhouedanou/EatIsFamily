<script setup lang="ts">
import { LucideMapPin, LucideClock, LucideBanknote } from 'lucide-vue-next'

defineProps({
  title: {
    type: String,
    required: true
  },
  department: {
    type: String,
    required: true
  },
  type: {
    type: String,
    required: true
  },
  salary: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  link: {
    type: String,
    default: '#'
  },
  postedTime: {
    type: String,
    default: 'Just now'
  }
})
</script>

<template>
  <div class="card-organic group">
    <div class="flex flex-col h-full justify-between">
      <!-- Header -->
      <div>
        <h3 class="font-heading font-bold text-xl leading-tight mb-1">{{ title }}</h3>
        <p class="text-xs text-gray-500 font-medium mb-4">Posted {{ postedTime }}</p>

        <!-- Tags Row -->
        <div class="flex flex-wrap gap-2 mb-4">
          <!-- Department Tag (Blue) -->
          <span class="tag-blue">
            {{ department }}
          </span>
          
          <!-- Type Tag (Green/Lime) -->
          <span class="tag-lime flex items-center gap-1">
            <span class="text-lg">🌿</span> {{ type }}
          </span>
          
          <!-- Salary Tag (Yellow) -->
          <span class="tag-yellow flex items-center gap-1">
            <span class="text-lg">💰</span> {{ salary }}
          </span>
        </div>

        <!-- Description -->
        <p class="text-sm text-gray-600 mb-6 line-clamp-3 font-body leading-relaxed">
          {{ description }}
        </p>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 mt-auto">
        <NuxtLink :to="link" class="btn-primary flex-1 text-center text-sm">
          Apply Now
        </NuxtLink>
        <NuxtLink :to="link" class="btn-secondary flex-1 text-center text-sm">
          View details
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
