<template>
  <div class="table-wrapper">
    <p v-if="contacts.length === 0" class="empty">No contacts found.</p>

    <table v-else>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contact in contacts" :key="contact.id">
          <td>{{ contact.name }}</td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.phone || '—' }}</td>
          <td class="notes-cell">{{ contact.notes || '—' }}</td>
          <td class="actions-cell">
            <button class="btn-edit" @click="$emit('edit', contact)">Edit</button>
            <button class="btn-delete" @click="$emit('delete', contact)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  contacts: { type: Array, required: true },
})

defineEmits(['edit', 'delete'])
</script>
