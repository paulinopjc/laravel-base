<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

import { ref, computed } from 'vue';
import TableWithFilters from '@/components/TableWithFilters.vue';

const props = defineProps<{
    cmsPages: {
        data: any[];
        current_page: number;
        per_page: number;
        total: number;
        links: any[];
    };
    filters: {
        fields: {
            title: string;
            slug: string;
            is_published: number;
        };
        sort: string;
        direction: 'asc' | 'desc';
    };
}>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'title', label: 'Title' },
    { key: 'slug', label: 'Url' },
    { key: 'is_published', label: 'Is Published' },
];

const filterFields = [
    { key: 'title', label: 'Title', value: props.filters.fields?.title || '' },
    { key: 'slug', label: 'Url', value: props.filters.fields?.slug || '' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CMS Pages',
        href: '/admin/cms-pages',
    },
];

const deleteCMSPage = (id: number) => {
    if (confirm('Are you sure you want to delete this cms page?')) {
        router.delete(`/admin/cms-pages/${id}`);
    }
};
</script>

<template>
    <Head title="CMS Pages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end mb-4">
            <Link
                :href="route('admin.cms-pages.create')"
                class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                + Add a new CMS Page
            </Link>
        </div>
        <TableWithFilters
            :items="props.cmsPages.data"
            :pagelink="'admin/cms-pages'"
            :filters="{ fields: filterFields, sort: props.filters.sort, direction: props.filters.direction }"
            :columns="columns"
            :pagination="props.cmsPages"
            :deleteItem="deleteCMSPage"
        />
    </AppLayout>
</template>
