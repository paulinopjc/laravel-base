<script setup>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})
const emit = defineEmits(['update:modelValue'])

const content = ref(props.modelValue)
watch(() => props.modelValue, (val) => {
  if (val !== content.value) content.value = val
})
watch(content, (val) => {
  emit('update:modelValue', val)
})

// Will hold the Quill instance once the editor is mounted
const editorInstance = ref(null)

const modules = {
  toolbar: {
    container: [
      ['bold', 'italic', 'underline', 'strike'],
      [{ header: [1, 2, 3, false] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['image', 'video'],
    ]
  }
}

// Called when Quill is ready
function handleReady(quill) {
  editorInstance.value = quill

  // Add image handler
  quill.getModule('toolbar').addHandler('image', () => handleUpload('image'))
  quill.getModule('toolbar').addHandler('video', () => handleUpload('video'))
}

async function handleUpload(type) {
  const input = document.createElement('input')
  input.setAttribute('type', 'file')
  input.setAttribute('accept', type === 'video' ? 'video/*' : 'image/*')
  input.click()

  input.onchange = async () => {
    const file = input.files[0]
    if (!file) return

    const formData = new FormData()
    formData.append('file', file)

    try {
      const res = await fetch('/upload', {
        method: 'POST',
        body: formData
      })

      const data = await res.json()
      const quill = editorInstance.value
      const range = quill.getSelection(true)

      quill.insertEmbed(range.index, type, data.url)
      quill.setSelection(range.index + 1)
    } catch (err) {
      console.error('Upload failed:', err)
      alert('Upload failed')
    }
  }
}
</script>

<template>
  <QuillEditor
    v-model:content="content"
    contentType="html"
    theme="snow"
    :modules="modules"
    @ready="handleReady"
    class="min-h-[300px] border rounded"
  />
</template>
