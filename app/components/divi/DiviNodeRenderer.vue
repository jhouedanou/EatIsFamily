<script setup lang="ts">
/**
 * DiviNodeRenderer.vue - Recursive Divi Node Renderer
 * 
 * Renders a single DiviNode and its children recursively.
 * Dispatches to the appropriate sub-component based on node type.
 */
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{
  node: DiviNode
}>()
</script>

<template>
  <!-- Section -->
  <DiviSection v-if="node.type === 'et_pb_section'" :node="node" />

  <!-- Row -->
  <DiviRow v-else-if="node.type === 'et_pb_row' || node.type === 'et_pb_row_inner'" :node="node" />

  <!-- Column (usually rendered within DiviRow, but handle standalone) -->
  <DiviColumn v-else-if="node.type === 'et_pb_column' || node.type === 'et_pb_column_inner'" :node="node" />

  <!-- Text -->
  <DiviText v-else-if="node.type === 'et_pb_text'" :node="node" />

  <!-- Image -->
  <DiviImage v-else-if="node.type === 'et_pb_image' || node.type === 'et_pb_fullwidth_image'" :node="node" />

  <!-- Button -->
  <DiviButton v-else-if="node.type === 'et_pb_button'" :node="node" />

  <!-- Blurb (icon + title + text) -->
  <DiviBlurb v-else-if="node.type === 'et_pb_blurb'" :node="node" />

  <!-- Slider / Fullwidth Slider -->
  <DiviSlider v-else-if="node.type === 'et_pb_slider' || node.type === 'et_pb_fullwidth_slider'" :node="node" />

  <!-- Video -->
  <DiviVideo v-else-if="node.type === 'et_pb_video'" :node="node" />

  <!-- Code (raw HTML) -->
  <DiviCode v-else-if="node.type === 'et_pb_code' || node.type === 'et_pb_fullwidth_code'" :node="node" />

  <!-- Image Carousel (WDCL plugin) -->
  <DiviImageCarousel v-else-if="node.type === 'wdcl_image_carousel'" :node="node" />

  <!-- CTA -->
  <DiviCta v-else-if="node.type === 'et_pb_cta'" :node="node" />

  <!-- Divider -->
  <div v-else-if="node.type === 'et_pb_divider'" class="divi-divider">
    <hr />
  </div>

  <!-- Accordion -->
  <DiviAccordion v-else-if="node.type === 'et_pb_accordion'" :node="node" />

  <!-- Toggle -->
  <DiviToggle v-else-if="node.type === 'et_pb_toggle'" :node="node" />

  <!-- Tabs -->
  <DiviTabs v-else-if="node.type === 'et_pb_tabs'" :node="node" />

  <!-- Team Member / Testimonial -->
  <DiviTestimonial v-else-if="node.type === 'et_pb_testimonial'" :node="node" />

  <!-- Table of Contents -->
  <div v-else-if="node.type === 'ez-toc'" class="divi-toc-placeholder">
    <!-- TOC is generated client-side from headings -->
  </div>

  <!-- Raw HTML (text between shortcodes) -->
  <div v-else-if="node.type === 'raw_html' && node.content" class="divi-raw-html" v-html="node.content"></div>

  <!-- Fallback: render children if any, or content -->
  <div v-else-if="node.children.length > 0 || node.content" class="divi-unknown">
    <div v-if="node.content" v-html="node.content"></div>
    <DiviNodeRenderer
      v-for="(child, idx) in node.children"
      :key="`child-${idx}`"
      :node="child"
    />
  </div>
</template>
