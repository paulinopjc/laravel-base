<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref, reactive } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import LogoInput from '@/components/LogoInput.vue';

const props = defineProps({
  logos: Object,
})

// Inertia form state
const form = useForm({
  desktop_logo: null,
  mobile_logo: null,
  favicon: null,
  apple_touch_icon: null,
})

// Directly access errors and flash from the page props
const { errors, flash } = usePage().props

// Preview URLs for uploaded files
const previews = reactive({
  desktop_logo: props.logos.desktop_logo || null,
  mobile_logo: props.logos.mobile_logo || null,
  favicon: props.logos.favicon || null,
  apple_touch_icon: props.logos.apple_touch_icon || null,
})

const processing = ref(false)

function onFileSelected(name, file) {
  form[name] = file

  if (file) {
    previews[name] = URL.createObjectURL(file)
  } else {
    previews[name] = props.logos[name] || null
  }
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Logos',
        href: '/admin/settings/logos',
    },
];

function submit() {
  processing.value = true
  form.post(route('admin.settings.logos.update'), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      processing.value = false
    },
    onError: () => {
      processing.value = false
    },
  })
}
</script>

<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-4xl mx-auto p-4">
            <h1 class="text-2xl font-bold mb-6">Site Logos Settings</h1>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
            <LogoInput
                label="Desktop Logo"
                name="desktop_logo"
                :initial-url="previews.desktop_logo"
                @file-selected="onFileSelected"
                :error="errors.desktop_logo"
            />

            <LogoInput
                label="Mobile Logo"
                name="mobile_logo"
                :initial-url="previews.mobile_logo"
                @file-selected="onFileSelected"
                :error="errors.mobile_logo"
            />

            <LogoInput
                label="Favicon (ico, png)"
                name="favicon"
                :initial-url="previews.favicon"
                @file-selected="onFileSelected"
                :error="errors.favicon"
            />

            <LogoInput
                label="Apple Touch Icon"
                name="apple_touch_icon"
                :initial-url="previews.apple_touch_icon"
                @file-selected="onFileSelected"
                :error="errors.apple_touch_icon"
            />

            <div>
                <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                :disabled="processing"
                >
                Save Logos
                </button>
            </div>

            <div v-if="flash?.success" class="mt-4 text-green-600">
                {{ flash.success }}
            </div>
            </form>
        </div>
  </AdminLayout>
</template>

<script lang="ts">
export default {
  components: {
    LogoInput: {
      props: ['label', 'name', 'initialUrl', 'error'],
      emits: ['file-selected'],
      data() {
        return {
          preview: this.initialUrl || null,
          file: null,
        }
      },
      watch: {
        initialUrl(newUrl) {
          if (!this.file) {
            this.preview = newUrl
          }
        },
      },
      methods: {
        onFileChange(event) {
          const file = event.target.files[0]
          this.file = file || null
          if (file) {
            this.preview = URL.createObjectURL(file)
            this.$emit('file-selected', this.name, file)
          } else {
            this.preview = this.initialUrl || null
            this.$emit('file-selected', this.name, null)
          }
        },
      },
      template: `
        <div>
          <label :for="name" class="block font-semibold mb-1">{{ label }}</label>
          <input
            :id="name"
            type="file"
            :name="name"
            accept="image/*"
            @change="onFileChange"
            class="mb-2"
          />
          <div v-if="preview" class="mb-2">
            <img :src="preview" alt="Preview" class="max-h-24 max-w-full object-contain border rounded" />
          </div>
          <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>
        </div>
      `,
    },
  },
}
</script>