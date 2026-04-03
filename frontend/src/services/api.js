import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8080/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

export default {
  getContacts(search = '') {
    return api.get('/contacts', { params: search ? { search } : {} })
  },
  createContact(data) {
    return api.post('/contacts', data)
  },
  updateContact(id, data) {
    return api.put(`/contacts/${id}`, data)
  },
  deleteContact(id) {
    return api.delete(`/contacts/${id}`)
  },
}
