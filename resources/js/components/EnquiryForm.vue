<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  type: { type: String, default: 'general' },
  courseId: { type: Number, default: null },
  variant: { type: String, default: 'full' }, // full | newsletter
  funding: { type: Boolean, default: false },
  company: { type: Boolean, default: false },
  message: { type: Boolean, default: false },
  submitLabel: { type: String, default: 'Get a Call Back' },
  user: { type: Object, default: () => ({}) },
});

const form = reactive({
  type: props.type,
  course_id: props.courseId,
  name: props.user.name || '',
  email: props.user.email || '',
  phone: '',
  company: '',
  funding_source: '',
  team_size: '',
  message: '',
});
const errors = ref({});
const success = ref('');
const failure = ref('');
const busy = ref(false);

async function submit() {
  busy.value = true;
  errors.value = {};
  failure.value = '';
  try {
    const payload = Object.fromEntries(Object.entries(form).filter(([, v]) => v !== '' && v !== null));
    const { data } = await axios.post('/enquiries', payload);
    success.value = data.message;
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors;
    } else {
      failure.value = 'Something went wrong. Please try again.';
    }
  } finally {
    busy.value = false;
  }
}

const input = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white';
</script>

<template>
  <div v-if="success" class="rounded-lg bg-green-50 border border-green-200 text-green-800 p-4 text-sm">
    <p class="font-semibold">✓ {{ success }}</p>
  </div>

  <form v-else-if="variant === 'newsletter'" class="flex gap-2" @submit.prevent="submit">
    <input v-model="form.email" type="email" required placeholder="Your email address" :class="input" />
    <button :disabled="busy" class="bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg px-4 text-sm disabled:opacity-60">Subscribe</button>
    <p v-if="errors.email" class="text-xs text-red-300 mt-1">{{ errors.email[0] }}</p>
  </form>

  <form v-else class="space-y-3" @submit.prevent="submit">
    <div v-if="funding">
      <select v-model="form.funding_source" :class="input">
        <option value="">Who is funding the training?</option>
        <option>Self-funded</option>
        <option>Employer</option>
        <option>Training budget</option>
      </select>
    </div>
    <div>
      <input v-model="form.name" type="text" required placeholder="Full name" :class="input" />
      <p v-if="errors.name" class="text-xs text-red-600 mt-1">{{ errors.name[0] }}</p>
    </div>
    <div>
      <input v-model="form.email" type="email" required placeholder="Email address" :class="input" />
      <p v-if="errors.email" class="text-xs text-red-600 mt-1">{{ errors.email[0] }}</p>
    </div>
    <div>
      <input v-model="form.phone" type="tel" placeholder="Phone number" :class="input" />
      <p v-if="errors.phone" class="text-xs text-red-600 mt-1">{{ errors.phone[0] }}</p>
    </div>
    <template v-if="company">
      <input v-model="form.company" type="text" placeholder="Company name" :class="input" />
      <input v-model="form.team_size" type="number" min="1" placeholder="Team size" :class="input" />
    </template>
    <textarea v-if="message" v-model="form.message" rows="3" placeholder="How can we help?" :class="input"></textarea>
    <p v-if="failure" class="text-sm text-red-600">{{ failure }}</p>
    <button :disabled="busy" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg px-4 py-3 text-sm disabled:opacity-60">
      {{ busy ? 'Sending...' : submitLabel }}
    </button>
    <p class="text-xs text-slate-500">By submitting you agree to be contacted about your enquiry.</p>
  </form>
</template>
