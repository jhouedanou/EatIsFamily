-4<script setup lang="ts">
interface Props {
  image: string
  title: string
  description: string
  link: string
  buttonImage: string
  additionalText?: string
  buttonImage2?: string
}

defineProps<Props>()
</script>

<template>
  <section id="ready-to-make-an-impact" class="py-5 new-white position-relative">
    <div id="mero" class="container-fluid position-relative d-flex justify-content-center align-items-center p-4">
      <!-- <img :src="image" alt="" /> -->
      <div id="leforme" class="position-absolute d-flex flex-column justify-content-center align-items-center text-center p-4 mx-auto">
        <h2>{{ title }}</h2>
        <p class="preserve-lines" v-html="description"></p>
    <!--     <nuxt-link>
          <nuxt-img :src="buttonImage" :alt="title"></nuxt-img>
        </nuxt-link> -->
        <PillButton  color="yellow" :to="link" width="320px" inset="-1px" label="Découvrir nos offres et postuler" /> 
      </div>
    </div>
    <CtaBlock
      v-if="additionalText && buttonImage2"
      :text="additionalText"
      :link="link"
      :button-image="buttonImage2"
      :button-alt="title"
    />
  </section>
</template>
