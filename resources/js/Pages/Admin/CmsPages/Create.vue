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
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    content: '',
    is_published: 0,
});

// Reactive array for individual keywords (start from splitting initial form meta_keywords)
const keywords = ref<string[]>([]);

// Sync form.meta_keywords whenever keywords array changes
watch(keywords, (newVal) => {
  form.meta_keywords = newVal.join(',');
});

// On mount, populate keywords array if form.meta_keywords has initial values
if (form.meta_keywords) {
  keywords.value = form.meta_keywords.split(',').map(k => k.trim()).filter(k => k.length > 0);
}

const newKeyword = ref('');

// Add keyword on Enter key press
function addKeyword() {
  const val = newKeyword.value.trim();
  if (val && !keywords.value.includes(val)) {
    keywords.value.push(val);
  }
  newKeyword.value = '';
}

// Remove keyword by index
function removeKeyword(index: number) {
  keywords.value.splice(index, 1);
}

const submit = () => {
  form.meta_keywords = keywords.value.join(',');
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
          <label class="block text-sm font-medium">Meta Title</label>
          <input v-model="form.meta_title" type="text" class="mt-1 w-full rounded border p-2" />
          <div v-if="form.errors.meta_title" class="text-red-500 text-sm">{{ form.errors.meta_title }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium">Meta Description</label>
          <textarea v-model="form.meta_description" rows="4" class="mt-1 w-full rounded border p-2"></textarea>
          <div v-if="form.errors.meta_description" class="text-red-500 text-sm">{{ form.errors.meta_description }}</div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Meta Keywords</label>
          
          <input
            type="text"
            v-model="newKeyword"
            @keydown.enter.prevent="addKeyword"
            placeholder="Type a keyword and press Enter"
            class="mt-1 w-full rounded border p-2"
          />
          <div class="flex flex-wrap mt-2 gap-2">
            <span
              v-for="(keyword, index) in keywords"
              :key="index"
              class="bg-blue-500 text-white px-3 py-1 rounded-full flex items-center space-x-2"
            >
              <span>{{ keyword }}</span>
              <button
                type="button"
                @click="removeKeyword(index)"
                class="text-white hover:text-gray-200"
                aria-label="Remove keyword"
              >
                &times;
              </button>
            </span>
          </div>

          <div v-if="form.errors.meta_keywords" class="text-red-500 text-sm mt-1">
            {{ form.errors.meta_keywords }}
          </div>
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
