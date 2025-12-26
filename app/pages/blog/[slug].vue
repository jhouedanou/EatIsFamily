<script setup lang="ts">
import { LucideArrowRight, LucideX } from 'lucide-vue-next'

const article = {
  title: 'The Future Of Stadium Catering: 5 Trends Reshaping The Industry',
  date: 'Posted 3 hours ago',
  image: 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=1200&auto=format&fit=crop',
  content: `
    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>
    
    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>
    
    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>
    
    <p>The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible. The stadium food experience has evolved dramatically over the past decade. Gone are the days when a basic hot dog and warm beer were the only options. Today's fans expect quality, variety, and convenience — and technology is making it all possible.</p>
  `
}
</script>

<template>
  <div class="min-h-screen bg-white">
    <!-- Close Button -->
    <button class="fixed top-6 right-6 z-50 w-12 h-12 bg-brand-yellow rounded-full flex items-center justify-center border-2 border-black hover:scale-110 transition-transform shadow-organic">
      <LucideX class="w-6 h-6" />
    </button>

    <article class="container mx-auto px-4 py-12 max-w-4xl">
      <!-- Header -->
      <header class="mb-8">
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
          <span class="relative inline">
            {{ article.title.split(':')[0] }}:
          </span>
          <span class="relative inline-block">
            {{ article.title.split(':')[1] }}
            <span class="absolute -bottom-2 left-0 w-full h-4 bg-brand-yellow -z-10 transform -rotate-1"></span>
          </span>
        </h1>
        <p class="text-gray-500 font-body">{{ article.date }}</p>
      </header>

      <!-- Featured Image -->
      <div class="mb-12 border-organic overflow-hidden">
        <NuxtImg 
          :src="article.image" 
          class="w-full h-auto object-cover"
          alt="Article featured image"
        />
      </div>

      <!-- Content -->
      <div class="prose prose-lg max-w-none font-body text-gray-700 leading-relaxed space-y-6" v-html="article.content">
      </div>

      <!-- Share / Navigation -->
      <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
        <NuxtLink to="/blog" class="text-brand-pink font-bold hover:underline flex items-center gap-2">
          ← Back to all articles
        </NuxtLink>
        
        <div class="flex gap-4">
          <button class="btn-secondary text-sm">Share Article</button>
          <button class="btn-primary text-sm flex items-center gap-2">
            Next Article <LucideArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>
    </article>
  </div>
</template>
