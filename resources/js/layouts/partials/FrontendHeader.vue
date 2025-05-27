<script setup>
defineProps({
  menus: Array
})
</script>

<template>
  <header class="bg-white shadow-md">
    <nav class="container mx-auto flex gap-6 py-4">
      <div v-for="menu in menus" :key="menu.id" class="relative group">
        <a :href="menu.url" class="text-gray-800 hover:text-blue-600 font-medium">
          {{ menu.name }}
        </a>
        <div
          v-if="menu.children && menu.children.length"
          class="absolute left-0 mt-2 w-40 bg-white border shadow-md z-10 hidden group-hover:block"
        >
          <ul class="divide-y">
            <li v-for="child in menu.children" :key="child.id">
              <a :href="child.url" class="block px-4 py-2 hover:bg-gray-100">
                {{ child.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>
