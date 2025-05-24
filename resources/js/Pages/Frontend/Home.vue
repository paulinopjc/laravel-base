<template>
    <FrontendLayout>
        <h1>Welcome to the Home Page</h1>
        <p>This is the frontend section accessible to all users.</p>
    </FrontendLayout>
</template>

<script setup lang="ts">
import FrontendLayout from '@/layouts/FrontendLayout.vue';
</script>

<style scoped>
h1 {
    color: #333;
}

p {
    font-size: 1.2em;
}
</style>