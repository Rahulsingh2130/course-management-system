<script setup>
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
  placeholder: { type: String, default: 'Search for a course, e.g. PMP, Scrum, AWS...' },
  large: { type: Boolean, default: false },
  initial: { type: String, default: '' },
});

const query = ref(props.initial);
const results = ref([]);
const open = ref(false);
const loading = ref(false);
const listening = ref(false);
let timer = null;

const speechSupported = typeof window !== 'undefined' && (window.SpeechRecognition || window.webkitSpeechRecognition);

watch(query, (value) => {
  clearTimeout(timer);
  if (value.trim().length < 2) {
    results.value = [];
    open.value = false;
    return;
  }
  timer = setTimeout(async () => {
    loading.value = true;
    try {
      const { data } = await axios.get('/api/courses', { params: { search: value.trim() } });
      results.value = data.data.slice(0, 6);
      open.value = true;
    } finally {
      loading.value = false;
    }
  }, 250);
});

const showEmpty = computed(() => open.value && !loading.value && results.value.length === 0);

function startVoice() {
  const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  if (!Recognition) return;
  const rec = new Recognition();
  rec.lang = 'en-IN';
  rec.onstart = () => (listening.value = true);
  rec.onend = () => (listening.value = false);
  rec.onresult = (e) => (query.value = e.results[0][0].transcript);
  rec.start();
}

function closeSoon() {
  setTimeout(() => (open.value = false), 150);
}
</script>

<template>
  <form action="/courses" method="get" class="relative w-full" @focusout="closeSoon">
    <div class="flex items-center bg-white rounded-full shadow-md border border-slate-200 focus-within:ring-2 focus-within:ring-brand-500">
      <svg class="w-5 h-5 text-slate-400 ml-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" /><path d="m21 21-4.3-4.3" /></svg>
      <input
        v-model="query"
        name="q"
        type="search"
        autocomplete="off"
        :placeholder="placeholder"
        :class="['flex-1 bg-transparent outline-none px-3 text-slate-800', large ? 'py-4 text-base' : 'py-2.5 text-sm']"
        @focus="open = results.length > 0"
      />
      <button v-if="speechSupported" type="button" title="Voice search" class="p-2 text-slate-400 hover:text-brand-600" @click="startVoice">
        <svg :class="['w-5 h-5', listening ? 'text-red-500 animate-pulse' : '']" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="3" width="6" height="11" rx="3" /><path d="M5 11a7 7 0 0 0 14 0M12 18v3" /></svg>
      </button>
      <button type="submit" :class="['bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-full m-1', large ? 'px-7 py-3' : 'px-5 py-1.5 text-sm']">Search</button>
    </div>

    <div v-if="open" class="absolute z-30 left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden text-left">
      <a v-for="course in results" :key="course.id" :href="`/course/${course.slug}`" class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0">
        <span>
          <span class="block text-sm font-medium text-slate-800">{{ course.title }}</span>
          <span class="block text-xs text-slate-500">{{ course.category?.name }}</span>
        </span>
        <span class="text-xs text-brand-600 font-semibold">View →</span>
      </a>
      <div v-if="showEmpty" class="px-4 py-4 text-sm text-slate-600">
        No courses found. <a href="/contact" class="text-brand-600 font-medium underline">Can't find your course? Ask us.</a>
      </div>
    </div>
  </form>
</template>
