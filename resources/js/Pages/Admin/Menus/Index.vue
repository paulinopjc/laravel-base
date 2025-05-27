<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

import { ref, computed } from 'vue';
import TableWithFilters from '@/components/TableWithFilters.vue';

const props = defineProps<{
    menus: {
        data: any[];
        current_page: number;
        per_page: number;
        total: number;
        links: any[];
    };
    filters: {
        fields: {
            name: string;
            url: string;
            order: number;
            parent_id: number;
        };
        sort: string;
        direction: 'asc' | 'desc';
    };
    parentMenus: {
        id: number;
        name: string;
        url: string;
        order: number;
        parent_id: number | null;
    }[];
}>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'url', label: 'Url' },
    { key: 'order', label: 'Order' },
    { key: 'parent_id', label: 'Parent', options: props.parentMenus },
];

const filterFields = [
    { key: 'name', label: 'Name', value: props.filters.fields?.name || '' },
    { key: 'url', label: 'Url', value: props.filters.fields?.url || '' },
    { key: 'order', label: 'Order', value: props.filters.fields?.order || '' },
    { key: 'parent_id', label: 'Parent', value: props.filters.fields?.parent_id || '', type: 'select', options: props.parentMenus.map(i => ({ value: i.id, label: i.name })) },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu Items',
        href: '/admin/menus',
    },
];

const deleteMenu = (id: number) => {
    if (confirm('Are you sure you want to delete this menu item?')) {
        router.delete(`/admin/menus/${id}`);
    }
};
</script>

<template>
    <Head title="Menu Items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-4">
            <Link
                :href="route('admin.menus.create')"
                class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                + Add a new Menu Item
            </Link>
        </div>
        <TableWithFilters
            :items="props.menus.data"
            :pagelink="'admin/menus'"
            :filters="{ fields: filterFields, sort: props.filters.sort, direction: props.filters.direction }"
            :columns="columns"
            :pagination="props.menus"
            :deleteItem="deleteMenu"
        />
    </AppLayout>
</template>
