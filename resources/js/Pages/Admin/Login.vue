<template>
    <FrontendLayout>
        <div class="flex flex-col items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Admin Login</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    <router-link to="/admin/forgot-password" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</router-link>
                </p>
            </div>

            <div class="mt-8 max-w-md w-full space-y-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input id="email" type="email" v-model="form.email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="email@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" type="password" v-model="form.password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Password" />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('admin.login'));
};
</script>

<style scoped>
/* Add any specific styles for the login page here */
</style>