<template>
  <div class="page">
    <header class="page-header">
      <h1>Handix — Contact Manager</h1>
    </header>

    <!-- Feedback toast -->
    <div v-if="toast.message" :class="['toast', toast.type]">{{ toast.message }}</div>

    <div class="toolbar">
      <input
        v-model="search"
        type="search"
        placeholder="Search by name or email…"
        @input="onSearch"
      />
      <button class="btn-primary" @click="openCreate">+ New Contact</button>
    </div>

    <div v-if="listLoading" class="loading">Loading…</div>
    <ContactList
      v-else
      :contacts="contacts"
      @edit="openEdit"
      @delete="confirmDelete"
    />

    <ContactForm
      v-if="showForm"
      :contact="editing"
      :loading="formLoading"
      :errors="formErrors"
      @close="closeForm"
      @submit="onSubmit"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api.js'
import ContactList from '../components/ContactList.vue'
import ContactForm from '../components/ContactForm.vue'

const contacts = ref([])
const search = ref('')
const listLoading = ref(false)
const showForm = ref(false)
const editing = ref(null)
const formLoading = ref(false)
const formErrors = ref({})
const toast = ref({ message: '', type: 'success' })

let searchTimeout = null

function showToast(message, type = 'success') {
  toast.value = { message, type }
  setTimeout(() => (toast.value = { message: '', type: 'success' }), 3000)
}

async function loadContacts(q = '') {
  listLoading.value = true
  try {
    const { data } = await api.getContacts(q)
    contacts.value = data
  } catch {
    showToast('Failed to load contacts.', 'error')
  } finally {
    listLoading.value = false
  }
}

function onSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => loadContacts(search.value), 350)
}

function openCreate() {
  editing.value = null
  formErrors.value = {}
  showForm.value = true
}

function openEdit(contact) {
  editing.value = contact
  formErrors.value = {}
  showForm.value = true
}

function closeForm() {
  showForm.value = false
  editing.value = null
  formErrors.value = {}
}

async function onSubmit(data) {
  formLoading.value = true
  formErrors.value = {}
  try {
    if (editing.value) {
      await api.updateContact(editing.value.id, data)
      showToast('Contact updated.')
    } else {
      await api.createContact(data)
      showToast('Contact created.')
    }
    closeForm()
    loadContacts(search.value)
  } catch (err) {
    if (err.response?.status === 422) {
      formErrors.value = err.response.data.errors ?? {}
    } else {
      showToast(err.response?.data?.message ?? 'An error occurred.', 'error')
    }
  } finally {
    formLoading.value = false
  }
}

async function confirmDelete(contact) {
  if (!confirm(`Delete "${contact.name}"?`)) return
  try {
    await api.deleteContact(contact.id)
    showToast('Contact deleted.')
    loadContacts(search.value)
  } catch {
    showToast('Failed to delete contact.', 'error')
  }
}

onMounted(() => loadContacts())
</script>
