<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <h2>{{ contact ? 'Edit Contact' : 'New Contact' }}</h2>

      <form @submit.prevent="submit">
        <div class="field">
          <label>Name *</label>
          <input v-model="form.name" type="text" placeholder="Full name" required />
          <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>
        </div>

        <div class="field">
          <label>Email *</label>
          <input v-model="form.email" type="email" placeholder="email@example.com" required />
          <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
        </div>

        <div class="field">
          <label>Phone</label>
          <input v-model="form.phone" type="text" placeholder="+55 11 9 0000-0000" />
          <span v-if="errors.phone" class="error">{{ errors.phone[0] }}</span>
        </div>

        <div class="field">
          <label>Notes</label>
          <textarea v-model="form.notes" rows="3" placeholder="Optional notes"></textarea>
          <span v-if="errors.notes" class="error">{{ errors.notes[0] }}</span>
        </div>

        <div class="actions">
          <button type="button" class="btn-secondary" @click="$emit('close')">Cancel</button>
          <button type="submit" class="btn-primary" :disabled="loading">
            {{ loading ? 'Saving…' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  contact: { type: Object, default: null },
  loading: { type: Boolean, default: false },
  errors: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['close', 'submit'])

const form = ref({ name: '', email: '', phone: '', notes: '' })

watch(
  () => props.contact,
  (val) => {
    form.value = val
      ? { name: val.name, email: val.email, phone: val.phone ?? '', notes: val.notes ?? '' }
      : { name: '', email: '', phone: '', notes: '' }
  },
  { immediate: true },
)

function submit() {
  emit('submit', { ...form.value })
}
</script>
