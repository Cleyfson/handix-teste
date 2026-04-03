import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api.js'


export const useContactStore = defineStore('contacts', () => {
  const contacts = ref([])
  const loading = ref(false)
  const saving = ref(false)
  const error = ref(null)

  async function fetchContacts(search = '') {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.get('/contacts', { params: search ? { search } : {} })
      contacts.value = data
    } catch (err) {
      error.value = err.response?.data?.message ?? 'Failed to load contacts.'
    } finally {
      loading.value = false
    }
  }

  async function createContact(data) {
    saving.value = true
    error.value = null
    try {
      const { data: created } = await api.post('/contacts', data)
      contacts.value.push(created)
      return { success: true }
    } catch (err) {
      if (err.response?.status === 422) {
        return { success: false, errors: err.response.data.errors ?? {} }
      }
      error.value = err.response?.data?.message ?? 'Failed to create contact.'
      return { success: false, errors: {} }
    } finally {
      saving.value = false
    }
  }

  async function updateContact(id, data) {
    saving.value = true
    error.value = null
    try {
      const { data: updated } = await api.put(`/contacts/${id}`, data)
      const index = contacts.value.findIndex((c) => c.id === id)
      if (index !== -1) contacts.value[index] = updated
      return { success: true }
    } catch (err) {
      if (err.response?.status === 422) {
        return { success: false, errors: err.response.data.errors ?? {} }
      }
      error.value = err.response?.data?.message ?? 'Failed to update contact.'
      return { success: false, errors: {} }
    } finally {
      saving.value = false
    }
  }

  async function deleteContact(id) {
    error.value = null
    try {
      await api.delete(`/contacts/${id}`)
      contacts.value = contacts.value.filter((c) => c.id !== id)
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message ?? 'Failed to delete contact.'
      return { success: false }
    }
  }

  return {
    contacts,
    loading,
    saving,
    error,
    fetchContacts,
    createContact,
    updateContact,
    deleteContact,
  }
})
