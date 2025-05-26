<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    user: {
        id: number;
        firstname: string;
        lastname: string;
        email: string;
        role_id: number;
        password: string;
        password_confirmation: string;
    };
    userRoles: {
        id: number;
        name: string;
    }[];
}>();

const form = useForm({
    firstname: props.user.firstname,
    lastname: props.user.lastname,
    email: props.user.email,
    role_id: props.user.role_id,
    password: props.user.password,
    password_confirmation: props.user.password_confirmation,
});

const submit = () => {
  form.put(`/admin/users/${props.user.id}`)
};

</script>

<template>
  <Head title="Edit a User" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Edit a User</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">First Name</label>
          <input v-model="form.firstname" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.firstname" class="text-red-500 text-sm">{{ form.errors.firstname }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Last Name</label>
          <input v-model="form.lastname" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.lastname" class="text-red-500 text-sm">{{ form.errors.lastname }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Email</label>
          <input v-model="form.email" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Select a User Role</label>
          <select v-model="form.role_id" class="mt-1 w-full rounded border p-2">
            <option value="">Select a User Role</option>
            <option v-for="role in props.userRoles" :key="role.id" :value="role.id">
            {{ role.name }}
            </option>
          </select>
          <div v-if="form.errors.role_id" class="text-red-500 text-sm">{{ form.errors.role_id }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Password</label>
          <input v-model="form.password" type="password" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</div>
        </div>

        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" class="mt-1 w-full rounded border p-2" />
            <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm">
                {{ form.errors.password_confirmation }}
            </div>
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
