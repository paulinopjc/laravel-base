<script setup>

import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3'
import FrontendMain from '@/layouts/FrontendLayout.vue'
const props = defineProps({
    page: Object,
});

const currentUrl = typeof window !== 'undefined' ? window.location.href : '';
</script>

<template>
    <FrontendMain
        :meta="{
            title: page.meta_title || page.title,
            description: page.meta_description || '',
            keywords: page.meta_keywords || '',
            type: 'article',
            url: currentUrl,
            image: null // Optional if you plan to support OG image
        }"
    >
        <div v-if="page" class="max-w-4xl mx-auto py-10 px-4">
            <h1 class="text-3xl font-bold mb-4">{{ page.title }}</h1>
            <div v-html="page.content" class="prose"></div>
        </div>
        <div v-else class="max-w-4xl mx-auto py-10 px-4">
            <h1>Page Not Found</h1>
            <p>The requested page does not exist.</p>
        </div>
    </FrontendMain>
</template>
