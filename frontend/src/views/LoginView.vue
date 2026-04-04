<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore.js'

const router = useRouter()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const loading = ref(false)
const errors = ref({})
const serverError = ref('')

async function submit() {
  loading.value = true
  errors.value = {}
  serverError.value = ''
  try {
    await auth.login(email.value, password.value)
    router.push('/contacts')
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors ?? {}
    } else {
      serverError.value = err.response?.data?.message ?? 'Erro ao fazer login.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-1">Entrar</h1>
      <p class="text-sm text-gray-500 mb-6">
        Não tem conta?
        <router-link to="/register" class="text-indigo-600 hover:underline font-medium">Criar conta</router-link>
      </p>

      <div v-if="serverError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
        {{ serverError }}
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
          <input
            v-model="email"
            type="email"
            autocomplete="email"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="{ 'border-red-400': errors.email }"
          />
          <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
          <input
            v-model="password"
            type="password"
            autocomplete="current-password"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="{ 'border-red-400': errors.password }"
          />
          <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password[0] }}</p>
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2 rounded-lg text-sm transition-colors"
        >
          <span v-if="loading">Entrando…</span>
          <span v-else>Entrar</span>
        </button>
      </form>
    </div>
  </div>
</template>
