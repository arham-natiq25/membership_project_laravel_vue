<template>
    <div class="d-flex justify-content-between">
        <h3>List of {{ user.name }} Trips</h3>

        <button class="btn btn-warning">Buy Trip</button>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-primary table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Trip</th>
                    <th>Trip Date</th>
                    <th style="width: 10%;">Route</th>
                    <th style="width:17%;">Locations</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(trip,index) in customerTrips" :key="trip.id">
                    <td>{{ index+1 }}</td>
                    <td>{{ trip.trip_name }}</td>
                    <td>{{ trip.trip_date }}</td>
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
import axios from 'axios';

export default {
    props: ['user'],
    name: "CustomerTrips",
    components:{

    },
    data() {
        return {
            customer_id: null,
            tripMembers: [],
            loginCustomer: {},
            tripIDS: [],
            customerTrips: [],
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
    methods: {
        getTripMembers() {
            axios.get('/api/gettrip').then((res) => {
                this.tripMembers = res.data;
                const memberIds = this.loginCustomer.members.map(member => member.id);
                this.tripMembers = this.tripMembers.filter(tripMember => memberIds.includes(tripMember.member_id));

                const uniqueTripIdsSet = new Set(this.tripMembers.map(tripMember => tripMember.trip_id));
                this.tripIDS = Array.from(uniqueTripIdsSet);
                this.getTrips();

            });
        },
        getLoginCusotmer() {
            console.log('Customer ID:', this.customer_id);
            axios.get('/api/customer').then((res) => {
                this.loginCustomer = res.data.filter((item) => item.user_id === this.customer_id)[0];

                this.getTripMembers();
            });
        },

        getTrips() {
            axios.get('/api/trips').then((res) => {
                const allTrips = res.data;

                // Filter trips based on the uniqueTripIds array
                this.customerTrips = allTrips.filter(trip => this.tripIDS.includes(trip.id));
            });
        }
    }
}
</script>
