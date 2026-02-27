<script setup lang="ts">
import { ref } from 'vue'
import type { DiviNode } from '~/composables/useDiviParser'

const props = defineProps<{ node: DiviNode }>()

const tabs = computed(() =>
  props.node.children.filter(c => c.type === 'et_pb_tab')
)

const activeTab = ref(0)
</script>

<template>
  <div v-if="tabs.length > 0" class="divi-tabs">
    <div class="divi-tabs__nav">
      <button
        v-for="(tab, idx) in tabs"
        :key="`tab-nav-${idx}`"
        class="divi-tabs__tab"
        :class="{ 'divi-tabs__tab--active': activeTab === idx }"
        @click="activeTab = idx"
      >
        {{ tab.attrs.title || `Onglet ${idx + 1}` }}
      </button>
    </div>
    <div class="divi-tabs__content">
      <div
        v-for="(tab, idx) in tabs"
        :key="`tab-content-${idx}`"
        v-show="activeTab === idx"
        class="divi-tabs__panel"
        v-html="tab.content"
      ></div>
    </div>
  </div>
</template>

<style lang="scss">
.divi-tabs {
  margin: 1.5rem 0;
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;

  &__nav {
    display: flex;
    border-bottom: 2px solid #eee;
    overflow-x: auto;
  }

  &__tab {
    flex: 1;
    padding: 0.75rem 1.25rem;
    background: #f9f9f9;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9375rem;
    color: #666;
    transition: all 0.2s ease;
    white-space: nowrap;

    &:hover {
      background: #f0f0f0;
      color: #1a1a1a;
    }

    &--active {
      background: #fff;
      color: #1a1a1a;
      border-bottom: 2px solid #c39d63;
      margin-bottom: -2px;
    }
  }

  &__content {
    padding: 1.25rem;
  }

  &__panel {
    font-size: 0.9375rem;
    line-height: 1.7;
    color: #444;

    p { margin: 0 0 0.75rem; }
  }
}
</style>
