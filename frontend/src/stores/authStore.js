import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api.js'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('token') ?? null)
  const user = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  async function register(name, email, password, passwordConfirmation) {
    const { data } = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    })
    _setSession(data)
  }

  async function login(email, password) {
    const { data } = await api.post('/auth/login', { email, password })
    _setSession(data)
  }

  async function logout() {
    try {
      await api.post('/auth/logout')
    } finally {
      _clearSession()
    }
  }

  async function fetchMe() {
    try {
      const { data } = await api.get('/auth/me')
      user.value = data
    } catch {
      _clearSession()
    }
  }

  function _setSession(data) {
    token.value = data.access_token
    user.value = data.user
    localStorage.setItem('token', data.access_token)
  }

  function _clearSession() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  return { token, user, isAuthenticated, register, login, logout, fetchMe }
})
