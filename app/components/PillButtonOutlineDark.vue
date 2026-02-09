<template>
  <NuxtLink :to="to" class="btn-wrapper" :class="{ 'btn-wrapper--disabled': disabled }">
    <svg class="btn-border" viewBox="0 0 300 70" preserveAspectRatio="none">
      <path :d="svgPath" fill="transparent" stroke="#000" stroke-width="3" />
    </svg>
    <span class="btn-label">
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

const svgPath = ref('')

function rand(min: number, max: number): number {
  return Math.random() * (max - min) + min
}

function generateOrganicPath(): string {
  const w = 300
  const h = 70
  const points: [number, number][] = []

  const depthMin = 0.5
  const depthMax = 3
  const r = 50
  const flatStart = 15
  const flatEnd = 85

  // LEFT SIDE (arc)
  for (let angle = 90; angle <= 270; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatStart
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const px = cx + sketchyR * 0.3 * Math.cos(rad)
    const py = cy + sketchyR * Math.sin(rad)
    points.push([
      Math.max(0, px) * w / 100,
      Math.max(0, Math.min(100, py)) * h / 100
    ])
  }

  // TOP EDGE
  let x = flatStart
  while (x < flatEnd) {
    const y = rand(depthMin, depthMax)
    points.push([x * w / 100, y * h / 100])
    x += rand(0.6, 1.4)
  }

  // RIGHT SIDE (arc)
  for (let angle = 270; angle <= 450; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatEnd
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const px = cx + sketchyR * 0.3 * Math.cos(rad)
    const py = cy + sketchyR * Math.sin(rad)
    points.push([
      Math.min(100, px) * w / 100,
      Math.max(0, Math.min(100, py)) * h / 100
    ])
  }

  // BOTTOM EDGE
  x = flatEnd
  while (x > flatStart) {
    const y = 100 - rand(depthMin, depthMax)
    points.push([x * w / 100, y * h / 100])
    x -= rand(0.6, 1.4)
  }

  // Build SVG path
  if (points.length === 0) return ''
  const first = points[0]!
  let d = `M ${first[0].toFixed(1)} ${first[1].toFixed(1)}`
  for (let i = 1; i < points.length; i++) {
    const pt = points[i]!
    d += ` L ${pt[0].toFixed(1)} ${pt[1].toFixed(1)}`
  }
  d += ' Z'
  return d
}

onMounted(() => {
  svgPath.value = generateOrganicPath()
})
</script>

<style scoped>
.btn-wrapper {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 70px;
  min-width: 250px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-wrapper:hover {
  transform: translateY(-3px);
}

.btn-wrapper:active {
  transform: translateY(1px);
}

.btn-wrapper--disabled {
  pointer-events: none;
  opacity: 0.6;
}

.btn-border {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}

.btn-label {
  position: relative;
  z-index: 1;
  font-family: FONTSPRINGDEMO-RecoletaSemiBold, Georgia, serif;
  font-size: 18px;
  font-weight: bold;
  font-style: normal;
  color: #000;
  line-height: 1.4;
  padding: 0 2rem;
}
</style>
