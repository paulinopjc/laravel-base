<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    userRole: {
        id: number;
        name: string;
    };
    userRoles: {
        id: number;
        name: string;
    }[];
}>();

const form = useForm({
    name: props.userRole.name,
});

const submit = () => {
  form.put(`/admin/user-roles/${props.userRole.id}`)
};

</script>

<template>
  <Head title="Edit a User Role" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Edit a User Role</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Name</label>
          <input v-model="form.name" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
        </div>


        <div>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create
          </button>
          <a href="/admin/users" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
