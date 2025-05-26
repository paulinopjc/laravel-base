<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

import { ref, computed } from 'vue';
import TableWithFilters from '@/components/TableWithFilters.vue';

const props = defineProps<{
    userRoles: {
        data: any[];
        current_page: number;
        per_page: number;
        total: number;
        links: any[];
    };
    filters: {
        fields: {
            name: string;
        };
        sort: string;
        direction: 'asc' | 'desc';
    };
}>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
];

const filterFields = [
    { key: 'name', label: 'Name', value: props.filters.fields?.name || '' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Roles',
        href: '/admin/user-roles',
    },
];

const deleteUserRole = (id: number) => {
    if (confirm('Are you sure you want to delete this user role?')) {
        router.delete(`/admin/user-roles/${id}`);
    }
};
</script>

<template>
    <Head title="User Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-4">
            <Link
                :href="route('admin.user-roles.create')"
                class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                + Add a new User Role
            </Link>
        </div>
        <TableWithFilters
            :items="props.userRoles.data"
            :pagelink="'admin/user-roles'"
            :filters="{ fields: filterFields, sort: props.filters.sort, direction: props.filters.direction }"
            :columns="columns"
            :pagination="props.userRoles"
            :deleteItem="deleteUserRole"
        />
    </AppLayout>
</template>
