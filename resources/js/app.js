import { createApp } from 'vue';
import axios from 'axios';
import SearchBox from './components/SearchBox.vue';
import EnquiryForm from './components/EnquiryForm.vue';
import DeliveryModes from './components/DeliveryModes.vue';

const token = document.querySelector('meta[name="csrf-token"]')?.content;
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Blade pages drop in <div data-vue="name" data-props='{"..."}'></div> islands.
const components = {
    'search-box': SearchBox,
    'enquiry-form': EnquiryForm,
    'delivery-modes': DeliveryModes,
};

document.querySelectorAll('[data-vue]').forEach((el) => {
    const component = components[el.dataset.vue];
    if (!component) return;
    const props = el.dataset.props ? JSON.parse(el.dataset.props) : {};
    createApp(component, props).mount(el);
});

// Mobile nav toggle
document.querySelectorAll('[data-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.getElementById(btn.dataset.toggle)?.classList.toggle('hidden');
    });
});
