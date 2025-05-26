<script setup lang="ts">
import FrontendLayout from '@/layouts/FrontendLayout.vue';
const props = defineProps({
  page: {
    type: Object,
    default: () => ({
      title: 'Home Page',
      meta_title: 'Home Page',
      meta_description: 'Welcome to the Home Page',
      meta_keywords: 'home, page',
      content: 'This is the content of the Home Page.'
    })
  }
});


const currentUrl = typeof window !== 'undefined' ? window.location.href : '';
</script>

<template>
    <FrontendLayout
        :meta="{
            title: page.meta_title || page.title,
            description: page.meta_description || '',
            keywords: page.meta_keywords || '',
            type: 'article',
            url: currentUrl,
            image: null // Optional if you plan to support OG image
        }"
    >
        <div class="max-w-4xl mx-auto py-10 px-4">
            <h1 class="text-3xl font-bold mb-4">Welcome to the Home Page</h1>
            <div class="prose">This is the frontend section accessible to all users.</div>
        </div>
    </FrontendLayout>
</template>