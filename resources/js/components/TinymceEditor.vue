<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import tinymce from 'tinymce/tinymce'

import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/models/dom'
import 'tinymce/plugins/link'
import 'tinymce/plugins/table'
import 'tinymce/plugins/code'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/image'
import 'tinymce/plugins/media'
import 'tinymce/plugins/autoresize'

import 'tinymce/skins/ui/oxide/skin.min.css'

const props = defineProps({
  modelValue: String,
  elementId: {
    type: String,
    default: 'tinymce-editor'
  },
  initOptions: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['update:modelValue'])

// Store selected files for later upload
const selectedFiles = ref([]) // array of { blobUrl, file }

let editorInstance

function storeSelectedFile(blobUrl, file) {
  selectedFiles.value.push({ blobUrl, file })
}

// Function to upload one file to server
async function uploadFileToServer(file) {
  const formData = new FormData()
  formData.append('file', file)

  const response = await fetch('/upload-editor-media', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
    },
    body: formData
  })

  if (!response.ok) {
    const errorData = await response.json()
    throw new Error(errorData.error || response.statusText)
  }

  const result = await response.json()

  if (!result.src || typeof result.src !== 'string') {
    throw new Error('Upload failed: no valid src URL returned.')
  }

  return result.src
}

// This function uploads all pending selected files,
// replaces blob URLs in content with the real URLs,
// clears the selectedFiles array,
// and returns the final updated content string.
async function uploadPendingFiles() {
  if (!editorInstance) return props.modelValue || ''

  let content = editorInstance.getContent()

  for (const item of selectedFiles.value) {
    try {
      const realUrl = await uploadFileToServer(item.file)
      // Replace blobUrl in content with realUrl
      const escapedBlobUrl = item.blobUrl.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')
      const regex = new RegExp(escapedBlobUrl, 'g')
      content = content.replace(regex, realUrl)
    } catch (err) {
      console.error('File upload failed:', err)
      alert('File upload failed: ' + err.message)
    }
  }

  selectedFiles.value = [] // clear after upload

  // Optionally update editor content with replaced URLs
  editorInstance.setContent(content)

  return content
}

onMounted(() => {
  tinymce.init({
    target: document.getElementById(props.elementId),
    plugins: 'link table code lists image media autoresize',
    toolbar:
      'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | customInsertButton | code',
    menubar: 'file edit view insert format tools table help',
    skin: false,
    content_css: false,
    automatic_uploads: false, // IMPORTANT: disable auto uploads

    file_picker_callback: (callback, value, meta) => {
        if (meta.filetype === 'image' || meta.filetype === 'media') {
            // Open a custom TinyMCE modal dialog
            editorInstance.windowManager.open({
            title: 'Select ' + (meta.filetype === 'image' ? 'Image' : 'Media'),
            body: {
                type: 'panel',
                items: [
                {
                    type: 'htmlpanel',
                    html: `
                    <input type="file" id="custom-file-input" accept="${meta.filetype === 'image' ? 'image/*' : 'video/*'}" />
                    <p id="upload-status" style="color: red; display:none;"></p>
                    `
                }
                ]
            },
            buttons: [
                {
                type: 'cancel',
                text: 'Cancel'
                },
                {
                type: 'submit',
                text: 'Save',
                primary: true
                }
            ],
            onSubmit: async (api) => {
                const input = document.getElementById('custom-file-input')
                const status = document.getElementById('upload-status')
                if (!input.files || input.files.length === 0) {
                status.style.display = 'block'
                status.textContent = 'No file selected.'
                return
                }

                const file = input.files[0]
                const formData = new FormData()
                formData.append('file', file)

                status.style.display = 'block'
                status.style.color = 'black'
                status.textContent = 'Uploading...'

                try {
                const response = await fetch('/upload-editor-media', {
                    method: 'POST',
                    headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                })

                if (!response.ok) {
                    const errorData = await response.json()
                    status.style.color = 'red'
                    status.textContent = 'Upload failed: ' + (errorData.error || response.statusText)
                    return
                }

                const result = await response.json()

                if (!result.src || typeof result.src !== 'string') {
                    status.style.color = 'red'
                    status.textContent = 'Upload failed: no valid src URL returned.'
                    return
                }

                callback(result.src)  // Insert the uploaded file URL into editor

                api.close()
                } catch (error) {
                status.style.color = 'red'
                status.textContent = 'Upload error: ' + error.message
                }
            }
            })
        }
    },

    setup(editor) {
      editor.on('init', () => {
        const baseUrl = window.location.origin
        // Convert relative src URLs to absolute URLs in initial content
        const contentWithAbsoluteUrls = (props.modelValue || '').replace(
          /src="(\.\.\/)+storage\/cms\//g,
          `src="${baseUrl}/storage/cms/`
        )
        editor.setContent(contentWithAbsoluteUrls)
      })

      editor.on('Change KeyUp', () => {
        emit('update:modelValue', editor.getContent())
      })

      editor.ui.registry.addButton('customInsertButton', {
        text: 'Insert Greeting',
        icon: 'emoji',
        onAction: () => {
          editor.insertContent('<p>👋 Hello from custom button!</p>')
        }
      })

      editorInstance = editor
    },

    ...props.initOptions
  })
})

onBeforeUnmount(() => {
  if (editorInstance) {
    editorInstance.destroy()
    editorInstance = null
  }
})

watch(() => props.modelValue, (newVal) => {
  if (editorInstance && editorInstance.getContent() !== newVal) {
    editorInstance.setContent(newVal || '')
  }
})

/**
 * Expose the uploadPendingFiles function so your parent
 * component or caller can trigger the upload on Save:
 *
 * Example usage in parent:
 *  const tinymceRef = ref(null)
 *  ...
 *  const save = async () => {
 *    const finalContent = await tinymceRef.value.uploadPendingFiles()
 *    // then send finalContent to your backend API to save
 *  }
 */
defineExpose({ uploadPendingFiles })
</script>
<!-- resources/js/Components/TinymceEditor.vue -->
<template>
  <textarea :id="elementId" :value="modelValue"></textarea>
</template>