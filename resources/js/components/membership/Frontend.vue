<template>
  <div class="container mb-4">
    <!-- when member is saved or customer payment successfull it shows for 10 sec  -->
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <!-- when get errors from backend during save of member shows modified arrays of errors here  -->
    <div v-for="(error, index) in Object.keys(errors)" :key="index">
      <div v-if="errors[error].length > 0">
        <div
          class="alert alert-danger"
          role="alert"
          v-for="(message, i) in errors[error]"
          :key="i"
        >
          {{
            message
              .replace("memberships", "Membership")
              .replace("dob", "date of birth")
              .replace("fname", "First name")
              .replace("lname", "Last name")
          }}
        </div>
      </div>
    </div>
    <div>
        <div v-if="showMemberships">
            <div class="row">
              <div v-if="errorMessage" class="alert alert-danger" role="alert">
                {{ errorMessage }}
              </div>

              <!-- Membership card on the left -->
              <div class="col-md-6">
                <div class="card-container">
                  <h4>Membership Details</h4>
                  <div class="row mt-3">
                    <!-- Show memberships that are active (only one at a time) -->
                    <div
                      class="card"
                      style="margin-bottom: 20px"
                      v-if="activeMembership != null"
                    >
                      <div class="card-body">
                        <span class="fst-italic h5">Title : </span>
                        <span class="text-primary h5">{{ activeMembership.title }}</span>
                        <div class="card-text mt-2">
                          <span class="text-primary">First Date :</span>
                          {{ activeMembership.first_date }} &
                          <span class="text-primary">First Price :</span> ${{ activeMembership.first_price }}
                          <br /><br />
                          <span class="text-primary">Second Date :</span>
                          {{ activeMembership.second_date }} &
                          <span class="text-primary">Second Price :</span> ${{ activeMembership.second_price }}
                          <br /><br />
                          <span class="text-primary">Third Date :</span>
                          {{ activeMembership.third_date }} &
                          <span class="text-primary">Third Price :</span> ${{ activeMembership.third_price }}
                          <br /><br />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Input fields on the right -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="membershipBuying">
                    How many Memberships do you want to buy?
                  </label>
                  <!-- Here how many we input gets into membershipCount -->
                  <input
                    type="number"
                    class="form-control"
                    id="membershipBuying"
                    name="membershipBuying"
                    v-model="membershipCount"
                  />
                </div>
                <div class="mt-4">
                  <!-- on click of this button function of showUserDetails is called -->
                  <button class="btn btn-warning mt-1" @click="showUserDetails">
                    Next
                  </button>
                </div>
              </div>
            </div>
          </div>

    </div>


    <!-- Get details from user  -->
    <div v-if="showUserDetailsForm">
      <div>
        <button class="btn btn-default" @click="goBackMainPage">Back</button>
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

    <div v-if="showCheckout">
      <!-- here errors shown that occurs during payment  -->
      <div v-if="errorMessage">
        <p class="alert alert-danger">{{ errorMessage }}</p>
      </div>
      <div>
        <button class="btn btn-default" @click="goBackMainPage">Back</button>
        <h3>Proceed to Checkout</h3>
        <div class="row">
          <div class="col-md-6">
            <p>Total Payment: $ {{  totalPayment }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label for="name"> Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                v-model="items.name"
              />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="items.email"
                name="email"
              />
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
                v-model="items.password"
              />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label for="phone">Parent phone # </label>
              <input
                type="text"
                class="form-control"
                id="phone"
                name="phone"
                v-model="items.phone"
              />
            </div>
          </div>
        </div>

        <div v-if="active===0">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="cardNumber" class="form-label">Card Number</label>
                <input
                  type="text"
                  class="form-control"
                  id="cardNumber"
                  maxlength="16"
                  minlength="16"
                  v-model="cardNumber"
                  placeholder="Enter card number"
                  required
                />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="cvv" class="form-label">CVV</label>
                <input
                  type="text"
                  class="form-control"
                  id="cvv"
                  maxlength="4s"
                  minlength="3"
                  v-model="cvv"
                  placeholder="Enter CVV"
                  required
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="expiryMonth" class="form-label">Expiry Month</label>
                <select
                  class="form-control"
                  id="expiryMonth"
                  v-model="expiryMonth"
                  required
                >
                  <option value="" disabled>Select month</option>
                  <option v-for="month in months" :key="month" :value="month">
                    {{ month }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-5">
              <label for="expiryYear" class="form-label">Expiry Year</label>
              <select
                class="form-control"
                id="expiryYear"
                v-model="expiryYear"
                required
              >
                <option value="" disabled>Select year</option>
                <option v-for="year in dynamicYears" :key="year" :value="year">
                  {{ year }}
                </option>
              </select>
            </div>
          </div>
          <button @click="submitPaymentViaAuthorize" class="btn btn-success">
            Payment
          </button>
        </div>

        <!--  new -->

      </div>

    </div>
    <div v-show="!showUserDetailsForm && !showMemberships && active===1">
        <label for="card-element"> Credit or debit card </label>
        <div class="col-md-8" id="card-element">
          <!-- Stripe.js injects the Card Element -->
        </div>
        <button @click="submitPayment" class="btn btn-success mt-3">
          Payment
        </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Frontend",
  data() {
    return {
      showCheckout: false,
      active: null,
      activeMembership: null,
      stripe: null,
      elements: null,
      errorMessage: null,
      successMessage: "",
      items: {
        membership_id: null,
        memberships: [],
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
        membership_price: null,
        name: "",
        email: "",
        password: "",
        phone: "",
      },
      savedMemberIDs: [],
      membershipCount: 0,
      showUserDetailsForm: false,
      showMemberships: true,

      errors: {},
      // authorize
      months: [
        "01",
        "02",
        "03",
        "04",
        "05",
        "06",
        "07",
        "08",
        "09",
        "10",
        "11",
        "12",
      ],
      years: ["2023", "2024", "2025", "2026", "2027", "2028", "2029", "2030"],
      cardNumber: "",
      cvv: "",
      expiryMonth: "",
      expiryYear: "",
    };
  },
  computed: {
    dynamicYears() {
      const currentYear = new Date().getFullYear();
      const futureYears = Array.from(
        { length: 15 },
        (_, index) => currentYear + index
      );
      return futureYears.map(String); // Convert years to strings
    },
    // here is the function that comutes total payment ..
    totalPayment() {
    const membershipPrice = this.items.membership_price;
    const numberOfMembers = this.items.memberships.length;
    const calculatedTotal = membershipPrice * numberOfMembers;

    // Check if calculatedTotal is a valid number, otherwise return 0
    return Number.isNaN(calculatedTotal) ? 0 : calculatedTotal;
},
  },
  mounted() {
    this.getActiveMembership();
    this.getActiveGateway();
    this.loadStripe();
  },
  methods: {
    resetFormFields() {
      this.items = {
        memberships: [],
        membership_price: this.activeMembership.first_price,
        membership_id: this.activeMembership.id,
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
        name: "",
        email: "",
        password: "",
        phone: "",
      };
      this.membershipCount = 0;
      this.savedMemberIDs = [];
      this.errors = {};
      this.cardNumber= "",
      this.cvv= "",
      this.expiryMonth= "",
      this.expiryYear= ""
    },
    getActiveMembership() {
      axios.get("/active/membership").then((res) => {
        this.activeMembership = res.data;
        this.items.membership_id = res.data.id;
        this.items.membership_price = res.data.first_price;
      });
    },
    showUserDetails() {
    //    if (this.membershipCount<1) {
    //     this.errorMessage="Enter How many memberships do you want to buy"
    //    }
    //    else{
        this.errorMessage=""
        this.showUserDetailsForm    = true;
      this.showMemberships = false;
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
    // }
      // make user details true and membership page hide ..

    },
    // this function calculate total payment ..
    calculateTotalPayment() {
      const membershipPrice = this.items.membership_price;
      const numberOfMembers = this.items.memberships.length;
      return membershipPrice * numberOfMembers;
    },
    goBackMainPage() {
      this.showMemberships = true;
      this.showUserDetailsForm = false;
      (this.showCheckout = false), (this.errors = {});
      this.items.memberships = [];
      this.resetFormFields();
    },
    clearErrors() {
            this.errors = {}; // Clear errors
        },

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


    // when our page loads this function called as we called it from mount...
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
    // this function is called from submit Function and it recieves payment id which we send to laravel controller
    async processPayment(paymentMethodId) {
      try {
        // here total payment get by calling the function
        const totalPayment = this.calculateTotalPayment(); // Calculate the total payment
        // request using api and send body ...
        const response = await fetch("/api/process-payment", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
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
        this.errorMessage = "An error occurred while processing the payment";
      }
    },

    submitPaymentViaAuthorize() {
      this.successMessage = null;
      this.errorMessage = null;
      const totalPayment = this.calculateTotalPayment();

      const cardDetails = {
        cardNumber: this.cardNumber,
        cvv: this.cvv,
        expiryMonth: this.expiryMonth,
        expiryYear: this.expiryYear,
        totalPayment,
        email: this.items.email,
        name: this.items.name,
        password: this.items.password,
        phone: this.items.phone,
        memberIDs: this.savedMemberIDs,
        gateway: 0,
      };
      axios
        .post("/api/process-payment", cardDetails)
        .then((response) => {
          this.successMessage = response.data.message;
          this.showMemberships = true;
          this.showUserDetailsForm = false;
          this.showCheckout = false;

          // Clear the success message after 10 seconds
          setTimeout(() => {
            this.successMessage = "";
          }, 10000);

          // at the end reset all fields and show memberhip page ....
          this.resetFormFields();
        })
        .catch((error) => {
          this.errorMessage =
            "Error making payment: " +
            (error.response.data.error
              ? error.response.data.error[0].text
              : "An unexpected error occurred.");
        });
    },
    getActiveGateway() {
      axios.get("/get/gateway").then((res) => {
        this.active = res.data;
      });
    },

    memberSave() {
      try {
        // request through api to backend and send mebership array consist of memberss ..
        // id of that membership that is active
        axios
          .post("/api/member", {
            memberships: this.items.memberships,
            membership_id: this.activeMembership.id,
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
            this.showCheckout = !this.showCheckout;
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
                  const errorMessage = `${fieldName} for member ${
                    parseInt(index[0]) + 1
                  } is required`;
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
