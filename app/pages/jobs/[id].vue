<script setup lang="ts">
import { LucideMapPin, LucideX, LucideHeart, LucideShare2, LucideChevronRight } from 'lucide-vue-next'

// In a real app, this would come from an API based on route params
const job = {
  title: 'Head Chef At VIP Hospitality',
  location: 'Stade Jean Bouin, Paris',
  department: 'Culinary',
  type: 'Part time',
  salary: '$50 - $200 / Hour',
  slots: '2 Slots',
  description: 'Lead our VIP culinary team delivering exceptional fine dining experiences for luxury boxes and corporate hospitality suites during major sporting events and concerts. Oversee family-friendly dining areas, create kid-friendly menus, and ensure welcoming atmosphere for all ages.',
  whatYouDo: [
    'Lead daily operations and ensure quality standards',
    'Collaborate with team members to deliver excellence',
    'Maintain safety and hygiene protocols',
    'Contribute to menu planning and innovation',
    'Train and mentor junior staff members'
  ],
  requirements: [
    'At least 5 years experience in high volume fine dining',
    'Culinary degree or equivalent certification',
    'Experience with event catering',
    'Ability to manage teams of 10-15 chefs',
    'Fluent in French and English'
  ],
  whyJoinUs: [
    'Work at one of France\'s most iconic venues',
    'Be part of major sporting and entertainment events',
    'Competitive compensation and benefits',
    'Career growth and professional development',
    'Dynamic and supportive team environment'
  ],
  images: [
    'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?q=80&w=600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1581299894007-aaa50297cf16?q=80&w=600&auto=format&fit=crop'
  ]
}
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="border-b border-gray-200 py-4">
      <div class="container mx-auto px-4 flex items-center justify-between">
        <div class="flex items-center gap-2 text-brand-pink">
          <LucideMapPin class="w-5 h-5" />
          <span class="font-medium">{{ job.location }}</span>
        </div>
        
        <button class="w-10 h-10 bg-brand-yellow rounded-full flex items-center justify-center border-2 border-black hover:scale-110 transition-transform">
          <LucideX class="w-5 h-5" />
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <!-- Title & Tags -->
      <div class="mb-8">
        <h1 class="font-heading text-4xl md:text-5xl font-bold mb-6">{{ job.title }}</h1>
        
        <div class="flex flex-wrap gap-3">
          <span class="tag-blue">Department · {{ job.department }}</span>
          <span class="tag-lime flex items-center gap-1">
            <span>🌿</span> {{ job.type }}
          </span>
          <span class="tag-yellow flex items-center gap-1">
            <span>💰</span> {{ job.salary }}
          </span>
        </div>
      </div>

      <!-- Description -->
      <p class="text-gray-600 text-lg mb-8 max-w-4xl font-body leading-relaxed">
        {{ job.description }}
      </p>

      <!-- CTA Banner -->
      <div class="bg-brand-blue border-organic p-6 mb-12 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
          <h3 class="font-heading text-xl font-bold mb-1">Ready To Join Our Team?</h3>
          <p class="text-gray-700 font-body">Apply now and be part of something special</p>
        </div>
        <button class="btn-primary whitespace-nowrap">
          Apply for this position
        </button>
      </div>

      <!-- Life at Location -->
      <section class="mb-12">
        <h2 class="font-heading text-2xl font-bold mb-6">Life at {{ job.location.split(',')[0] }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="(img, i) in job.images" :key="i" class="border-organic overflow-hidden aspect-video">
            <NuxtImg :src="img" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" />
          </div>
        </div>
      </section>

      <!-- Job Details Grid -->
      <section class="mb-12">
        <h2 class="font-heading text-2xl font-bold mb-6">Job Description And Requirement</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
          <!-- What You'll Do -->
          <div class="card-organic">
            <h3 class="font-heading text-lg font-bold mb-4 flex items-center gap-2">
              <span class="w-6 h-6 bg-brand-pink rounded-full flex items-center justify-center text-white text-xs">✓</span>
              What You'll Do
            </h3>
            <p class="text-gray-500 text-sm mb-4 font-body">You'll do the following:</p>
            <ul class="space-y-3">
              <li v-for="(item, i) in job.whatYouDo" :key="i" class="flex items-start gap-3 text-sm font-body">
                <LucideChevronRight class="w-4 h-4 text-brand-pink flex-shrink-0 mt-0.5" />
                {{ item }}
              </li>
            </ul>
          </div>

          <!-- Requirements -->
          <div class="card-organic bg-brand-lime">
            <h3 class="font-heading text-lg font-bold mb-4">What We're Looking For</h3>
            <p class="text-gray-700 text-sm mb-4 font-body">The following requirement is what we are looking for</p>
            <ul class="space-y-3">
              <li v-for="(item, i) in job.requirements" :key="i" class="flex items-start gap-3 text-sm font-body">
                <LucideChevronRight class="w-4 h-4 text-brand-dark flex-shrink-0 mt-0.5" />
                {{ item }}
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- Bottom Section -->
      <section class="grid md:grid-cols-3 gap-6 mb-12">
        <!-- Why Join Us -->
        <div class="bg-brand-pink text-white p-6 border-organic">
          <h3 class="font-heading text-lg font-bold mb-4 flex items-center gap-2">
            <LucideHeart class="w-5 h-5" /> Why Join Us
          </h3>
          <ul class="space-y-3">
            <li v-for="(item, i) in job.whyJoinUs" :key="i" class="flex items-start gap-3 text-sm font-body">
              <LucideChevronRight class="w-4 h-4 flex-shrink-0 mt-0.5" />
              {{ item }}
            </li>
          </ul>
        </div>

        <!-- Quick Facts -->
        <div class="card-organic">
          <h3 class="font-heading text-lg font-bold mb-4">Quick Facts</h3>
          <div class="space-y-4 text-sm">
            <div>
              <p class="text-gray-500 uppercase text-xs mb-1">Location</p>
              <p class="font-bold">{{ job.location }}</p>
            </div>
            <div>
              <p class="text-gray-500 uppercase text-xs mb-1">Department</p>
              <p class="font-bold">{{ job.department }}</p>
            </div>
            <div>
              <p class="text-gray-500 uppercase text-xs mb-1">Employment Type</p>
              <p class="font-bold">{{ job.type }}</p>
            </div>
            <div>
              <p class="text-gray-500 uppercase text-xs mb-1">Available Positions</p>
              <p class="font-bold text-brand-pink">{{ job.slots }}</p>
            </div>
          </div>
        </div>

        <!-- Share -->
        <div class="card-organic">
          <h3 class="font-heading text-lg font-bold mb-4">Do You Know Someone That Is Perfect For This Position?</h3>
          <p class="text-gray-600 text-sm mb-4 font-body">Kindly Share This Job To The Person</p>
          <button class="btn-secondary flex items-center gap-2 w-full justify-center">
            <LucideShare2 class="w-4 h-4" />
            Share this job
          </button>
        </div>
      </section>

      <!-- Final CTA -->
      <section class="bg-brand-yellow border-organic p-8 text-center">
        <h2 class="font-heading text-3xl md:text-4xl font-bold mb-4">Ready To Make An Impact?</h2>
        <p class="text-gray-700 max-w-2xl mx-auto mb-8 font-body">
          Join our team and be part of creating unforgettable experiences at one of France's most exciting venues.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button class="btn-lime">Apply for this position</button>
          <button class="btn-secondary">Go back to jobs</button>
        </div>
      </section>
    </main>
  </div>
</template>
