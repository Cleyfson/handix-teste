<template>
  <teleport to="body">
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-2.5 items-end pointer-events-none">
      <transition-group
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition ease-in duration-200 absolute"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 translate-y-2"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold min-w-[220px] max-w-sm"
          :class="{
            'bg-emerald-600 text-white': toast.type === 'success',
            'bg-red-600 text-white':     toast.type === 'error',
            'bg-amber-500 text-white':   toast.type === 'warning',
          }"
        >
          <!-- Icon -->
          <div class="shrink-0">
            <svg v-if="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else-if="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
            </svg>
          </div>

          <span class="flex-1">{{ toast.message }}</span>

          <!-- Progress bar -->
          <div class="absolute bottom-0 left-0 h-0.5 rounded-b-xl bg-white/30 transition-all ease-linear"
            :style="{ width: toast.progress + '%', transitionDuration: toast.duration + 'ms' }"
          />
        </div>
      </transition-group>
    </div>
  </teleport>
</template>

<script setup>
import { reactive } from 'vue'

const toasts = reactive([])
let counter = 0

function add(message, type = 'success', duration = 3500) {
  const id = ++counter
  const toast = { id, message, type, duration, progress: 100 }
  toasts.push(toast)

  // Start shrinking progress bar on next tick
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      toast.progress = 0
    })
  })

  setTimeout(() => {
    const index = toasts.findIndex((t) => t.id === id)
    if (index !== -1) toasts.splice(index, 1)
  }, duration + 300)
}

defineExpose({ add })
</script>
