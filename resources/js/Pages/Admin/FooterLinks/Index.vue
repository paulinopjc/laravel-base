<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

import { ref, computed } from 'vue';
import TableWithFilters from '@/components/TableWithFilters.vue';

const props = defineProps<{
    footerLinks: {
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
    parentFooterLinks: {
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
    { key: 'parent_id', label: 'Parent', options: props.parentFooterLinks },
];

const filterFields = [
    { key: 'name', label: 'Name', value: props.filters.fields?.name || '' },
    { key: 'url', label: 'Url', value: props.filters.fields?.url || '' },
    { key: 'order', label: 'Order', value: props.filters.fields?.order || '' },
    { key: 'parent_id', label: 'Parent', value: props.filters.fields?.parent_id || '', type: 'select', options: props.parentFooterLinks.map(i => ({ value: i.id, label: i.name })) },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Footer Links',
        href: '/admin/footer-links',
    },
];

const deleteFooterLink = (id: number) => {
    if (confirm('Are you sure you want to delete this footer link?')) {
        router.delete(`/admin/footer-links/${id}`);
    }
};
</script>

<template>
    <Head title="Footer Links" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-4">
            <Link
                :href="route('admin.footer-links.create')"
                class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                + Add a new Footer Link
            </Link>
        </div>
        <TableWithFilters
            :items="props.footerLinks.data"
            :pagelink="'admin/footer-links'"
            :filters="{ fields: filterFields, sort: props.filters.sort, direction: props.filters.direction }"
            :columns="columns"
            :pagination="props.footerLinks"
            :deleteItem="deleteFooterLink"
        />
    </AppLayout>
</template>
