<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const workshops = ref([]);
const enrollingId = ref(null);
const feedback = ref('');

async function fetchWorkshops() {
  const { data } = await axios.get('/api/workshops/upcoming');
  workshops.value = data;
}

async function enroll(workshopId) {
  enrollingId.value = workshopId;
  feedback.value = '';
  try {
    const { data } = await axios.post(`/api/workshops/${workshopId}/enroll`);
    feedback.value = data.message;
    await fetchWorkshops();
  } catch (error) {
    feedback.value = error.response?.data?.message ?? 'Something went wrong.';
  } finally {
    enrollingId.value = null;
  }
}

function formatDate(iso) {
  return new Date(iso).toLocaleString('en-IN', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

onMounted(fetchWorkshops);
</script>

<template>
  <section class="max-w-6xl mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6">Upcoming Workshops</h2>

    <p v-if="feedback" class="mb-4 text-sm text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-md px-4 py-2">
      {{ feedback }}
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div
        v-for="workshop in workshops"
        :key="workshop.id"
        class="bg-white rounded-lg border border-slate-100 shadow-sm p-5"
      >
        <h3 class="font-semibold">{{ workshop.batch_name }}</h3>
        <p class="text-sm text-slate-500">{{ workshop.course_title }}</p>
        <p class="text-sm text-slate-500 mt-1">Starts: {{ formatDate(workshop.starts_at) }}</p>

        <div class="mt-3 flex justify-between items-center">
          <span class="text-xs" :class="workshop.is_full ? 'text-red-600' : 'text-green-600'">
            {{ workshop.is_full ? 'Fully booked' : `${workshop.seats_remaining} seats left` }}
          </span>
          <button
            :disabled="workshop.is_full || enrollingId === workshop.id"
            @click="enroll(workshop.id)"
            class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-indigo-700"
          >
            {{ enrollingId === workshop.id ? 'Enrolling...' : 'Enroll' }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
