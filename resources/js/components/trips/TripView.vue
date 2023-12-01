<template>
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
    <div v-if="showTripInfo">
        <button class="btn btn-default my-3" @click="goToTripRoute">Back</button>
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
        <button class="btn btn-warning" @click="showMembers(getData.id)">Trip Members</button>
        <div class="row mt-3">
            <div class="col-4">
                <label for="">Total Trips limit </label>
            </div>
            <div class="col-4">{{ totalSeats }}</div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for=""> Trips Sold </label>
            </div>
            <div class="col-4">{{ totalSoldSeats }}</div>
        </div>

        <div class="row">
            <div class="col-4">
                <label for=""> Trips Avaliable </label>
            </div>
            <div class="col-4">{{ availableTotalSeats }}</div>
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
                    <table class="table table-primary table-bordered" v-if="getData.route && getData.route.locations">
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
                                    {{ availableSeats[location.id] }}   /
                                    {{ soldSeats[location.id] }}
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

    <div v-if="sawMembers">


        <div>
            <button class="btn btn-secondary my-2" @click="backToInfo">Back</button>
            <h4>Trip Members</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-info table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(member, index) in selectedMembers" :key="index">
                        <td scope="row">{{ index + 1 }}</td>
                        <td>{{ member.first_name }}</td>
                        <td>{{ member.last_name }}</td>
                        <td>{{ member.dob }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {

    props: ['id'],
    data() {
        return {
            getData: {},
            getallData: [],
            selectedLocation: null,
            showModal: false,
            seats: "",
            tripId: null,
            tripMembers: [],
            allMemberrs: [],
            selectedMembers: [],
            sawMembers: false,
            showTripInfo: true,
            soldseats:0,
            totalss:0,
            totalgetSeats:0

        }
    },
    mounted() {
        this.tripId = this.id; // Store the prop value in the component's data
        this.getSpecificData();
        this.getTripMembers(),
            this.getMembers()

    },
    computed:{
        totalSeats() {
            this.totalgetSeats=0
            let total = 0;

            // Check if the route and locations are available in the trip data
            if (this.getData.route && this.getData.route.locations) {
                const locations = this.getData.route.locations;

                // Use a for loop to iterate over each location
                for (let i = 0; i < locations.length; i++) {
                    // Add the total seats for the current location to the total
                    total += locations[i].total_seats || 0;
                }
            }
            this.totalgetSeats = total

            // Return the total seats
            return total;
        },
         soldSeats() {
            const soldSeatsCounts = {};

            // Initialize soldSeatsCounts for each location
            this.getData.route.locations.forEach(location => {
                soldSeatsCounts[location.id] = 0;
            });

            // Count sold seats for each location
            this.tripMembers.forEach(data => {
                const locationId = data.location_id;
                if (soldSeatsCounts.hasOwnProperty(locationId)) {
                    soldSeatsCounts[locationId]++;
                }
            });

            return soldSeatsCounts;
        },
        availableSeats() {
            const availableSeatsCounts = {};
            const locations = this.getData.route.locations;

            for (let i = 0; i < locations.length; i++) {
                const locationId = locations[i].id;
                const totalSeats = locations[i].total_seats || 0;
                const soldSeats = this.soldSeats[locationId] || 0;

                availableSeatsCounts[locationId] = totalSeats - soldSeats;
            }

            return availableSeatsCounts;
        },

        totalSoldSeats(){
            this.totalss=0
            let total=0;
            this.tripMembers.forEach(tm => {
              if (  tm.trip_id==this.tripId) {
                total=total+1
            }
            });
            this.totalss=total
            return total;
        },
        availableTotalSeats(){
            let avaSeats = 0;
            avaSeats = this.totalgetSeats - this.totalss;
            return avaSeats;
        }


    },
    methods: {
        backToInfo() {
            this.showTripInfo = true,
                this.sawMembers = false
        },
        goToTripRoute() {
            window.location.href = '/trips';
        },
        getSpecificData() {
            axios.get("/api/trips").then((res) => {
                this.getallData = res.data.filter(item => item.id === parseInt(this.id));
                this.getData = this.getallData[0]
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
        openEditSeatsModal(location) {
            this.selectedLocation = location;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedLocation = null;
            this.seats = "";
        },
        updateSeats() {
            axios
                .post("/api/updateSeats", {
                    seats: this.seats,
                    location: this.selectedLocation,
                })
                .then((response) => {
                    window.location.href = `/trip/${this.tripId}/view`;
                    console.log("Seats updated successfully:", response.data);
                    // Close the modal after updating seats
                    this.closeModal();
                })
                .catch((error) => {
                    console.error("Error updating seats:", error);
                });
        },
        getTripMembers() {
            axios.get('/api/gettrip').then((res) => {
                this.tripMembers = res.data;
            });
        },
        getMembers() {
            axios.get('/api/member').then((res) => {
                this.allMemberrs = res.data;
            });
        },
        showMembers(id) {
            this.sawMembers = true;
            this.showTripInfo = false;
            // Reset selectedMembers to an empty array
            this.selectedMembers = [];

            // Iterate over tripMembers and match with allMemberrs
            this.tripMembers.forEach(tripMember => {
                this.allMemberrs.forEach(member => {
                    if (id == tripMember.trip_id && tripMember.member_id == member.id) {
                        // Push each matched member to selectedMembers
                        this.selectedMembers.push(member);
                    }
                });
            });
        },

    }
}
</script>
