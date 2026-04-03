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
        @delete="confirmDelete"
      />
    </main>

    <!-- Toast -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="toast.message"
        :class="[
          'fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold',
          toast.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'
        ]"
      >
        <svg v-if="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{ toast.message }}
      </div>
    </transition>

    <!-- Form modal -->
    <ContactForm
      v-if="showForm"
      :contact="editing"
      :loading="store.saving"
      :errors="formErrors"
      @close="closeForm"
      @submit="onSubmit"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useContactStore } from '../stores/contactStore.js'
import ContactList from '../components/ContactList.vue'
import ContactForm from '../components/ContactForm.vue'

const store = useContactStore()

const search = ref('')
const showForm = ref(false)
const editing = ref(null)
const formErrors = ref({})
const toast = ref({ message: '', type: 'success' })

let searchTimeout = null

function showToast(message, type = 'success') {
  toast.value = { message, type }
  setTimeout(() => (toast.value = { message: '', type: 'success' }), 3000)
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
  const result = editing.value
    ? await store.updateContact(editing.value.id, data)
    : await store.createContact(data)

  if (result.success) {
    showToast(editing.value ? 'Contact updated.' : 'Contact created.')
    closeForm()
  } else {
    formErrors.value = result.errors ?? {}
  }
}

async function confirmDelete(contact) {
  if (!confirm(`Delete "${contact.name}"?`)) return
  const result = await store.deleteContact(contact.id)
  if (result.success) {
    showToast('Contact deleted.')
  } else {
    showToast(store.error ?? 'Failed to delete.', 'error')
  }
}

onMounted(() => store.fetchContacts())
</script>
