import { createApp } from 'vue';
import CourseCatalog from './components/CourseCatalog.vue';
import WorkshopCalendar from './components/WorkshopCalendar.vue';

const app = createApp({});
app.component('course-catalog', CourseCatalog);
app.component('workshop-calendar', WorkshopCalendar);
app.mount('#app');
