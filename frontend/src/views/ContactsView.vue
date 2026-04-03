<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Top bar -->
    <header class="bg-white border-b border-slate-200 shadow-sm">
      <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-base font-bold text-slate-800 leading-none">Handix</h1>
            <p class="text-xs text-slate-400 mt-0.5">Contact Manager</p>
          </div>
        </div>
        <span class="text-xs text-slate-400 font-medium">{{ store.contacts.length }} contact{{ store.contacts.length !== 1 ? 's' : '' }}</span>
      </div>
    </header>

    <!-- Page content -->
    <main class="max-w-5xl mx-auto px-6 py-8">

      <!-- Error banner -->
      <div v-if="store.error" class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        {{ store.error }}
      </div>

      <!-- Toolbar -->
      <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
          </svg>
          <input
            v-model="search"
            type="search"
            placeholder="Search by name or email…"
            @input="onSearch"
            class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors"
          />
        </div>
        <button
          @click="openCreate"
          class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm whitespace-nowrap"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New Contact
        </button>
      </div>

      <!-- Loading skeleton -->
      <div v-if="store.loading" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div v-for="i in 4" :key="i" class="flex items-center gap-4 px-5 py-4 border-b border-slate-100 last:border-0 animate-pulse">
          <div class="w-8 h-8 rounded-full bg-slate-200 shrink-0" />
          <div class="flex-1 space-y-2">
            <div class="h-3 w-40 bg-slate-200 rounded" />
            <div class="h-3 w-56 bg-slate-100 rounded" />
          </div>
        </div>
      </div>

      <!-- Contact list -->
      <ContactList
        v-else
        :contacts="store.contacts"
        @edit="openEdit"
        @delete="openDeleteConfirm"
      />
    </main>

    <!-- Delete confirmation dialog -->
    <ConfirmDialog
      :visible="!!contactToDelete"
      :title="`Delete ${contactToDelete?.name ?? 'contact'}?`"
      message="This contact will be permanently removed. This action cannot be undone."
      :loading="deleting"
      @confirm="executeDelete"
      @cancel="contactToDelete = null"
    />

    <!-- Form modal -->
    <ContactForm
      v-if="showForm"
      :contact="editing"
      :loading="store.saving"
      :errors="formErrors"
      @close="closeForm"
      @submit="onSubmit"
    />

    <!-- Toasts -->
    <ToastNotification ref="toastRef" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useContactStore } from '../stores/contactStore.js'
import ContactList from '../components/ContactList.vue'
import ContactForm from '../components/ContactForm.vue'
import ConfirmDialog from '../components/ConfirmDialog.vue'
import ToastNotification from '../components/ToastNotification.vue'

const store = useContactStore()

const search = ref('')
const showForm = ref(false)
const editing = ref(null)
const formErrors = ref({})
const contactToDelete = ref(null)
const deleting = ref(false)
const toastRef = ref(null)

let searchTimeout = null

function toast(message, type = 'success') {
  toastRef.value?.add(message, type)
}

function onSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => store.fetchContacts(search.value), 350)
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
  const isEditing = !!editing.value
  const result = isEditing
    ? await store.updateContact(editing.value.id, data)
    : await store.createContact(data)

  if (result.success) {
    toast(isEditing ? 'Contact updated successfully.' : 'Contact created successfully.')
    closeForm()
  } else {
    formErrors.value = result.errors ?? {}
  }
}

function openDeleteConfirm(contact) {
  contactToDelete.value = contact
}

async function executeDelete() {
  deleting.value = true
  const result = await store.deleteContact(contactToDelete.value.id)
  deleting.value = false

  if (result.success) {
    toast('Contact deleted successfully.')
    contactToDelete.value = null
  } else {
    toast(store.error ?? 'Failed to delete contact.', 'error')
    contactToDelete.value = null
  }
}

onMounted(() => store.fetchContacts())
</script>

