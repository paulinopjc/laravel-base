<script setup>
import { usePage } from '@inertiajs/vue3';

defineProps({
  menus: Array,
  footerLinks: Array,
})

const footerLinks = usePage().props.frontEndFooterLinks;
console.log('Footer Links:', footerLinks);
</script>
<template>
  <footer class="bg-gray-100 mt-10 border-t">
    <div class="max-w-6xl mx-auto px-4 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
      <div v-for="link in footerLinks" :key="link.id" class="space-y-2">
        <h4 class="text-lg font-semibold text-gray-800">{{ link.name }}</h4>
        <ul class="space-y-1">
          <li v-for="child in link.children" :key="child.id">
            <a
              :href="child.url"
              class="text-gray-600 hover:text-gray-900 text-sm"
              target="_blank"
              rel="noopener"
            >
              {{ child.name }}
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-600">
      &copy; {{ new Date().getFullYear() }} MySite. All rights reserved.
    </div>
  </footer>
</template>
