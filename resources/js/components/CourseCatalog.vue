<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const courses = ref([]);
const categories = ref([]);
const search = ref('');
const selectedCategory = ref('');
const loading = ref(true);

async function fetchCategories() {
  const { data } = await axios.get('/api/categories');
  categories.value = data;
}

async function fetchCourses() {
  loading.value = true;
  const { data } = await axios.get('/api/courses', {
    params: {
      search: search.value || undefined,
      category: selectedCategory.value || undefined,
    },
  });
  courses.value = data.data ?? data;
  loading.value = false;
}

let debounceTimer;
watch([search, selectedCategory], () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(fetchCourses, 300);
});

onMounted(() => {
  fetchCategories();
  fetchCourses();
});
</script>

<template>
  <section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">Browse Courses</h1>

    <div class="flex flex-col sm:flex-row gap-3 mb-8">
      <input
        v-model="search"
        type="text"
        placeholder="Search courses..."
        class="border border-slate-300 rounded-md px-4 py-2 flex-1"
      />
      <select v-model="selectedCategory" class="border border-slate-300 rounded-md px-4 py-2">
        <option value="">All categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.slug">
          {{ cat.name }} ({{ cat.courses_count }})
        </option>
      </select>
    </div>

    <div v-if="loading" class="text-slate-500">Loading courses...</div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow-sm border border-slate-100 p-5 hover:shadow-md transition"
      >
        <span class="text-xs uppercase tracking-wide text-indigo-600 font-medium">
          {{ course.category?.name }}
        </span>
        <h2 class="text-lg font-semibold mt-1">{{ course.title }}</h2>
        <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ course.description }}</p>
        <div class="mt-4 flex justify-between items-center">
          <span class="font-semibold">₹{{ course.price }}</span>
          <a :href="`/courses/${course.slug}`" class="text-sm text-indigo-600 hover:underline">
            View details →
          </a>
        </div>
      </div>

      <p v-if="courses.length === 0" class="text-slate-500 col-span-full">
        No courses match your filters yet.
      </p>
    </div>
  </section>
</template>
