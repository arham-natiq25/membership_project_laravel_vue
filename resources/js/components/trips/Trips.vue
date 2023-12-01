<template>
    <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
    </div>
    <!-- Form where take input  -->
    <div v-if="createTripForm">
        <button class="btn btn-default my-2" @click="showTripRecords">Back</button>
        <h5>Trip Details</h5>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="trip_name">Enter Trip name</label>
                    <input type="text" class="form-control" id="trip_name" name="trip_name" v-model="items.trip_name" />
                    <span v-if="errors.trip_name" class="text-danger">{{
                        errors.trip_name[0]
                    }}</span>
                </div>
            </div>
            <div></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="trip_date">Enter Trip Date</label>
                    <div class="input-group">
                        <flat-pickr class="form-control" :config="config" id="trip_date" name="trip_date"
                            v-model="items.trip_date"></flat-pickr>
                    </div>
                    <span v-if="errors.trip_date" class="text-danger">{{
                        errors.trip_date[0]
                    }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="booking_date">Booking Close Date</label>
                    <flat-pickr class="form-control" :config="config" id="booking_date" name="booking_date"
                        v-model="items.booking_close_date"></flat-pickr>
                    <span v-if="errors.booking_close_date" class="text-danger">{{
                        errors.booking_close_date[0]
                    }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" v-model="items.price" id="price" name="price" />
                    <span v-if="errors.price" class="text-danger">{{
                        errors.price[0]
                    }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="close_trip_date">Close Trip Date</label>
                    <flat-pickr class="form-control" :config="config" id="close_trip_date" name="close_trip_date"
                        v-model="items.close_trip_booking"></flat-pickr>
                    <span v-if="errors.close_trip_booking" class="text-danger">{{
                        errors.close_trip_booking[0]
                    }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="auto_activation_date">Auto Activation Date</label>
                    <flat-pickr class="form-control" :config="config" id="auto_date" name="auto_date"
                        v-model="items.auto_activation_date"></flat-pickr>
                    <span v-if="errors.auto_activation_date" class="text-danger">{{
                        errors.auto_activation_date[0]
                    }}</span>
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 35px">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="status" id="status" value="1"
                            v-model="items.status" />
                        <label for="status" class="custom-control-label"> Status</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 35px">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="night" id="night" value="1"
                            v-model="items.night" />
                        <label for="night" class="custom-control-label"> Night</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="routes">Select Route</label>
                    <select class="form-control select2" v-model="items.route_id" style="width: 100%">
                        <option value="" disabled>Select</option>
                        <option :value="route.id" v-for="route in routes" :key="route.id">
                            {{ route.title }}
                        </option>
                    </select>
                    <span v-if="errors.route_id" class="text-danger">Please Select Your Route</span>
                </div>
            </div>
        </div>

        <button class="btn btn-primary" @click="saveTrip">
            {{ isEditing ? "Update" : "Save" }}
        </button>
    </div>
    <!-- input form ends -->

    <!-- All Details section  -->

    <div v-if="allTrips">
        <div class="row">
        <div class="col-md-12 m-3"> <!-- Set the column width to 12 to take up full width -->
            <div class="form-inline">
                <div class="input-group" style="width: 100%;"> <!-- Set width to 100% -->
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                         style="width: 60%;" />
                    <div class="input-group-append">
                        <button class="btn btn-primary">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-md-12">
            <button class="btn btn-primary mx-2" @click="showTripform">Create</button>
            <BuyTrip></BuyTrip>
            <div class="col-md-12 mt-2">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="nav-item ">
                        <a class="nav-link active" id="allTrips" data-toggle="pill" href="#custom-tabs-two-home" role="tab"
                            aria-controls="custom-tabs-two-home" aria-selected="true">All Trips</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="activeTrips" data-toggle="pill" href="#custom-tabs-two-active" role="tab"
                            aria-controls="custom-tabs-two-active" aria-selected="false">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="archiveTrips" data-toggle="pill" href="#custom-tabs-two-profile" role="tab"
                            aria-controls="custom-tabs-two-profile" aria-selected="false">Archieved</a>
                    </li>
                </ul>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel"
                            aria-labelledby="allTrips">
                            <div class="col-md-12 mt-2">
                                <div class="table-responsive">
                                    <table class="table table-secondary table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 20%">Trip Name</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Route</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, index) in list" :key="data.id">
                                                <td scope="row">{{ index + 1 }}</td>
                                                <td>{{ data.trip_name }}</td>
                                                <td>{{ formatDateString(data.trip_date) }}</td>
                                                <td>{{ data.route.title }}</td>
                                                <td>${{ formatPrice(data.price) }}</td>
                                                <td>{{ data.status == 1 ? "Active" : "Inactive" }}</td>
                                                <td>
                                                    <i class="fas fa-trash-alt btn btn-danger text-white btn-sm"
                                                        @click="deleteTrip(data.id)"></i>
                                                    <i class="far fa-edit btn btn-primary text-white btn-sm mx-2"
                                                        @click="editTrip(data)"></i>
                                                    <i class="far fa-eye btn btn-primary text-white btn-sm"
                                                        @click="viewInfo(data)"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show " id="custom-tabs-two-active" role="tabpanel"
                            aria-labelledby="allTrips">
                            <div class="col-md-12 mt-2">
                                <div class="table-responsive">
                                    <table class="table table-secondary table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 20%">Trip Name</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Route</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, index) in active" :key="data.id">
                                                <td scope="row">{{ index + 1 }}</td>
                                                <td>{{ data.trip_name }}</td>
                                                <td>{{ formatDateString(data.trip_date) }}</td>
                                                <td>{{ data.route.title }}</td>
                                                <td>${{ formatPrice(data.price) }}</td>
                                                <td>{{ data.status == 1 ? "Active" : "Inactive" }}</td>
                                                <td>
                                                    <i class="fas fa-trash-alt btn btn-danger text-white btn-sm"
                                                        @click="deleteTrip(data.id)"></i>
                                                    <i class="far fa-edit btn btn-primary text-white btn-sm mx-2"
                                                        @click="editTrip(data)"></i>
                                                    <i class="far fa-eye btn btn-primary text-white btn-sm"
                                                        @click="viewInfo(data)"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel"
                            aria-labelledby="archiveTrips">
                            <div class="col-md-12 mt-2">
                                <div class="table-responsive">
                                    <table class="table table-secondary table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 20%">Trip Name</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Route</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, index) in archived" :key="data.id">
                                                <td scope="row">{{ index + 1 }}</td>
                                                <td>{{ data.trip_name }}</td>
                                                <td>{{ formatDateString(data.trip_date) }}</td>
                                                <td>{{ data.route.title }}</td>
                                                <td>${{ formatPrice(data.price) }}</td>
                                                <td>{{ data.status == 1 ? "Active" : "Inactive" }}</td>
                                                <td>
                                                    <i class="fas fa-trash-alt btn btn-danger text-white btn-sm"
                                                        @click="deleteTrip(data.id)"></i>
                                                    <i class="far fa-edit btn btn-primary text-white btn-sm mx-2"
                                                        @click="editTrip(data)"></i>
                                                    <i class="far fa-eye btn btn-primary text-white btn-sm"
                                                        @click="viewInfo(data)"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>


    <!-- // Member Show Area  -->
</template>

<script>
import axios from "axios";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import BuyTrip from "./BuyTrip.vue";
export default {
    name: "Trips",
    components: {
        flatPickr,
        BuyTrip

    },
    data() {
        return {
            config: {
                enableTime: true, // Enable time selection
                dateFormat: "Y-m-d H:i:S", // Format as '2024-08-16 09:12:00'
                allowInput: true,
            },
            list: [],
            routes: [],
            items: {
                trip_name: "",
                trip_date: "",
                booking_close_date: "",
                price: "",
                close_trip_booking: "",
                auto_activation_date: "",
                status: false,
                night: false,
                route_id: "",
                ViewMembers: false
            },
            errors: {},
            successMessage: "",
            isEditing: false,
            temp_id: null,
            createTripForm: false,
            allTrips: true,
            selectedLocation: null,
            showTripInfo: false,
            showModal: false,
            archived: [],
            active: [],
        };
    },
    mounted() {
        this.getTrips(), this.getRoutes(), this.getArchievedTrips(), this.getActiveTrips();
    },
    computed: {

    },
    methods: {
        formatDateString(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                const options = { year: "numeric", month: "long", day: "numeric" };
                return date.toLocaleDateString("en-US", options);
            }
            return "";
        },
        getActiveTrips() {
            axios.get('/api/trips/active').then((res) => {
                this.active = res.data;
            });
        },
        getArchievedTrips() {
            axios.get('/api/trips/archieved').then((res) => {
                this.archived = res.data;
            });
        },
        isTripActive(tripDate) {
            const today = new Date();
            const tripDateTime = new Date(tripDate);
            // Compare the dates without considering the time
            today.setHours(0, 0, 0, 0);
            tripDateTime.setHours(0, 0, 0, 0);


            return tripDateTime >= today;
        },
        goBack() {
            (this.showTripInfo = false),
                // this.showTrips=true
                (this.getData = []);
            this.allTrips = true;
        },

        viewInfo(data) {
            window.location.href = `/trip/${data.id}/view`;
        },
        showTripform() {
            this.createTripForm = true;
            this.allTrips = false;
        },
        showTripRecords() {
            (this.createTripForm = false), (this.allTrips = true);
            this.getTrips();
            this.errors = {};
            this.items = {
                trip_name: "",
                trip_date: "",
                booking_close_date: "",
                price: "",
                close_trip_booking: "",
                auto_activation_date: "",
                status: "",
                night: "",
                route_id: "",
            };
            this.isEditing = false;
            this.temp_id = null;
        },
        formatPrice(price) {
            return `${price.toFixed(2)}`;
        },
        getTrips() {
            axios.get("/api/trips").then((res) => {
                this.list = res.data;
            });
        },
        getRoutes() {
            axios.get("/api/routes").then((res) => {
                this.routes = res.data;
            });
        },
        // SAVE IN DATABASE
        saveTrip() {
            let method = axios.post;
            let url = "/api/trips";

            if (this.isEditing) {
                method = axios.put;
                url = `/api/trips/${this.temp_id}`;
            }

            try {
                method(url, this.items)
                    .then(async (res) => {
                        this.getTrips();
                        (this.items = {
                            trip_name: "",
                            trip_date: "",
                            booking_close_date: "",
                            price: "",
                            close_trip_booking: "",
                            auto_activation_date: "",
                            status: "",
                            night: "",
                            route_id: "",
                        }),
                            (this.createTripForm = false);
                        this.successMessage = res.data.message;
                        this.allTrips = true;
                        this.isEditing = false;
                        this.temp_id = null;
                        this.errors = {};
                        setTimeout(() => {
                            this.successMessage = "";
                        }, 5000);
                    })
                    .catch((error) => {
                        if (error.response && error.response.data) {
                            const { data } = error.response;
                            if (data.errors) {
                                this.errors = data.errors;
                            }
                        }
                    });
            } catch (error) {
                console.log(error);
            }
        },

        editTrip(data) {
            this.items = {
                trip_name: data.trip_name,
                trip_date: data.trip_date,
                booking_close_date: data.booking_close_date,
                price: data.price,
                close_trip_booking: data.close_trip_booking,
                auto_activation_date: data.auto_activation_date,
                status: data.status === 1,
                night: data.night === 1,
                route_id: data.route_id,
            };
            (this.temp_id = data.id),
                (this.isEditing = true),
                (this.createTripForm = true);
            this.allTrips = false;
        },

        async deleteTrip(id) {
            try {
                // on click a popup shows of sweet alert
                const confirmed = await Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this trip!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                });

                // if click on yes ... request using axios and deleted and show message ..
                if (confirmed.isConfirmed) {
                    await axios.delete(`/api/trips/${id}`);
                    this.getTrips();
                    Swal.fire("Deleted!", "Your trip has been deleted.", "success");
                }
                // if getting any error in deleting
            } catch (error) {
                console.error(error);
                Swal.fire(
                    "Error",
                    "An error occurred while deleting the trip.",
                    "error"
                );
            }
        },

    },
};
</script>
