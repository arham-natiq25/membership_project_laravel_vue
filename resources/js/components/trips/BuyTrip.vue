<template>
    <button class="btn btn-warning" @click="openmodel">
        Buy Trip
    </button>
    <div v-if="showModal">
        <div class="modal fade show" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Trip and Customer</h4>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer">Select Customer</label>
                                    <select class="form-control select2" v-model="selectedCustomer" style="width: 100%">
                                        <option value="" selected disabled>Select</option>
                                        <option :value="cus" v-for="cus in customers" :key="cus.id">
                                            {{ cus.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" @click="closeModal">
                            Close
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="onNext">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
    <div v-if="showModalTwo">
        <div class="modal fade show" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Members and Location</h4>
                        <button type="button" class="close" @click="closeModalTwo">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routes">Select Members</label>
                                    <div v-for="member in selectedCustomer.members" :key="member.id">
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routes">Select Locations</label>
                                    <select class="form-control select2" v-model="selectedLocation">
                                        <option value="" disabled>Select</option>
                                        <option :value="location.id" v-for="location in selectedTrip.route.locations"
                                            :key="location.id">
                                            {{ location.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" @click="closeModalTwo">
                            Close
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="saveMembersTrips">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
</template>
<script>
import axios from "axios";


export default ({
    name: "BuyTrip",
    data() {
        return {

            showModal: false,
            showModalTwo: false,
            trips: [],
            selectedLocation: null,
            customers: [],
            selectedTrip: [],
            selectedCustomer: [],
            selectedMembers: [],
            message: "",
            error: {},
        };
    },
    mounted() {
        this.getTrips(),
            this.getCustomers()
    },
    methods: {

        saveMembersTrips() {
            if (this.selectedMembers.length === 0 || this.selectedLocation.length === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please select Atleast one Member and Location",
                });
            }
            else {

                const payload = {
                    loc_id: this.selectedLocation,
                    trip_id: this.selectedTrip.id,
                    member: { ...this.selectedMembers }
                };
                axios.post('/api/savetrip', payload)
                    .then((res) => {
                        this.message = res.data.message;
                        this.error = {};  // Clear any previous error messages
                        this.selectedCustomer = [];
                        this.selectedMembers = [];
                        this.selectedLocation = [];
                        this.selectedTrip = [];
                        this.showModalTwo = false;  // Close the second modal

                        // Optionally, you can show a success notification here
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.data.message,
                        });
                    })
                    .catch(error => {
                        if (error.response && error.response.data) {
                            const { data } = error.response;
                            if (data.error) {
                                this.error = data.error;
                            }
                        }

                        // Optionally, you can show an error notification here
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: this.error,
                        });
                    });

            }
        },

        getTrips() {
            axios.get('/api/trips').then((res) => {
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
            });
        },
        getCustomers() {
            axios.get('/api/customer').then((res) => {
                this.customers = res.data
            })
        },

        openmodel() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedCustomer = [],
                this.selectedTrip = []
        },
        closeModalTwo() {
            this.showModalTwo = false;
            this.showModal = true
            this.selectedMembers = [],
                this.selectedLocation = [],
                this.error = {},
                this.message = ""
        },
        onNext() {
            if (this.selectedTrip.length == 0 || this.selectedCustomer.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please select both Trip and Customer",
                });
            }
            this.showModal = false;
            this.showModalTwo = true
        },
    }
})
</script>
