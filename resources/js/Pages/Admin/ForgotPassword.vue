<template>
    <FrontendLayout>
        <div class="flex flex-col items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Forgot Password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your email address to receive a password reset link.
                </p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="email"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email address"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Send Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<style scoped>
/* Add any specific styles for the Forgot Password page here */
</style>