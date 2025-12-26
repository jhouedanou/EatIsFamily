<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LucideChefHat, LucideUtensils, LucidePartyPopper, LucideMapPin, LucideArrowRight } from 'lucide-vue-next'
import gsap from 'gsap'

const heroTitle = ref(null)

const services = [
  { 
    title: 'Corporate Catering', 
    icon: LucideChefHat, 
    desc: 'Premium culinary experiences for your business events and meetings.',
    bg: 'bg-brand-pink',
    text: 'text-white'
  },
  { 
    title: 'Stadium Concessions', 
    icon: LucideUtensils, 
    desc: 'High-volume, high-quality food service for major arenas and venues.',
    bg: 'bg-brand-lime',
    text: 'text-brand-dark'
  },
  { 
    title: 'Private Events', 
    icon: LucidePartyPopper, 
    desc: 'Unforgettable moments crafted with passion and exceptional flavor.',
    bg: 'bg-brand-yellow',
    text: 'text-brand-dark'
  }
]

const locations = [
  { name: 'Paris', count: 12 },
  { name: 'Lyon', count: 8 },
  { name: 'Marseille', count: 6 },
  { name: 'Bordeaux', count: 4 }
]

const partners = [
  'LOGO', 'LOGO', 'LOGO', 'LOGO', 'LOGO', 'LOGO'
]

onMounted(() => {
  if (heroTitle.value) {
    gsap.from(heroTitle.value, {
      y: 60,
      opacity: 0,
      duration: 1,
      ease: 'power4.out',
      delay: 0.2
    })
  }
})
</script>

<template>
  <div class="overflow-hidden">
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center bg-white overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-40 h-40 border-2 border-black rounded-full"></div>
        <div class="absolute bottom-20 right-20 w-60 h-60 border-2 border-black rounded-full"></div>
      </div>
      
      <div class="container mx-auto px-4 py-20">
        <div class="flex flex-col lg:flex-row items-center gap-12">
          <!-- Left: Text -->
          <div class="lg:w-1/2">
            <div class="inline-block relative mb-6">
              <span class="tag-pink text-xs">NEW LOCATIONS</span>
            </div>
            
            <h1 ref="heroTitle" class="font-heading text-5xl md:text-7xl lg:text-8xl font-bold leading-[0.95] text-brand-dark mb-8">
              <span class="relative inline-block">
                Bursting
                <span class="absolute -bottom-2 left-0 w-full h-3 bg-brand-yellow -z-10 transform -rotate-1"></span>
              </span><br/>
              With <span class="italic">Flavour.</span><br/>
              Built With<br/>
              <span class="text-brand-pink">Passion.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-lg font-body leading-relaxed">
              We don't just serve food — we create experiences. From the bustling concessions at your favorite stadium to the elegant spreads at VIP events, we craft culinary moments that bring people together.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4">
              <BaseButton to="/contact" variant="primary" size="lg">
                Start a Project
              </BaseButton>
              <BaseButton to="/careers" variant="secondary" size="lg">
                Join Our Team
              </BaseButton>
            </div>
          </div>

          <!-- Right: Image Grid -->
          <div class="lg:w-1/2 relative">
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-4">
                <div class="border-organic overflow-hidden h-48">
                  <NuxtImg 
                    src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=600&auto=format&fit=crop" 
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                    alt="Food"
                  />
                </div>
                <div class="border-organic overflow-hidden h-64">
                  <NuxtImg 
                    src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?q=80&w=600&auto=format&fit=crop" 
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                    alt="Food"
                  />
                </div>
              </div>
              <div class="space-y-4 pt-8">
                <div class="border-organic overflow-hidden h-64">
                  <NuxtImg 
                    src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=600&auto=format&fit=crop" 
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
                    alt="Food"
                  />
                </div>
                <div class="border-organic overflow-hidden h-48 bg-brand-blue flex items-center justify-center">
                  <div class="text-center p-4">
                    <p class="font-heading text-4xl font-bold">15+</p>
                    <p class="font-body text-sm">Years Experience</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Floating Badge -->
            <div class="absolute -bottom-4 -left-4 bg-brand-lime border-organic px-6 py-3 animate-float">
              <span class="font-heading font-bold">🍴 Open for Events</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Preview Section -->
    <section class="py-20 bg-brand-gray overflow-hidden">
      <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
          <div class="lg:w-1/3">
            <span class="tag-blue mb-4 inline-block">LOCATIONS</span>
            <h2 class="font-heading text-4xl md:text-5xl font-bold mb-6">
              Explore Where We<br/>
              <span class="relative inline-block">
                Operate
                <span class="absolute -bottom-1 left-0 w-full h-3 bg-brand-lime -z-10"></span>
              </span>
            </h2>
            <p class="text-gray-600 font-body mb-8 leading-relaxed">
              From Paris to Marseille, we bring exceptional culinary experiences to the biggest venues in France. Discover our presence in your city.
            </p>
            
            <ul class="space-y-3">
              <li v-for="loc in locations" :key="loc.name" class="flex items-center justify-between p-4 bg-white border-organic hover:shadow-organic transition-all cursor-pointer group">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-brand-pink flex items-center justify-center border-2 border-black group-hover:scale-110 transition-transform">
                    <LucideMapPin class="w-5 h-5 text-white" />
                  </div>
                  <span class="font-bold text-lg">{{ loc.name }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-brand-pink font-bold">{{ loc.count }} jobs</span>
                  <LucideArrowRight class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition-transform" />
                </div>
              </li>
            </ul>
          </div>
          
          <div class="lg:w-2/3 h-[500px]">
            <div class="w-full h-full border-organic shadow-organic overflow-hidden">
              <ClientOnly>
                <VenueMap 
                  :venues="[
                    { id: '1', name: 'Paris', location: 'Stade Jean Bouin', lat: 48.8427, lng: 2.2536, openPositions: 12 },
                    { id: '2', name: 'Lyon', location: 'Groupama Stadium', lat: 45.7640, lng: 4.8357, openPositions: 8 },
                    { id: '3', name: 'Marseille', location: 'Stade Vélodrome', lat: 43.2965, lng: 5.3698, openPositions: 6 }
                  ]" 
                />
              </ClientOnly>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <span class="tag-yellow mb-4 inline-block">OUR SERVICES</span>
          <h2 class="font-heading text-4xl md:text-6xl font-bold">
            B2B Experiences Built With<br/>
            <span class="relative inline-block">
              Precision
              <span class="absolute -bottom-1 left-0 w-full h-3 bg-brand-blue -z-10 transform rotate-1"></span>
            </span> And Energy
          </h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div 
            v-for="(service, i) in services" 
            :key="i" 
            :class="['card-organic relative overflow-hidden group', service.bg, service.text]"
          >
            <component :is="service.icon" class="w-12 h-12 mb-6 transform group-hover:scale-110 transition-transform duration-300" />
            <h3 class="font-heading text-2xl font-bold mb-4">{{ service.title }}</h3>
            <p class="opacity-90 font-body">{{ service.desc }}</p>
            
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-black/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            
            <button class="mt-6 flex items-center gap-2 font-bold group-hover:gap-4 transition-all">
              Learn more <LucideArrowRight class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Partners Section -->
    <section class="py-16 bg-brand-gray border-y-2 border-black">
      <div class="container mx-auto px-4">
        <p class="text-center font-heading text-xl mb-8">
          They Trusted Us For Their Restauration By The General Public. They Did Not Regret Their Choices
        </p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
          <div v-for="(partner, i) in partners" :key="i" class="text-3xl font-heading font-bold text-gray-400 hover:text-brand-dark transition-colors cursor-pointer">
            {{ partner }}
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-brand-pink text-white text-center relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-80 h-80 border-2 border-white rounded-full"></div>
        <div class="absolute bottom-0 right-1/4 w-60 h-60 border-2 border-white rounded-full"></div>
      </div>
      
      <div class="container mx-auto px-4 relative z-10">
        <h2 class="font-heading text-4xl md:text-6xl font-bold mb-6">Ready to Make an Impact?</h2>
        <p class="text-white/80 max-w-2xl mx-auto mb-10 text-lg font-body">
          Join our team and be part of creating unforgettable culinary experiences across France's most exciting venues.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <BaseButton to="/careers" variant="lime" size="lg">Apply for a position</BaseButton>
          <BaseButton to="/contact" variant="secondary" size="lg">Get in touch</BaseButton>
        </div>
      </div>
    </section>

    <!-- Food Gallery Section -->
    <section class="py-20 bg-brand-dark">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="border-organic overflow-hidden aspect-square">
            <NuxtImg 
              src="https://images.unsplash.com/photo-1565299507177-b0ac66763828?q=80&w=400&auto=format&fit=crop" 
              class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
              alt="Food"
            />
          </div>
          <div class="border-organic overflow-hidden aspect-square">
            <NuxtImg 
              src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400&auto=format&fit=crop" 
              class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
              alt="Food"
            />
          </div>
          <div class="border-organic overflow-hidden aspect-square">
            <NuxtImg 
              src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=400&auto=format&fit=crop" 
              class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
              alt="Food"
            />
          </div>
          <div class="border-organic overflow-hidden aspect-square">
            <NuxtImg 
              src="https://images.unsplash.com/photo-1482049016gy?q=80&w=400&auto=format&fit=crop" 
              class="w-full h-full object-cover hover:scale-110 transition-transform duration-500"
              alt="Food"
            />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.tag-pink {
  @apply inline-flex items-center px-3 py-1 text-xs font-bold border-2 border-black bg-brand-pink text-white;
  border-radius: 50px 10px 45px 10px / 10px 45px 10px 50px;
}
</style>
