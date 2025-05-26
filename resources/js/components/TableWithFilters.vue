<script setup lang="ts">
import { defineProps, ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';

type Column = {
  key: string;
  label: string;
};

type FilterField = {
  key: string;
  label: string;
  value: string;
  type?: string;
  options?: { label: string; value: string }[];
};

type Filter = {
  fields: FilterField[];
  sort: string;
  direction: 'asc' | 'desc';
};

const props = defineProps<{
  items: any[];
  pagelink: string;
  filters: Filter;
  columns: Column[];
  pagination: {
    current_page: number;
    per_page: number;
    total: number;
    links: any[];
  };
  deleteItem: (id: number) => void;
}>();

const localFilters = ref(
  props.filters?.fields?.reduce((acc: Record<string, string>, field) => {
    acc[field.key] = field.value;
    return acc;
  }, {}) || {}
);

const fieldMap = computed(() => {
  return props.filters.fields.reduce((map, field) => {
    map[field.key] = field;
    return map;
  }, {} as Record<string, FilterField>);
});

const updateFilters = () => {
  router.get(
    window.location.pathname,
    {
      ...localFilters.value,
      sort: props.filters.sort,
      direction: props.filters.direction,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
};

const sortBy = (field: string) => {
  const newDirection =
    props.filters.sort === field && props.filters.direction === 'asc'
      ? 'desc'
      : 'asc';
  router.get(
    window.location.pathname,
    {
      ...localFilters.value,
      sort: field,
      direction: newDirection,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
};

const getDisplayValue = (key: string, value: any) => {
    const field = fieldMap.value[key];
    if (!field) return value;

    // If field has options and value is not an object (i.e., an ID), find label
    if (field.type === 'select' && field.options && typeof value !== 'object') {
        const match = field.options.find(opt => opt.value == value);
        return match ? match.label : value;
    }

    // If value is an object with a name, show that
    if (typeof value === 'object' && value !== null && 'name' in value) {
        return value.name;
    }

    return value;
};

const clearFilters = () => {
  for (const key in localFilters.value) {
    localFilters.value[key] = '';
  }
//   updateFilters();
  // Redirect to the same page without any query parameters
  router.get(window.location.pathname, {}, {
    preserveState: true,
    replace: true,
  });
};

const indexOffset = computed(
  () => (props.pagination.current_page - 1) * props.pagination.per_page
);
</script>

<template>
  <div class="overflow-x-auto rounded-xl border bg-white dark:bg-gray-900">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-100 dark:bg-gray-800 text-left text-gray-700 dark:text-gray-300">
        <tr>
          <th
            v-for="column in columns"
            :key="column.key"
            class="px-4 py-2 font-medium"
          >
            <div class="flex items-center gap-2">
              <template v-if="fieldMap[column.key]">
                <template v-if="fieldMap[column.key].type === 'select' && fieldMap[column.key].options?.length">
                    <select
                    v-model="localFilters[column.key]"
                    @change="updateFilters"
                    class="w-full rounded border px-2 py-1 text-sm"
                    >
                    <option value="">-- All --</option>
                    <option
                        v-for="option in fieldMap[column.key].options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                    </select>
                </template>
                <template v-else>
                    <input
                    v-model="localFilters[column.key]"
                    @change="updateFilters"
                    class="w-full rounded border px-2 py-1 text-sm"
                    :placeholder="`Search ${fieldMap[column.key].label}`"
                    type="text"
                    />
                </template>
            </template>
            <template v-else>
              {{ column.label }}
            </template>
              <button @click="sortBy(column.key)" class="text-sm cursor-pointer">
                <span v-if="props.filters.sort === column.key">
                  {{ props.filters.direction === 'asc' ? '↑' : '↓' }}
                </span>
                <span v-else>↕</span>
              </button>
            </div>
          </th>
          <th class="px-4 py-2 font-medium">Actions</th>
          <th class="px-4 py-2 font-medium">
            <button @click="updateFilters" class="text-sm bg-blue-600 text-white rounded px-2 py-1">
              Search
            </button>
            <button @click="clearFilters" class="text-sm bg-red-600 text-white rounded px-2 py-1 ml-2">
              Clear
            </button>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        <tr v-for="(item, index) in items" :key="index">
          <td v-for="column in columns" :key="column.key" class="px-4 py-2">
            {{ getDisplayValue(column.key, item[column.key]) }}
          </td>
          <td class="px-4 py-2 flex gap-2">
            <Link :href="`/${pagelink}/${item.id}/edit`" class="text-blue-600 hover:underline">Edit</Link>
            <button @click="props.deleteItem(item.id)" class="text-red-600 hover:underline">Delete</button>
          </td>
          <td class="px-4 py-2 flex gap-2"></td>
        </tr>
        <tr v-if="items.length === 0">
          <td :colspan="columns.length + 1" class="px-4 py-4 text-left text-gray-500">No items found.</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div v-if="items.length > 0" class="mt-4 flex flex-wrap gap-2">
    <Link
      v-for="link in pagination.links"
      :key="link.label"
      :href="link.url || ''"
      v-html="link.label"
      class="px-3 py-1 rounded border text-sm"
      :class="{
        'bg-blue-600 text-white': link.active,
        'text-gray-700 hover:bg-gray-100': !link.active,
        'cursor-not-allowed text-gray-400': !link.url,
      }"
    />
  </div>
</template>
