<template>

    <div class="d-flex justify-content-between mb-3">
        <h3>List of {{ user.name }} Memberships</h3>
        <button class="btn btn-warning">Buy New Membership</button>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-secondary table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Member Name</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Activity</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in loginCustomer.members" :key="item.id">
                        <td scope="row">{{ index + 1 }}</td>
                        <td>{{ item.first_name + " " + item.last_name }} </td>
                        <td>{{ formatDateString(item.dob) }}</td>
                        <td>{{ item.gender === 1 ? 'Male' : 'Female' }}</td>
                        <td>{{ item.activity === 1 ? 'Skier' : 'Snow Border' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div>
        <button class="btn btn-default">Back</button>
        <div>
            <h4 class="my-2">Enter Your Details</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname"
                            v-model="items.fname" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" v-model="items.lname" id="lname"
                            name="lname" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" v-model="items.dob" class="form-control" id="dob"
                            name="dob" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dob">Gender : </label> <br />
                        <input type="radio" class="" v-model="items.gender" :id="'male' "
                            value="male" :name="'gender'" />
                        Male
                        <input type="radio" class="" :id="'female'" value="female"
                            v-model="items.gender" :name="'gender'" />
                        Female
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <label for="dob">Select Your Activity : </label> <br />
                        <input type="radio" class="" :id="'skier'" value="skier" :name="'activity'"
                            v-model="items.activity" />
                        Skier
                        <input type="radio" class="" :id="'snowboarder'" value="snowboarder"
                            :name="'activity'" v-model="items.activity" />
                        SnowBoarder
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-4" @click="cretaeMember">
                Buy Now
            </button>
            </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ["user"],
    name: "CustomerMembership",
    data() {
        return {
            customer_id: null,
            loginCustomer: {},
            membership:[],
            membership_id:null,
            membership_price:null,
            items:{
                fname:'',
                lname:'',
                dob:'',
                gender:'',
                activity:'',

        }
        }
    },
    mounted() {
        if (this.user) {
            this.customer_id = this.user.id;
        }
        this.getLoginCusotmer();
        this.getMembership();

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
        getLoginCusotmer() {
            axios.get("/api/customer").then((res) => {
                this.loginCustomer = res.data.filter(
                    (item) => item.user_id === this.customer_id
                )[0];
            });
        },
        getMembership(){
            axios.get("/api/membership").then((res) => {
            this.membership = res.data.filter((item) => item.status === 1)[0];
            this.membership_id = this.membership.id;
            this.membership_price=this.membership.first_price
        });
        },
    },
}
</script>
