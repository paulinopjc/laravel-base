<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    footerLink: {
        id: number;
        name: string;
        url: string;
        order: number;
        parent_id: number | null;
    };
    parentFooterLinks: {
        id: number;
        name: string;
    }[];
}>();

const form = useForm({
    name: props.footerLink.name,
    url: props.footerLink.url,
    parent_id: props.footerLink.parent_id,
    order: props.footerLink.order,
});

const submit = () => {
  form.put(`/admin/footer-links/${props.footerLink.id}`)
};

</script>

<template>
  <Head title="Edit a Footer Link" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Edit a Footer Link</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input v-model="form.name" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">URL</label>
          <input v-model="form.url" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.url" class="text-red-500 text-sm">{{ form.errors.url }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Order</label>
          <input v-model.number="form.order" type="number" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.order" class="text-red-500 text-sm">{{ form.errors.order }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Parent</label>
          <select v-model="form.parent_id" class="mt-1 w-full rounded border p-2">
            <option value="">Select Parent Footer Link</option>
            <option v-for="footerLink in props.parentFooterLinks" :key="footerLink.id" :value="footerLink.id">
              {{ footerLink.name }}
            </option>
          </select>
          <div v-if="form.errors.parent_id" class="text-red-500 text-sm">{{ form.errors.parent_id }}</div>
        </div>

        <div>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update
          </button>
          <a href="/admin/footer-links" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
