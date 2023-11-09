import './bootstrap';
import { createApp } from 'vue';
import Welcome from './components/dashboard/Welcome.vue'
import Create from './components/membership/Create.vue'
import Frontend from './components/membership/Frontend.vue'
import Customer from "./components/customers/customer.vue"

const app = createApp({});
app.component('welcome',Welcome);
app.component('front-page',Frontend);
app.component('membership-create',Create);
app.component('customer',Customer);

app.mount("#app");



import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
