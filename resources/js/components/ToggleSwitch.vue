<script setup>
defineProps({
  modelValue: [Boolean, Number],
  id: {
    type: String,
    default: 'toggle',
  },
  error: {
    type: String,
    default: '',
  },
});
defineEmits(['update:modelValue']);
</script>

<style scoped>
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  background-color: #ccc;
  border-radius: 24px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  transition: 0.4s;
}

.slider:before {
  content: "";
  position: absolute;
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #4f46e5; /* Tailwind indigo-600 */
}

input:checked + .slider:before {
  transform: translateX(26px);
}
</style>

<template>
  <div>
    <label class="block text-sm font-medium">
      <slot name="label">Status</slot>
    </label>
    <label class="toggle-switch">
      <input
        type="checkbox"
        :id="id"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked ? 1 : 0)"
      />
      <span class="slider"></span>
    </label>
    <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>
  </div>
</template>