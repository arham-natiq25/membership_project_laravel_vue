<template>
  <div class="container mb-4">
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <div v-for="(error, index) in Object.keys(errors)" :key="index">
    <div v-if="errors[error].length > 0">
      <div class="alert alert-danger" role="alert" v-for="(message, i) in errors[error]" :key="i">
        {{ message.replace('memberships', 'Membership').replace('dob','date of birth').replace(
            'fname','First name'
        ).replace('lname','Last name')
             }}
          </div>
    </div>
    </div>
    <!-- Membership and select membership  -->
    <div>
      <div v-show="showMemberships">
        <div class="row">
          <div class="col-md-12">
            <div class="card-container">
              <h4>Membership Details</h4>
              <div class="row mt-3">
                <div class="col-md-6" v-for="item in list" :key="item.id">
                  <div class="card" style="margin-bottom: 20px">
                    <div class="card-body">
                      <span class="fst-italic h5">Title : </span>
                      <span class="text-primary h5">{{ item.title }}</span>
                      <div class="card-text mt-2">
                        <span class="text-primary">First Date :</span>
                        {{ item.first_date }} &
                        <span class="text-primary">First Price :</span> ${{
                          item.first_price
                        }}
                        <br /><br />
                        <span class="text-primary">Second Date :</span>
                        {{ item.second_date }} &
                        <span class="text-primary">Second Price :</span> ${{
                          item.second_price
                        }}
                        <br />
                        <br />
                        <span class="text-primary">Third Date :</span>
                        {{ item.third_date }} &
                        <span class="text-primary">Third Price :</span> ${{
                          item.third_price
                        }}
                        <br />
                        <br />
                      </div>
                      <p class="card-text"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="membershipBuying"
                >How many Memberships do you want to buy ?
              </label>
              <input
                type="number"
                class="form-control"
                id="membershipBuying"
                name="membershipBuying"
                v-model="membershipCount"
              />
            </div>
          </div>
          <div class="col-md-2 mt-md-4">
            <button class="btn btn-warning mt-1" @click="showUserDetails">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Get details from user  -->
    <div v-if="showUserDetailsForm">
      <div>
        <button class="btn btn-default" @click="goBack">Back</button>
        <div v-for="(index, i) in parseInt(membershipCount)" :key="index">
          <h4 class="my-2">Enter Your Details</h4>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="fname">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="fname"
                  name="fname"
                  v-model="items.memberships[i].fname"
                />
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="lname">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="items.memberships[i].lname"
                  id="lname"
                  name="lname"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input
                  type="date"
                  v-model="items.memberships[i].dob"
                  class="form-control"
                  id="dob"
                  name="dob"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="dob">Gender : </label> <br />
                <input
                  type="radio"
                  class=""
                  v-model="items.memberships[i].gender"
                  :id="'male' + i"
                  value="male"
                  :name="'gender' + i"
                />
                Male
                <input
                  type="radio"
                  class=""
                  :id="'female' + i"
                  value="female"
                  v-model="items.memberships[i].gender"
                  :name="'gender' + i"
                />
                Female
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <label for="dob">Select Your Activity : </label> <br />
                <input
                  type="radio"
                  class=""
                  :id="'skier' + i"
                  value="skier"
                  :name="'activity' + i"
                  v-model="items.memberships[i].activity"
                />
                Skier
                <input
                  type="radio"
                  class=""
                  :id="'snowboarder' + i"
                  value="snowboarder"
                  :name="'activity' + i"
                  v-model="items.memberships[i].activity"
                />
                SnowBoarder
              </div>
            </div>
            <hr v-if="i < parseInt(membershipCount) - 1" />
            <div class="col-md-4 mt-4">
              <button
                v-if="i === parseInt(membershipCount) - 1"
                class="btn btn-primary"
                @click="memberSave"
              >
                NEXT PAGE
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Customers Area  -->
    <div>
      <h3>Proceed to Checkout</h3>
      <div class="row">
        <div class="col-md-10">
          <div class="form-group">
            <label for="name"> Name</label>
            <input type="text" class="form-control" id="name" name="name" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" />
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label for="phone">Parent phone # </label>
            <input type="text" class="form-control" id="phone" name="phone" />
          </div>
        </div>
      </div>

      <!--  new -->
      <label for="card-element">
        Credit or debit card
      </label>
      <div id="card-element">
        <!-- Stripe.js injects the Card Element -->
      </div>
      <button @click="submitPayment">Pay</button>
      <div v-if="errorMessage">
      <p>{{ errorMessage }}</p>
    </div> 
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  name: "Frontend",
  // Data that are used in table to manuplate ....
  data() {
    return {
      stripe: null,
      elements: null,
      errorMessage: null,  
      successMessage: "",
      list: [],
      successMessage: "",
      items: {
        membership_id: "",
        memberships: [],
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
      },
      membershipCount: 0,
      showUserDetailsForm: false,
      showMemberships: true,
      errors: {},
    };
  },
  // use to fetch data using http request .
  mounted() {
    // here calling method to fetech all data
    this.fetchAll();
    this.loadStripe();
  },
  // functions are created here thats implement on templet divs
  methods: {
    // This function is getting request through axios and fetch data from db and we mount them .
    fetchAll() {
      axios.get("/api/membership").then((res) => {
        this.list = res.data.filter((item) => item.status === 1);
        this.items.membership_id = this.list[0].id;
        console.log(this.items.membership_id);
      });
    },
    showUserDetails() {
      for (let i = 0; i < parseInt(this.membershipCount); i++) {
        this.items.memberships.push({
          fname: "",
          lname: "",
          dob: "",
          gender: "",
          activity: "",
          // Membership ID can be the same for all members
        });
      }
      this.showUserDetailsForm = true;
      this.showMemberships = false;
    },
    goBack() {
      this.showMemberships = true;
      this.showUserDetailsForm = false;
      this.errors={}
      this.items.memberships = [];
    },
    addMember() {
      this.items.memberships.push({
        fname: this.items.fname,
        lname: this.items.lname,
        dob: this.items.dob,
        gender: this.items.gender,
        activity: this.items.activity,
      });

      // Clear the form fields for the next member
      this.items.fname = "";
      this.items.lname = "";
      this.items.dob = "";
      this.items.gender = "";
      this.items.activity = "";
    },
    clearErrors() {
      this.errors = {}; // Clear errors
    },
    loadStripe() {
      if (window.Stripe) {
        this.stripe = window.Stripe('pk_test_51NUU03Emu0Ala7lxKFLz0kgK8mfOVQr99wlJMIDW39xzneQ0B6Zb2x9irWjjNuldkUYyDFQG11FE50M6px3wvrVx00A0milkpo');
        this.elements = this.stripe.elements();

        // Create an instance of the card Element
        const card = this.elements.create('card');

        // Add an instance of the card Element into the `card-element` div
        card.mount('#card-element');
      } else {
        // Redirect or show an error if Stripe is not available
        this.errorMessage = 'Stripe is not available';
      }
    },
//  async submitPayment() {
//       const { paymentMethod, error } = await this.stripe.createPaymentMethod({
//         type: 'card',
//         card: this.elements.getElement('card'),
//       });

//       if (error) {
//         this.errorMessage = error.message;
//       } else {
//         // Send paymentMethod.id to the backend to process the payment
//         this.processPayment(paymentMethod.id);
//       }
//     },
//     async processPayment(paymentMethodId) {
//       try {
//         const response = await fetch('/api/process-payment', {
//           method: 'POST',
//           headers: {
//             'Content-Type': 'application/json',
//           },
//           body: JSON.stringify({ paymentMethodId }),
//         });

//         const responseData = await response.json();
//         // Handle the response from the server after payment processing
//       } catch (error) {
//         this.errorMessage = 'An error occurred while processing the payment';
//       }
//     },

//     // members save in database :
    memberSave() {
        this.clearErrors();
      try {
        axios
          .post("/api/member", {
            memberships: this.items.memberships,
            membership_id: this.items.membership_id,
          })
          .then((response) => {
            // Reset the form fields after successfully creating the members
            this.items.memberships = this.items.memberships.map(() => ({
              fname: "",
              lname: "",
              dob: "",
              gender: "",
              activity: "",
            }));

            // Display the success message
            this.successMessage = response.data.message;

            // Clear the success message after 10 seconds
            setTimeout(() => {
              this.successMessage = "";
            }, 10000);
          })
          .catch((error) => {
            console.error("Error:", error);

            if (error.response && error.response.data.error) {
          // Capture errors from the backend and assign them to the errors object
          const backendErrors = error.response.data.error;

          for (const key in backendErrors) {
            const index = key.match(/\d+/); // Extract the index from the key
            const fieldName = key.replace(/\.\d+\./, " "); // Extract field name

            if (index && fieldName) {
              const errorMessage = `${fieldName} for member ${parseInt(index[0]) + 1} is required`;
              if (!this.errors[`member_${index[0]}`]) {
                this.errors[`member_${index[0]}`] = [errorMessage];
              } else {
                this.errors[`member_${index[0]}`].push(errorMessage);
              }
            }
          }
        }
          });
      } catch (e) {}
    },
  },
};
</script>
