<script setup>
import { usePage } from '@inertiajs/vue3';

defineProps({
  menus: Array
})

const siteSettings = usePage().props.siteSettings;
</script>

<template>
  <header class="bg-white shadow-md">
    <div class="container mx-auto flex items-center justify-between py-4">
      <a href="/" class="text-2xl font-bold text-gray-800">
        <img :src="siteSettings.desktop_logo" alt="Site Logo" class="max-h-[60px]">
      </a>
      <nav class="flex gap-6">
        <div v-for="menu in menus" :key="menu.id" class="relative group">
          <!-- Parent link -->
          <a
            :href="menu.url"
            class="text-gray-800 hover:text-blue-600 font-medium"
          >
            {{ menu.name }}
          </a>

          <!-- Dropdown -->
          <div
            v-if="menu.children && menu.children.length"
            class="absolute left-0 top-full w-40 bg-white border shadow-md z-10 opacity-0 group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200"
          >
            <ul class="divide-y">
              <li v-for="child in menu.children" :key="child.id">
                <a
                  :href="child.url"
                  class="block px-4 py-2 hover:bg-gray-100"
                >
                  {{ child.name }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>
</template>

