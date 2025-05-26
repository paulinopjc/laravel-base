<script setup>
    import FrontendHeader from './partials/FrontendHeader.vue'
    import FrontendFooter from './partials/FrontendFooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <FrontendHeader />
    
    <main class="flex-1">
      <slot />
    </main>

    <FrontendFooter />
  </div>
</template>
