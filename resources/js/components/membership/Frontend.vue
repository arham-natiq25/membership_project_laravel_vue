<template>
  <div class="container mb-4">
    <!-- when member is saved or customer payment successfull it shows for 10 sec  -->
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <!-- when get errors from backend during save of member shows modified arrays of errors here  -->
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
                <!-- Show membershiips that are active although only one membership is actived at a time  -->
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
          <div class="col-md-6">
            <div class="form-group">
              <label for="membershipBuying"
                >How many Memberships do you want to buy ?
              </label>
              <!-- Here how many we input it gets in membershipCount  -->
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
            <!-- on click of this button function of show User Detaisl is called  -->
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
    <div v-show="showCheckout">
        <!-- here errors shown that occurs during payment  -->
        <div v-if="errorMessage">
      <p class="alert alert-danger">{{ errorMessage }}</p>
          </div>
        <div>
            <button class="btn btn-default" @click="goBack">Back</button>
            <h3>Proceed to Checkout</h3>
            <div class="row">
                <div class="col-md-6">
                    <p>Total Payment: $ {{ totalPayment }}</p>
                </div>
            </div>
      <div class="row">
        <div class="col-md-10">
          <div class="form-group">
            <label for="name"> Name</label>
            <input type="text" class="form-control" id="name" name="name"  v-model="items.name" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email"  v-model="items.email" name="email" />
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="password" >Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              v-model="items.password"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label for="phone">Parent phone # </label>
            <input type="text" class="form-control" id="phone" name="phone" v-model="items.phone" />
          </div>
        </div>
      </div>

      <!--  new -->
      <label for="card-element">
        Credit or debit card
      </label>
      <div class="col-md-8" id="card-element">
        <!-- Stripe.js injects the Card Element -->
      </div>
      <button @click="submitPayment" class="btn btn-success mt-3">Payment</button>

    </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
    // name use to export in app.js..
  name: "Frontend",
  // Data that are used in table to manuplate ....
  data() {
    return {
     list: [],
      stripe: null,
      elements: null,
      errorMessage: null,
      successMessage: "",
      successMessage: "",
      items: {
        membership_id: "",
        memberships: [],
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
        membership_price:"",
        name:"",
        email:"",
        password:"",
        phone:"",
    },
      savedMemberIDs: [],
      membershipCount: 0,
      showUserDetailsForm: false,
      showMemberships: true,
      showCheckout: false,
      errors: {},
    };
  },
  // use to fetch data using http request .
  mounted() {
    // here calling method to fetech all data
    this.fetchAll();
    // this is use to call stripe every time when our page is ready or refresh
    this.loadStripe();
  },

  computed: {

    // here is the function that comutes total payment ..
    totalPayment() {
      const membershipPrice = this.items.membership_price;
      const numberOfMembers = this.items.memberships.length;
      return membershipPrice * numberOfMembers;
    },
},
  // functions are created here thats implement on templet divs
  methods: {
    // This function is getting request through axios and fetch data from db and we mount them .
    fetchAll() {
      axios.get("/api/membership").then((res) => {
        this.list = res.data.filter((item) => item.status === 1);
        this.items.membership_id = this.list[0].id;
        this.items.membership_price=this.list[0].first_price
      });
    },
    // this function calculate total payment ..
    calculateTotalPayment() {
    const membershipPrice = this.items.membership_price;
    const numberOfMembers = this.items.memberships.length;
    return membershipPrice * numberOfMembers;
  },
  // This functions shows how many members we want to add . it shows forms like 1 , 2 , 3
    showUserDetails() {
        // we put v model in input of membership count and here loop to display N forms n=1,2,3,4...
      for (let i = 0; i < parseInt(this.membershipCount); i++) {
        this.items.memberships.push({
          fname: "",
          lname: "",
          dob: "",
          gender: "",
          activity: "",
        });
      }
      // make user details true and membership page hide ..
      this.showUserDetailsForm = true;
      this.showMemberships = false;
    },
    // this function is called when click on back button so it goes to show membership and clear all fields
    goBack() {
      this.showMemberships = true;
      this.showUserDetailsForm = false;
      this.showCheckout=false,
      this.errors={}
      this.items.memberships = [];
      this.resetFormFields();

    },
    // this function is called and we push data in our membershiip array everytime doesnot matter how many records we have
    addMember() {
      this.items.memberships.push({
        fname: this.items.fname,
        lname: this.items.lname,
        dob: this.items.dob,
        gender: this.items.gender,
        activity: this.items.activity,
      });
      // After pushing data in array ....
      // Clear the form fields for the next member
      this.items.fname = "";
      this.items.lname = "";
      this.items.dob = "";
      this.items.gender = "";
      this.items.activity = "";
    },

    // whenever this fun is called all errors clear ...
    clearErrors() {
      this.errors = {}; // Clear errors
    },
    // when our page loads this function called as we called it from mount...
    loadStripe() {
        // when window refresuh
      if (window.Stripe) {
        // assign stripe a publish key of our stripe
        this.stripe = window.Stripe('pk_test_51NUU03Emu0Ala7lxKFLz0kgK8mfOVQr99wlJMIDW39xzneQ0B6Zb2x9irWjjNuldkUYyDFQG11FE50M6px3wvrVx00A0milkpo');
        // assign elements .  a stripe elements of card , cvc , etc
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
    // whenever we called this fun all fields reset except membership id and membership price
    resetFormFields() {
    this.items = {
      memberships: [],
      membership_id : this.list[0].id,
      fname: "",
      lname: "",
      dob: "",
      gender: "",
      activity: "",
      membership_price:this.list[0].first_price,
      name: "",
      email: "",
      password: "",
      phone: "",
    };
    this.savedMemberIDs = [];
    this.membershipCount = 0;
    this.errors = {};
  },
  // this function is called when we click on PAYMENT BUTTON.
    async submitPayment() {
      // here create payment method / error and get card elements . if any error it shows error
      const { paymentMethod, error } = await this.stripe.createPaymentMethod({
        type: 'card',
        card: this.elements.getElement('card'),
      });

      if (error) {
        this.errorMessage = error.message;
      } else {
        //here call a next function with payment id we
        // Send paymentMethod.id to the backend to process the payment
        this.processPayment(paymentMethod.id);
      }
    },
    // this function is called from submit Function and it recieves payment id which we send to laravel controller
    async processPayment(paymentMethodId) {
      try {
        // here total payment get by calling the function
        const totalPayment = this.calculateTotalPayment(); // Calculate the total payment
        // request using api and send body ...
        const response = await fetch('/api/process-payment', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            paymentMethodId,
            totalPayment,
            email: this.items.email,
            name: this.items.name,
            password: this.items.password,
            phone: this.items.phone,
            memberIDs: this.savedMemberIDs, // Include member IDs
        }),
        });


        // get response from backend ..
        const responseData = await response.json();
        // Handle the response from the server after payment processing
        //if success show success messgae for 10 sec
      if (responseData.success) {
        this.successMessage = responseData.message;

        // Toggle section visibility
        this.showMemberships = true;
        this.showUserDetailsForm = false;
        this.showCheckout = false;

        // Clear the success message after 10 seconds
        setTimeout(() => {
          this.successMessage = "";
        }, 10000);

        // at the end reset all fields and show memberhip page ....
        this.resetFormFields();
      }
      } catch (error) {
        this.errorMessage = 'An error occurred while processing the payment';
      }
    },

    // members save in database :
    memberSave() {
        // when click on next button this fun called .
        // firstly it clear errors
        this.clearErrors();
      try {
        // request through api to backend and send mebership array consist of memberss ..
        // id of that membership that is active
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
            // get a response of member ids that are saved in database and get them in savedMemberIdDs array
            this.savedMemberIDs = response.data.savedMemberIDs;
            // Display the success message
            this.successMessage = response.data.message;

            // Clear the success message after 10 seconds
            setTimeout(() => {
              this.successMessage = "";
            }, 10000);

            this.showCheckout = true;
            this.showMemberships = false; // Hide the member section
            this.showUserDetailsForm = false; // Hide the user details section
          })
          .catch((error) => {
            console.error("Error:", error);

            if (error.response && error.response.data.error) {
          // Capture errors from the backend and assign them to the errors object
          const backendErrors = error.response.data.error;

          // use Nested loops to customize errors that are coming from backend
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
