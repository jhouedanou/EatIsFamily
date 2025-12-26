<script setup lang="ts">
import { LucideArrowRight } from 'lucide-vue-next'

const articles = [
  {
    title: 'The Future Of Stadium Catering: 5 Trends Reshaping The Industry',
    excerpt: 'From sustainable packaging to tech-enabled ordering, discover how big venues are changing the game.',
    image: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 3 hours ago',
    tag: 'Trends'
  },
  {
    title: 'Behind The Scenes: Feeding 50,000 Fans At Stade de France',
    excerpt: 'An exclusive look at the logistics, passion, and teamwork required to pull off a major matchday service.',
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 1 day ago',
    tag: 'Case Study'
  },
  {
    title: 'Meet The Chef: Sarah Jenkins On Elevating VIP Hospitality',
    excerpt: 'Our Head Chef discusses her inspiration and the challenge of creating fine dining experiences in a stadium setting.',
    image: 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 2 days ago',
    tag: 'People'
  },
  {
    title: 'Sustainability In Food Service: Our Commitment To The Planet',
    excerpt: 'How Eat is Family is reducing waste and sourcing locally to create a greener future for events.',
    image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 4 days ago',
    tag: 'Sustainability'
  },
  {
    title: 'Top 10 Street Food Concepts Taking Over Corporate Events',
    excerpt: 'Why street food stations are becoming the most popular choice for business gatherings.',
    image: 'https://images.unsplash.com/photo-1565123409695-7b5ef63a2efb?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 1 week ago',
    tag: 'Menu Ideas'
  },
  {
    title: 'The Art Of Plating: Visual Storytelling On A Plate',
    excerpt: 'Visual appeal is just as important as taste. Learn how our chefs design dishes that wow.',
    image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1000&auto=format&fit=crop',
    date: 'Posted 1 week ago',
    tag: 'Culinary Arts'
  }
]
</script>

<template>
  <div class="bg-brand-gray min-h-screen pb-24">
    <section class="bg-white py-20 border-b-2 border-black">
       <div class="container mx-auto px-4 text-center">
          <SectionHeader title="Our Blog" subtitle="Stories about food, events, and the people who make it happen." centered />
       </div>
    </section>

    <div class="container mx-auto px-4 mt-16">
       <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article v-for="(article, index) in articles" :key="index" class="group bg-white rounded-3xl overflow-hidden border-2 border-black shadow-hand hover:shadow-hand-hover transition-all duration-300 hover:-translate-y-2 relative">
             <div class="h-64 overflow-hidden relative border-b-2 border-black">
                <NuxtImg :src="article.image" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" />
                <span class="absolute top-4 right-4 bg-brand-yellow text-brand-black text-xs font-bold px-3 py-1 rounded-full border border-black shadow-sm">
                   {{ article.tag }}
                </span>
             </div>
             
             <div class="p-8">
                <p class="text-gray-500 text-xs font-bold mb-3 uppercase tracking-wider">{{ article.date }}</p>
                <h3 class="text-2xl font-serif font-bold mb-3 leading-tight group-hover:text-brand-rose transition-colors">
                   <a href="#" class="focus:outline-none">
                      <span class="absolute inset-0 z-10"></span>
                      {{ article.title }}
                   </a>
                </h3>
                <p class="text-gray-600 mb-6 line-clamp-3">
                   {{ article.excerpt }}
                </p>
                
                <div class="flex items-center text-brand-rose font-bold group-hover:translate-x-2 transition-transform duration-300">
                   Read Article <LucideArrowRight class="w-5 h-5 ml-2" />
                </div>
             </div>
          </article>
       </div>
       
       <div class="mt-16 text-center">
          <BaseButton variant="outline" size="lg">Load More Articles</BaseButton>
       </div>
    </div>
  </div>
</template>
