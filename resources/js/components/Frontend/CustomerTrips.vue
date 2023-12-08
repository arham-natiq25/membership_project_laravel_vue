<template>
    <div v-if="showModal">
        <div class="modal fade show" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Trip and Location</h4>
                        <button type="button" class="close" @click="closeModal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="trip">Select Trip</label>
                                    <select class="form-control select2" v-model="selectedTrip" style="width: 100%">
                                        <option value="" selected disabled>Select</option>
                                        <option :value="trip" v-for="trip in trips" :key="trip.id">
                                            {{ trip.trip_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="selectedTrip != null">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer">Select Location</label>
                                    <select class="form-control select2" v-model="selectedLocation" style="width: 100%">
                                        <option value="" selected disabled>Select</option>
                                        <option :value="loc" v-for="loc in selectedTrip.route.locations" :key="loc.id">
                                            {{ loc.name }}({{ availableSeats[loc.id] }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routes">Select Members</label>
                                    <div v-for="member in loginCustomer.members" :key="member.id">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :value="member.id"
                                                v-model="selectedMembers" :id="'memberCheckbox' + member.id" />
                                            <label class="form-check-label" :for="'memberCheckbox' + member.id">
                                                {{ member.first_name + " " + member.last_name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" @click="closeModal">
                            Close
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="onNext">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
    <div class="d-flex justify-content-between">
        <h3>List of {{ user.name }} Trips</h3>
        <button class="btn btn-warning" @click="openmodel">Buy Trip</button>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-secondary table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th>Trip</th>
                    <th style="width: 15%">Trip Date</th>
                    <th style="width: 10%">Route</th>
                    <th style="width: 30%">Locations</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(trip, index) in customerTrips" :key="trip.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ trip.trip_name }}</td>
                    <td>{{ formatDateString(trip.trip_date) }}</td>
                    <td>{{ trip.route.title }}</td>
                    <td>
                        <ul>
                            <li v-for="location in trip.route.locations" :key="location.id">
                                {{ location.name }}
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import axios, { all } from "axios";

export default {
    props: ["user"],
    name: "CustomerTrips",
    components: {},
    data() {
        return {
            showModal: false,
            customer_id: null,
            tripMembers: [],
            alltripMembers: [],
            loginCustomer: {},
            tripIDS: [],
            customerTrips: [],
            trips: [],
            selectedTrip: null,
            selectedMembers: [],
            selectedLocation: null,
        };
    },
    mounted() {
        // Access the user's ID and store it in customer_id
        if (this.user) {
            this.customer_id = this.user.id;
        }
        this.getLoginCusotmer();
        // this.getTripMembers();
    },
    computed: {
        soldSeats() {
            const soldSeatsCounts = {};

            // Initialize soldSeatsCounts for each location
            this.selectedTrip.route.locations.forEach((location) => {
                soldSeatsCounts[location.id] = 0;
            });

            // Count sold seats for each location
            this.alltripMembers.forEach((data) => {
                const locationId = data.location_id;
                if (soldSeatsCounts.hasOwnProperty(locationId)) {
                    soldSeatsCounts[locationId]++;
                }
            });

            return soldSeatsCounts;
        },
        availableSeats() {
            const availableSeatsCounts = {};
            const locations = this.selectedTrip.route.locations;

            for (let i = 0; i < locations.length; i++) {
                const locationId = locations[i].id;
                const totalSeats = locations[i].total_seats || 0;
                const soldSeats = this.soldSeats[locationId] || 0;

                availableSeatsCounts[locationId] = totalSeats - soldSeats;
            }

            return availableSeatsCounts;
        },
    },
    methods: {
        openmodel() {
            this.showModal = true;
        },
        closeModal() {
            (this.selectedLocation = null),
                (this.selectedMembers = []),
                (this.selectedTrip = null);
            this.showModal = false;
        },
        getTripMembers() {
            axios.get("/api/gettrip").then((res) => {
                this.tripMembers = res.data;
                this.alltripMembers = res.data;

                const memberIds = this.loginCustomer.members.map((member) => member.id);
                this.tripMembers = this.tripMembers.filter((tripMember) =>
                    memberIds.includes(tripMember.member_id)
                );

                const uniqueTripIdsSet = new Set(
                    this.tripMembers.map((tripMember) => tripMember.trip_id)
                );
                this.tripIDS = Array.from(uniqueTripIdsSet);
                this.getTrips();
            });
        },
        getLoginCusotmer() {
            console.log("Customer ID:", this.customer_id);
            axios.get("/api/customer").then((res) => {
                this.loginCustomer = res.data.filter(
                    (item) => item.user_id === this.customer_id
                )[0];

                this.getTripMembers();
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

        getTrips() {
            axios.get("/api/trips").then((res) => {
                const allTrips = res.data;
                const currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0);

                this.trips = res.data.filter((item) => {
                    if (item.status === 1 && item.trip_date) {
                        const tripDate = new Date(item.trip_date);
                        tripDate.setHours(0, 0, 0, 0);
                        return tripDate >= currentDate;
                    }
                    return false;
                });

                // Filter trips based on the uniqueTripIds array
                this.customerTrips = allTrips.filter((trip) =>
                    this.tripIDS.includes(trip.id)
                );
            });
        },
        onNext() {
            if (
                this.selectedTrip.length == 0 ||
                this.selectedLocation.length == 0 ||
                this.selectedMembers.length === 0
            ) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please select both Trip and Location",
                });
            } else {
                const locationId = this.selectedLocation.id;
                const availableSeats = this.availableSeats[locationId];

                if (availableSeats === 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No available seats for the selected location.",
                    });
                } else {
                    const payload = {
                        loc_id: locationId,
                        trip_id: this.selectedTrip.id,
                        customer_id: this.loginCustomer.id,
                        member: { ...this.selectedMembers },
                    };

                    axios
                        .post("/api/savetrip", payload)
                        .then((res) => {
                            this.message = res.data.message;
                            this.error = {}; // Clear any previous error messages
                            this.selectedMembers = [];
                            this.selectedLocation = null
                            this.selectedTrip =null
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: res.data.message,
                            });
                        })
                        .catch((error) => {
                            if (error.response && error.response.data) {
                                const { data } = error.response;
                                if (data.error) {
                                    this.error = data.error;
                                }
                            }
                        });
                }
            }
        },
    },
};
</script>
