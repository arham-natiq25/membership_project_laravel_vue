import './bootstrap';
import { createApp } from 'vue';
import Welcome from './components/dashboard/Welcome.vue'
import Create from './components/membership/Create.vue'
import Frontend from './components/membership/Frontend.vue'
import Customer from "./components/customers/customer.vue"
import Trips from "./components/trips/Trips.vue"
import Location from "./components/Locations/Location.vue"
import Route from "./components/routes/Route.vue"
import TripView from "./components/trips/TripView.vue"
import CustomerTrips from "./components/Frontend/CustomerTrips.vue"
const app = createApp({});
app.component('welcome',Welcome);
app.component('front-page',Frontend);
app.component('membership-create',Create);
app.component('customer',Customer);
app.component('trip',Trips);
app.component('location',Location);
app.component('route',Route);
app.component('trip-view',TripView);
app.component('customer-trip',CustomerTrips);

app.mount("#app");



import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
