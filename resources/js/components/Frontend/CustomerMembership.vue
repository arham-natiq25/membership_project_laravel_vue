<template>
    <div v-if="successMessage" class="alert alert-success mt-3" role="alert">
        {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-danger mt-3" role="alert">
        {{ errorMessage }}
    </div>

    <div v-if="showmemberships">
        <div class="d-flex justify-content-between mb-3">
            <h3>List of {{ user.name }} Memberships</h3>
            <button class="btn btn-warning" @click="openBuyMembershipForm">Buy New Membership</button>
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
    </div>

    <div v-show="showBuymembership">
        <button class="btn btn-default" @click="openAllmemberships">Back</button>
        <div>
            <h4 class="my-2">Enter Your Details</h4>
            <div class="row">
                <div class="col-6">
                    <span for="mebership_price">Membership Name : </span><b>{{membership.title}}</b> <br>
                    <span for="mebership_price">Membership price : </span><b>${{membership_price}}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" v-model="items.fname" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" v-model="items.lname" id="lname" name="lname" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" v-model="items.dob" class="form-control" id="dob" name="dob" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dob">Gender : </label> <br />
                        <input type="radio" class="" v-model="items.gender" :id="'male'" value="male" :name="'gender'" />
                        Male
                        <input type="radio" class="" :id="'female'" value="female" v-model="items.gender"
                            :name="'gender'" />
                        Female
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <label for="dob">Select Your Activity : </label> <br />
                        <input type="radio" class="" :id="'skier'" value="skier" :name="'activity'"
                            v-model="items.activity" />
                        Skier
                        <input type="radio" class="" :id="'snowboarder'" value="snowboarder" :name="'activity'"
                            v-model="items.activity" />
                        SnowBoarder
                    </div>
                </div>
            </div>
            <label for="card-element"> Credit or debit card </label>
            <div class="col-md-8" id="card-element">
                <!-- Stripe.js injects the Card Element -->
            </div>
            <button class="btn btn-primary mt-4" @click="submitPayment">
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
            showmemberships: true,
            showBuymembership: false,
            customer_id: null,
            stripe: null,
            elements: null,
            loginCustomer: {},
            membership: [],
            membership_id: null,
            membership_price: null,
            items: {
                fname: '',
                lname: '',
                dob: '',
                gender: '',
                activity: '',

            },
            errorMessage: "",
            successMessage: ""
        }
    },
    mounted() {
        if (this.user) {
            this.customer_id = this.user.id;
        }
        this.getLoginCusotmer();
        this.getMembership();
        this.loadStripe();

    },
    methods: {
        openBuyMembershipForm() {
            this.showmemberships = false,
                this.showBuymembership = true
            this.loadStripe();
        },
        openAllmemberships() {
            this.showmemberships = true,
                this.showBuymembership = false
            this.items = {
                fname: '',
                lname: '',
                dob: '',
                gender: '',
                activity: '',
            };
            // Reset the Stripe card element
            const cardElementContainer = document.getElementById('card-element');
            cardElementContainer.innerHTML = ''; // Clear the container
            const card = this.elements.create('card');
            card.mount(cardElementContainer);
        },
        loadStripe() {
            // when window refresuh
            if (window.Stripe) {
                // assign stripe a publish key of our stripe
                this.stripe = window.Stripe(
                    "pk_test_51NUU03Emu0Ala7lxKFLz0kgK8mfOVQr99wlJMIDW39xzneQ0B6Zb2x9irWjjNuldkUYyDFQG11FE50M6px3wvrVx00A0milkpo"
                );
                // assign elements .  a stripe elements of card , cvc , etc
                this.elements = this.stripe.elements();

                // Create an instance of the card Element
                const card = this.elements.create("card");

                // Add an instance of the card Element into the `card-element` div
                card.mount("#card-element");
            } else {
                // Redirect or show an error if Stripe is not available
                this.errorMessage = "Stripe is not available";
            }
        },
        async submitPayment() {
            if (!this.validateItems()) {
                // If validation fails, stop submitting
                return;
            }
            // here create payment method / error and get card elements . if any error it shows error
            const { paymentMethod, error } = await this.stripe.createPaymentMethod({
                type: "card",
                card: this.elements.getElement("card"),
            });

            if (error) {
                this.errorMessage = error.message;
            } else {
                //here call a next function with payment id we
                // Send paymentMethod.id to the backend to process the payment
                this.processPayment(paymentMethod.id);
            }
        },
        validateItems() {
            // Basic validation for required fields
            if (!this.items.fname || !this.items.lname || !this.items.dob || !this.items.gender || !this.items.activity) {
                this.errorMessage = "You must have to fill all the fields";
                return false;
            }

            // Additional validation logic can be added here

            // Clear previous error message if validation passes
            this.errorMessage = "";
            return true;
        },


        async processPayment(paymentMethodId) {
            const response = await fetch("/api/customer/add-Member", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    paymentMethodId,
                    customerId: this.loginCustomer.id,
                    membershipId: this.membership_id,
                    payment: this.membership_price,
                    memberdata: this.items
                }),
            });
            const responseData = await response.json();

                if (responseData.success) {
                    this.showmemberships = true,
                    this.showBuymembership = false,
                    this.loginCustomer();
                    this.successMessage = responseData.message;


                setTimeout(() => {
                    this.successMessage = "";
                }, 10000);
                this.items = {
                    fname: "",
                    lname: "",
                    dob: "",
                    gender: "",
                    activity: "",
                };

            } else {
                this.errorMessage = responseData.message;

                // Hide error message after 10 seconds
                setTimeout(() => {
                    this.errorMessage = "";
                }, 10000);
            }
        },

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
        getMembership() {
            axios.get("/api/membership").then((res) => {
                this.membership = res.data.filter((item) => item.status === 1)[0];
                this.membership_id = this.membership.id;
                this.membership_price = this.membership.first_price
            });
        },

    },
}
</script>
