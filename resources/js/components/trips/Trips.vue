<template>
    <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
    </div>
    <div v-if="showModal">
        <div class="modal fade show" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Seats for Location</h4>
                        <button type="button" class="close" @click="closeModal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="seats">Seats</label>
                                <input type="text" class="form-control" id="seats" name="seats" v-model="seats" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" @click="closeModal">
                            Close
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="updateSeats">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
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

    <div v-if="allTrips" class="row m-2">
        <div class="col-md-12">
            <button class="btn btn-primary" @click="showTripform">Create</button>
            <button class="btn btn-warning mx-2">Buy a Trip</button>
            <!-- <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Bootstrap Switch</h3>
              </div>
              <div class="card-body">
                <input type="checkbox" name="my-checkbox" checked data-bootstrap-switch>
                <input type="checkbox" name="my-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
              </div>
                </div> -->
            <div class="col-md-12 mt-2">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                    <li class="nav-item ">
                        <a class="nav-link active" id="allTrips" data-toggle="pill" href="#custom-tabs-two-home" role="tab"
                            aria-controls="custom-tabs-two-home" aria-selected="true">All Trips</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="archiveTrips" data-toggle="pill" href="#custom-tabs-two-profile" role="tab"
                            aria-controls="custom-tabs-two-profile" aria-selected="false">Archievd</a>
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
                                                <th scope="col" style="width: 40%">Trip Name</th>
                                                <th scope="col">Route</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, index) in activeTrips" :key="data.id">
                                                <td scope="row">{{ index + 1 }}</td>
                                                <td>{{ data.trip_name }}</td>
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
                                                <th scope="col" style="width: 40%">Trip Name</th>
                                                <th scope="col">Route</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, index) in archivedTrips" :key="data.id">
                                                <td scope="row">{{ index + 1 }}</td>
                                                <td>{{ data.trip_name }}</td>
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
    <div v-if="showTripInfo">
        <h4>View Trip</h4>
        <br />
        <button class="btn btn-default my-3" @click="goBack">Back</button>
        <div class="row">
            <div class="col-2 fw-bold">
                <label for=""> Trip Info </label>
            </div>
        </div>
        <div class="row">
            <div class="col-4">{{ formatDateString(getData.trip_date) ?? "" }}</div>
            <div class="col-md-3">{{ getDayOfWeek(getData.trip_date) }}</div>
            <div class="col-md-3">Alpine</div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <label for="">Last Date of Booking : </label>
                <span class="col-4">{{
                    formatDateTime(getData.booking_close_date)
                }}</span>
            </div>
            <div class="col-12">
                <label for="">Price : </label> <span> ${{ getData.price }}</span>
            </div>
            <div class="col-12">
                <label for="">Price Stuff kid : </label> <span>$0.00</span>
            </div>
            <div class="col-12">
                <label for="">Price Junior Instructor : </label> <span>$0.00</span>
            </div>
            <button class="btn btn-secondary">Close Booking</button>
        </div>
        <hr />
        <button class="btn btn-warning">Trip Members</button>
        <div class="row mt-3">
            <div class="col-4">
                <label for="">Total Trips limit </label>
            </div>
            <div class="col-4">42</div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for=""> Trips Sold </label>
            </div>
            <div class="col-4">10</div>
        </div>

        <div class="row">
            <div class="col-4">
                <label for=""> Trips Avaliable </label>
            </div>
            <div class="col-4">1</div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for=""> AM-ON-BUS </label>
            </div>
            <div class="col-4">5</div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for=""> PM-RETURN </label>
            </div>
            <div class="col-4">6</div>
        </div>
        <hr />
        <div class="row mt-4">
            <label for="">Bus Stop Sales</label>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-primary table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Location Bus stop Name</th>
                                <th scope="col">Seat / Avaliable / Sold</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="location in getData.route.locations" :key="location.id">
                                <td scope="row">{{ location.name }}</td>
                                <td>
                                    {{ location.total_seats ?? 0 }} /
                                    {{ location.avaliable_seats ?? 0 }} /
                                    {{ location.sold_seats ?? 0 }}
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" @click="openEditSeatsModal(location)">
                                        Edit Seats
                                    </button>
                                    <button class="btn btn-primary btn-sm">
                                        Location Members
                                    </button>
                                    <button class="btn btn-secondary btn-sm">
                                        Excel to export
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
export default {
    name: "Trips",
    components: {
        flatPickr,

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
            },
            getData: [],
            errors: {},
            successMessage: "",
            isEditing: false,
            temp_id: null,
            createTripForm: false,
            allTrips: true,
            selectedLocation: null,
            showTripInfo: false,
            showModal: false,
            seats: "",
        };
    },
    mounted() {
        this.getTrips(), this.getRoutes();
    },
    computed: {
        activeTrips() {
            return this.list.filter(data => this.isTripActive(data.trip_date));
        },
        archivedTrips() {
            return this.list.filter(data => !this.isTripActive(data.trip_date));
        },
    },
    methods: {
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
            this.getData = data;
            (this.showTripInfo = true),
                (this.showTrips = false),
                (this.allTrips = false);
        },
        openEditSeatsModal(location) {
            this.selectedLocation = location;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedLocation = null;
            this.seats = "";
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
                    text: "You will not be able to recover this membership!",
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
        updateSeats() {
            axios
                .post("/api/updateSeats", {
                    seats: this.seats,
                    location: this.selectedLocation,
                })
                .then((response) => {
                    this.getTrips();
                    window.location.href = "/trips";
                    console.log("Seats updated successfully:", response.data);
                    // Close the modal after updating seats
                    this.closeModal();
                })
                .catch((error) => {
                    console.error("Error updating seats:", error);
                });
        },
        formatDateString(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                const options = { year: "numeric", month: "long", day: "numeric" };
                return date.toLocaleDateString("en-US", options);
            }
            return "";
        },
        getDayOfWeek(dateString) {
            if (dateString) {
                const daysOfWeek = [
                    "Sunday",
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday",
                ];
                const date = new Date(dateString);
                const dayIndex = date.getDay();
                return daysOfWeek[dayIndex];
            }
            return "";
        },
        formatDateTime(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                const options = {
                    year: "2-digit",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                };
                return date
                    .toLocaleDateString("en-US", options)
                    .replace(/(\d+)\/(\d+)\/(\d+),/, "$2-$1-$3");
            }
            return "";
        },
    },
};
</script>
