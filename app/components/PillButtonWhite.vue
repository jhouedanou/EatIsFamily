<template>
  <NuxtLink :to="to" class="btn-wrapper" :class="{ 'btn-wrapper--disabled': disabled }">
    <div class="btn-outline" :style="{ clipPath: clipPath }"></div>
    <span class="btn btn--white" :style="{ clipPath: clipPath }">
      <slot>{{ label }}</slot>
    </span>
  </NuxtLink>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Props {
  to: string
  label?: string
  disabled?: boolean
}

defineProps<Props>()

const clipPath = ref('')

function rand(min: number, max: number): number {
  return Math.random() * (max - min) + min
}

function generateRandomClipPath(): string {
  const points: string[] = []
  
  const depthMin = 0.5
  const depthMax = 3.5
  const stepMin = 0.6
  const stepMax = 1.4
  
  const r = 50
  const flatStart = 15
  const flatEnd = 85
  
  // LEFT SIDE
  for (let angle = 90; angle <= 270; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatStart
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const x = cx + sketchyR * 0.3 * Math.cos(rad)
    const y = cy + sketchyR * Math.sin(rad)
    points.push(`${Math.max(0, x).toFixed(2)}% ${Math.max(0, Math.min(100, y)).toFixed(2)}%`)
  }
  
  // TOP EDGE
  let x = flatStart
  while (x < flatEnd) {
    const y = rand(depthMin, depthMax)
    points.push(`${x.toFixed(2)}% ${y.toFixed(2)}%`)
    x += rand(stepMin, stepMax)
  }
  
  // RIGHT SIDE
  for (let angle = 270; angle <= 450; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatEnd
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const xPoint = cx + sketchyR * 0.3 * Math.cos(rad)
    const y = cy + sketchyR * Math.sin(rad)
    points.push(`${Math.min(100, xPoint).toFixed(2)}% ${Math.max(0, Math.min(100, y)).toFixed(2)}%`)
  }
  
  // BOTTOM EDGE
  x = flatEnd
  while (x > flatStart) {
    const y = 100 - rand(depthMin, depthMax)
    points.push(`${x.toFixed(2)}% ${y.toFixed(2)}%`)
    x -= rand(stepMin, stepMax)
  }
  
  return `polygon(${points.join(', ')})`
}

onMounted(() => {
  clipPath.value = generateRandomClipPath()
})
</script>

<style scoped>
.btn-wrapper {
  position: relative;
  display: inline-block;
  text-decoration: none;
}

.btn-wrapper--disabled {
  pointer-events: none;
  opacity: 0.6;
}

.btn-outline {
  position: absolute;
  inset: -4px;
  background: #000;
  z-index: 0;
}

.btn {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-family: FONTSPRINGDEMO-RecoletaSemiBold, Georgia, serif;
  font-size: 18px;
  font-weight: bold;
  padding: 0 2rem;
  height: 70px;
  min-width: 250px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  line-height: 1.4;
  transition: all 0.2s ease;
  font-style: normal;
  z-index: 1;
}

.btn:hover {
  transform: translateY(-3px);
  filter: brightness(1.05);
}

.btn:active {
  transform: translateY(1px);
}

.btn--white {
  background: #ffffff;
  color: #000;
}
</style>
