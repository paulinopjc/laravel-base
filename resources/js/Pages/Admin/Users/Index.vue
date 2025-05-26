<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

import { ref, computed } from 'vue';
import TableWithFilters from '@/components/TableWithFilters.vue';

interface User {
  id: number;
  name: string;      // virtual accessor from Laravel
  email: string;
  role_id: number;
}

const props = defineProps<{
    users: {
        data: User[];
        current_page: number;
        per_page: number;
        total: number;
        links: any[];
    };
    filters: {
        fields: {
            name: string;
            email: string;
            role_id: number;
        };
        sort: string;
        direction: 'asc' | 'desc';
    };
    userRoles: {
        id: number;
        name: string;
    }
}>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'role_id', label: 'Role', options: props.userRoles },
];

const filterFields = [
    { key: 'name', label: 'Name', value: props.filters.fields?.name || '' },
    { key: 'email', label: 'Email', value: props.filters.fields?.email || '' },
    { key: 'role_id', label: 'Role', value: props.filters.fields?.role_id || '', type: 'select', options: props.userRoles.map(i => ({ value: i.id, label: i.name })) },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/admin/users',
    },
];

const deleteUser = (id: number) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(`/admin/users/${id}`);
    }
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-4">
            <Link
                :href="route('admin.users.create')"
                class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                + Add a new User
            </Link>
        </div>
        <TableWithFilters
            :items="props.users.data"
            :pagelink="'admin/users'"
            :filters="{ fields: filterFields, sort: props.filters.sort, direction: props.filters.direction }"
            :columns="columns"
            :pagination="props.users"
            :deleteItem="deleteUser"
        />
    </AppLayout>
</template>
