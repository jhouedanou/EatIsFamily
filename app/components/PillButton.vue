<template>
  <NuxtLink :to="to" class="btn-wrapper" :class="{ 'btn-wrapper--disabled': disabled }" :style="wrapperStyle">
    <!-- Filled variant: clip-path -->
    <template v-if="variant === 'filled'">
      <div class="btn-outline-bg" :style="{ clipPath: clipPath }"></div>
      <span class="btn" :style="[{ clipPath: clipPath }, btnColorStyle]">
        <slot>{{ label }}</slot>
      </span>
    </template>

    <!-- Outline variant: SVG border -->
    <template v-else>
      <svg class="btn-border" viewBox="0 0 300 70" preserveAspectRatio="none">
        <path :d="svgPath" fill="transparent" :stroke="outlineStrokeColor" stroke-width="3" />
      </svg>
      <span class="btn-label" :style="{ color: outlineTextColor }">
        <slot>{{ label }}</slot>
      </span>
    </template>
  </NuxtLink>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

interface Props {
  to?: string
  label?: string
  disabled?: boolean
  color?: 'pink' | 'yellow' | 'white' | 'transparent' | 'dark' | 'light'
  variant?: 'filled' | 'outline'
  width?: string
}

const props = withDefaults(defineProps<Props>(), {
  color: 'pink',
  variant: 'filled',
})

const clipPath = ref('')
const svgPath = ref('')

const colorMap: Record<string, { bg: string; text: string }> = {
  pink: { bg: '#f9375b', text: '#fff' },
  yellow: { bg: '#FFE600', text: '#000' },
  white: { bg: '#ffffff', text: '#000' },
  transparent: { bg: 'transparent', text: '#fff' },
}

const btnColorStyle = computed(() => {
  const c = colorMap[props.color] || colorMap.pink
  return { background: c.bg, color: c.text }
})

const outlineStrokeColor = computed(() => props.color === 'dark' ? '#000' : '#fff')
const outlineTextColor = computed(() => props.color === 'dark' ? '#000' : '#fff')

const wrapperStyle = computed(() => {
  const s: Record<string, string> = {}
  if (props.width) s.maxWidth = props.width
  return s
})

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

  for (let angle = 90; angle <= 270; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatStart
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const x = cx + sketchyR * 0.3 * Math.cos(rad)
    const y = cy + sketchyR * Math.sin(rad)
    points.push(`${Math.max(0, x).toFixed(2)}% ${Math.max(0, Math.min(100, y)).toFixed(2)}%`)
  }

  let x = flatStart
  while (x < flatEnd) {
    const y = rand(depthMin, depthMax)
    points.push(`${x.toFixed(2)}% ${y.toFixed(2)}%`)
    x += rand(stepMin, stepMax)
  }

  for (let angle = 270; angle <= 450; angle += 4) {
    const rad = (angle * Math.PI) / 180
    const cx = flatEnd
    const cy = 50
    const sketchyR = r + rand(-depthMax, depthMax)
    const xPoint = cx + sketchyR * 0.3 * Math.cos(rad)
    const y = cy + sketchyR * Math.sin(rad)
    points.push(`${Math.min(100, xPoint).toFixed(2)}% ${Math.max(0, Math.min(100, y)).toFixed(2)}%`)
  }

  x = flatEnd
  while (x > flatStart) {
    const y = 100 - rand(depthMin, depthMax)
    points.push(`${x.toFixed(2)}% ${y.toFixed(2)}%`)
    x -= rand(stepMin, stepMax)
  }

  return `polygon(${points.join(', ')})`
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

  let x = flatStart
  while (x < flatEnd) {
    const y = rand(depthMin, depthMax)
    points.push([x * w / 100, y * h / 100])
    x += rand(0.6, 1.4)
  }

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

  x = flatEnd
  while (x > flatStart) {
    const y = 100 - rand(depthMin, depthMax)
    points.push([x * w / 100, y * h / 100])
    x -= rand(0.6, 1.4)
  }

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
  if (props.variant === 'filled') {
    clipPath.value = generateRandomClipPath()
  } else {
    svgPath.value = generateOrganicPath()
  }
})
</script>

<style scoped>
/* Shared */
.btn-wrapper {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 60px;
  max-width: 250px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-wrapper:hover {
  transform: translateY(-3px);
  filter: brightness(1.05);
}

.btn-wrapper:active {
  transform: translateY(1px);
}

.btn-wrapper--disabled {
  pointer-events: none;
  opacity: 0.6;
}

/* Filled variant */
.btn-outline-bg {
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
  height: 60px;
  max-width: 250px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  line-height: 1.4;
  font-style: normal;
  z-index: 1;
}

/* Outline variant */
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
  line-height: 1.4;
  padding: 0 2rem;
}
</style>
