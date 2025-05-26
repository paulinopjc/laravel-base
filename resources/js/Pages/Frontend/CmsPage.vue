<script setup>

import { defineProps } from 'vue';
import FrontendMain from '@/layouts/FrontendLayout.vue'
const props = defineProps({
page: Object,
});

</script>

<template>
  <FrontendMain>
    <div class="max-w-4xl mx-auto py-10 px-4">
      <h1 class="text-3xl font-bold mb-4">{{ page.title }}</h1>
      <div v-html="page.content" class="prose"></div>
    </div>
  </FrontendMain>
</template>
