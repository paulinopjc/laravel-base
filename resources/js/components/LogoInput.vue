<template>
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
</template>

<script>
export default {
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
}
</script>
