<script setup>
import { ref } from 'vue';

const modes = [
  {
    key: 'online_instructor',
    label: 'Online Instructor-led',
    text: 'Join live, interactive sessions with an expert trainer from anywhere. Ask questions in real time, work in breakout groups and get the same certified material as classroom learners.',
    points: ['Live trainer & peer interaction', 'Recordings for revision', 'No travel, flexible batches'],
  },
  {
    key: 'online_self_paced',
    label: 'Online Self-paced',
    text: 'Learn on your own schedule with on-demand video lessons, quizzes and practice exams. Ideal for busy professionals who prefer to set their own pace.',
    points: ['Lifetime-style access during term', 'Practice exams & quizzes', 'Most affordable option'],
  },
  {
    key: 'classroom',
    label: 'Classroom',
    text: 'Immersive face-to-face training in our partner venues across major Indian cities. Networking, hands-on workshops and refreshments included.',
    points: ['Venues in 6+ cities', 'Hands-on group exercises', 'Meals & materials included'],
  },
  {
    key: 'onsite',
    label: 'Onsite',
    text: 'We bring the trainer to your office. Tailored content, private cohorts and flexible dates for teams of any size.',
    points: ['Customised to your goals', 'Private team cohort', 'Cost-effective for groups'],
  },
];
const active = ref(modes[0]);
</script>

<template>
  <div class="grid md:grid-cols-3 gap-6">
    <div class="space-y-2">
      <button
        v-for="m in modes"
        :key="m.key"
        :class="['w-full text-left px-5 py-4 rounded-xl border font-semibold transition', active.key === m.key ? 'bg-brand-600 text-white border-brand-600 shadow' : 'bg-white text-slate-700 border-slate-200 hover:border-brand-400']"
        @click="active = m"
      >
        {{ m.label }}
      </button>
    </div>
    <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 p-8">
      <h3 class="text-xl font-bold text-slate-900">{{ active.label }}</h3>
      <p class="mt-3 text-slate-600 leading-relaxed">{{ active.text }}</p>
      <ul class="mt-5 space-y-2">
        <li v-for="p in active.points" :key="p" class="flex items-center gap-2 text-slate-700">
          <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m5 13 4 4L19 7" /></svg>
          {{ p }}
        </li>
      </ul>
      <a :href="`/courses?mode=${active.key}`" class="inline-block mt-6 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm">Browse {{ active.label }} courses</a>
    </div>
  </div>
</template>
