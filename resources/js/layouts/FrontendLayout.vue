<script setup>
    import FrontendHeader from './partials/FrontendHeader.vue'
    import FrontendFooter from './partials/FrontendFooter.vue'
    import { Head, usePage } from '@inertiajs/vue3';

    const props = defineProps({
        meta: {
            type: Object,
            default: () => ({
            title: '',
            description: '',
            keywords: '',
            type: 'website',
            url: '',
            image: null,
            }),
        },
    });

    const menus = usePage().props.frontendMenus;
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Head>
        <title>{{ meta.title }}</title>
        <meta name="description" :content="meta.description || ''" />
        <meta name="keywords" :content="meta.keywords || ''" />
        <!-- Open Graph -->
        <meta property="og:title" :content="meta.title" />
        <meta property="og:description" :content="meta.description" />
        <meta property="og:type" content="article" />
        <meta property="og:url" :content="meta.url" />
        <!-- Replace with dynamic image if needed -->
        <!--<meta property="og:image" content="/default-og-image.jpg" />-->

        <!-- Twitter Card (optional) -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="meta.title" />
        <meta name="twitter:description" :content="meta.description" />
        <!-- Replace with dynamic image if needed -->
        <!--<meta name="twitter:image" content="/default-og-image.jpg" />-->
    </Head>
    <FrontendHeader :menus="menus" />
    
    <main class="flex-1">
      <slot />
    </main>

    <FrontendFooter />
  </div>
</template>
