<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import RichTextEditor from '@/components/RichTextEditor.vue'
import TinymceEditor from '@/components/TinymceEditor.vue'

const form = useForm({
    title: '',
    slug: '',
    content: '',
    is_published: 0,
});

const submit = () => {
  form.post(route('admin.cms-pages.store'));
};

</script>

<template>
  <Head title="Add a User Role" />

  <AppLayout>
    <div class="max-w-xl mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Add a CMS Page</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Title</label>
          <input v-model="form.title" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Url</label>
          <input v-model="form.slug" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.slug" class="text-red-500 text-sm">{{ form.errors.slug }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Content</label>
          <!-- Need to fix RTE -->
          <!--<RichTextEditor v-model="form.content" />-->
          <!--<textarea v-model="form.content" class="mt-1 w-full rounded border p-2"></textarea>-->
          <TinymceEditor v-model="form.content" element-id="content-editor" />
          <div v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</div>
        </div>

        <ToggleSwitch
            v-model="form.is_published"
            :error="form.errors.is_published"
            id="status"
        >
            <template #label>Status</template>
        </ToggleSwitch>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Create
        </button>
      </form>
    </div>
  </AppLayout>
</template>
