<template>
  <div v-if="showmemberships">
    <div class="d-flex justify-content-between mb-3">
      <h3>List of {{ user.name }} Memberships</h3>
      <button class="btn btn-warning" @click="openBuyMembershipForm">
        Buy New Membership
      </button>
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
            <tr v-for="(item, index) in list" :key="item.id">
              <td scope="row">{{ index + 1 }}</td>
              <td>{{ item.first_name + " " + item.last_name }}</td>
              <td>{{ formatDateString(item.dob) }}</td>
              <td>{{ item.gender === 1 ? "Male" : "Female" }}</td>
              <td>{{ item.activity === 1 ? "Skier" : "Snow Border" }}</td>
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
        <div class="col-md-4">
          <div class="form-group">
            <label for="fname">First Name</label>
            <input
              type="text"
              class="form-control"
              id="fname"
              name="fname"
              v-model="items.fname"
            />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="lname">Last Name</label>
            <input
              type="text"
              class="form-control"
              v-model="items.lname"
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
              v-model="items.dob"
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
              v-model="items.gender"
              :id="'male'"
              value="male"
              :name="'gender'"
            />
            Male
            <input
              type="radio"
              class=""
              :id="'female'"
              value="female"
              v-model="items.gender"
              :name="'gender'"
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
              :id="'skier'"
              value="skier"
              :name="'activity'"
              v-model="items.activity"
            />
            Skier
            <input
              type="radio"
              class=""
              :id="'snowboarder'"
              value="snowboarder"
              :name="'activity'"
              v-model="items.activity"
            />
            SnowBoarder
          </div>
        </div>
      </div>
      <button class="btn btn-primary" @click="showCard">
        Pay Using Saved Card
      </button>
      <button class="btn btn-primary mx-2" @click="showPayment">
        Proceed to Payment
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div v-show="PaymentShow">
        <div v-if="errorMessage" class="alert alert-danger">
          {{ errorMessage }}
        </div>
        <div class="row">
          <div class="col-6">
            <span for="mebership_price">Membership Name : </span
            ><b>{{ this.membership.title }}</b> <br />
            <span for="mebership_price">Membership price : </span
            ><b>${{ this.paymentAmount }}</b>
          </div>
        </div>
        <div v-show="active===1">
          <div class="form-group">
            <label for="card-number">Card Number</label>
            <div id="card-number" class="form-control"></div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-6">
              <label for="card-expiry">Expiration Date</label>
              <div id="card-expiry" class="form-control"></div>
            </div>
            <div class="col-md-6">
              <label for="card-cvc">CVC</label>
              <div id="card-cvc" class="form-control"></div>
            </div>
          </div>
        </div>

        <!-- Authorize Gateway  -->
        <div v-show="active===0">
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
          <div class="row justify-content-center">
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            <div class="col-md-4">
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
        </div>
        <div class="row mt-3">
          <!-- Add top margin to separate buttons from the form -->
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="hidePayment"
            >
              Close
            </button>
          </div>
          <div class="col-md-6 text-right">
            <button
              v-show="active===1"
              type="button"
              class="btn btn-outline-dark"
              @click="submit"
              :disabled="loading"
            >
              <span
                v-if="loading"
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
              ></span>
              <span v-else>Payment</span>
            </button>
            <button
              v-show="active===0"
              type="button"
              class="btn btn-outline-dark"
              @click="submitViaAuthorize"
              :disabled="loading"
            >
              <span
                v-if="loading"
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
              ></span>
              <span v-else>Payment Auh</span>
            </button>
          </div>
          <!-- Authorize  -->

        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div v-show="payCard">
        <div class="row">
          <div class="col-6">
            <span for="mebership_price">Membership Name : </span
            ><b>{{ this.membership.title }}</b> <br />
            <span for="mebership_price">Membership price : </span
            ><b>${{ this.paymentAmount }}</b>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-12">
            <label>Select Card</label>
            <div v-for="card in cards" :key="card.id">
              <input
                type="radio"
                :id="'card_' + card.id"
                name="selectedCard"
                class="m-2"
                :value="card"
                v-model="selectedCard"
              />
              <label
                :for="'card_' + card.id"
                class="m-2"
                style="font-size: 16px"
              >
                **** **** **** {{ card.last_four_digits }}
              </label>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="hideCard"
            >
              Close
            </button>
          </div>
          <div class="col-md-6 text-right">
            <button
              type="button"
              class="btn btn-outline-dark"
              @click="saveMembershipUsingCard"
              :disabled="loading"
            >
              <span
                v-if="loading"
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
              ></span>
              <span v-else>Payment</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: ["user"],
  name: "CustomerMembership",
  data() {
    return {
      showmemberships: true,
      list: [],
      showBuymembership: false,
      items: {
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
      },
      errorMessage: "",
      successMessage: "",
      elements: null,
      stripe: null,
      cardNumberElement: null,
      cardExpiryElement: null,
      cardCvcElement: null,
      PaymentShow: false,
      membership: {},
      paymentAmount: 0,
      loading: false,
      message: "",
      error: {},
      payCard: false,
      cards: [],
      selectedCard: "",
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
      active: null,
    };
  },
  mounted() {
      this.getActiveGateway();
    this.getCustomerMemberships();
    this.loadStripe();
    this.getCustomerCards();
    this.getActiveMembership();
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
  },
  methods: {
    getCustomerMemberships() {
      axios.get("/customer-memberships").then((res) => {
        this.list = res.data;
      });
    },
    getActiveMembership() {
      axios.get("/active/membership").then((res) => {
        this.membership = res.data;
        this.calculateMembershipPrice();
      });
    },

    calculateMembershipPrice() {
      const currentDate = new Date();
      currentDate.setHours(0, 0, 0, 0);

      const firstDate = new Date(this.membership.first_date);
      firstDate.setHours(0, 0, 0, 0);

      const secondDate = new Date(this.membership.second_date);
      secondDate.setHours(0, 0, 0, 0);

      const thirdDate = new Date(this.membership.third_date);
      thirdDate.setHours(0, 0, 0, 0);

      if (currentDate < firstDate) {
        this.paymentAmount = this.membership.first_price;
      } else if (currentDate < secondDate) {
        this.paymentAmount = this.membership.second_price;
      } else if (currentDate < thirdDate) {
        this.paymentAmount = this.membership.third_price;
      } else {
        this.paymentAmount = this.membership.third_price;
      }
    },

    openBuyMembershipForm() {
      (this.showmemberships = false), (this.showBuymembership = true);
    },
    openAllmemberships() {
      (this.showmemberships = true), (this.showBuymembership = false);
      this.items = {
        fname: "",
        lname: "",
        dob: "",
        gender: "",
        activity: "",
      };
    },
    showCard() {
      if (
        !this.items.fname ||
        !this.items.lname ||
        !this.items.dob ||
        !this.items.gender ||
        !this.items.activity
      ) {
        Swal.fire({
          icon: "error",
          title: "Validation Error",
          text: "Please fill in all the required fields.",
        });
        return;
      }
      this.payCard = true;
      this.showBuymembership = false;
    },
    hideCard() {
      (this.payCard = false), (this.showBuymembership = true);
    },
    showPayment() {
      if (
        !this.items.fname ||
        !this.items.lname ||
        !this.items.dob ||
        !this.items.gender ||
        !this.items.activity
      ) {
        Swal.fire({
          icon: "error",
          title: "Validation Error",
          text: "Please fill in all the required fields.",
        });
        return;
      }
      this.PaymentShow = true;
      this.showBuymembership = false;
    },
    hidePayment() {
      (this.PaymentShow = false), (this.showBuymembership = true);
    },
    formatDateString(dateString) {
      if (dateString) {
        const date = new Date(dateString);
        const options = { year: "numeric", month: "long", day: "numeric" };
        return date.toLocaleDateString("en-US", options);
      }
      return "";
    },
    loadStripe() {
      // if current windonw has Stripe set primary key
      if (window.Stripe) {
        this.stripe = window.Stripe(
          "pk_test_51NUU03Emu0Ala7lxKFLz0kgK8mfOVQr99wlJMIDW39xzneQ0B6Zb2x9irWjjNuldkUYyDFQG11FE50M6px3wvrVx00A0milkpo"
        );
        this.elements = this.stripe.elements();
        // Create an instance of the card number Element
        this.cardNumberElement = this.elements.create("cardNumber", {
          placeholder: "Card Number",
        });
        this.cardNumberElement.mount("#card-number");

        // Create an instance of the card expiry Element
        this.cardExpiryElement = this.elements.create("cardExpiry", {
          placeholder: "MM / YY",
        });
        this.cardExpiryElement.mount("#card-expiry");

        // Create an instance of the card cvc Element
        this.cardCvcElement = this.elements.create("cardCvc", {
          placeholder: "CVC",
        });
        this.cardCvcElement.mount("#card-cvc");
      } else {
        // Handle the case when Stripe is not available
        console.error("Stripe is not available");
      }
    },
    getActiveGateway() {
      axios.get("/get/gateway").then((res) => {
        this.active = res.data;
      });
    },
    async submit() {
      // Set loading to true before making the API call
      this.errorMessage = "";
      const cardElement = this.elements.getElement("cardNumber");
      const { paymentMethod, error } = await this.stripe.createPaymentMethod({
        type: "card",
        card: cardElement,
      });
      console.log("Error from Stripe:", error); // Log Stripe error
      if (error) {
        this.errorMessage = error.message;
      } else {
        this.loading = true;
        this.saveMemberships(paymentMethod.id);
      }
    },
    saveMemberships(paymentMethodId) {
      const payload = {
        paymentMethodId,
        price: this.paymentAmount,
        items: this.items,
        membership_id: this.membership.id,
        method: 0, // with new card
        payment_for: 0, // membership
      };

      axios
        .post("/customer/membership-payment", payload)
        .then((res) => {
          this.message = res.message;
          this.error = {}; // Clear any previous error messages
          (this.showmemberships = true),
            (this.showBuymembership = false),
            (this.PaymentShow = false),
            (this.loading = false);
          this.loadStripe(),
            this.getCustomerMemberships(),
            this.getCustomerCards();
          this.items = {
            fname: "",
            lname: "",
            dob: "",
            gender: "",
            activity: "",
          };
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
              this.errorMessage = data.error;
              this.error = data.error;
              this.loading = false;
            }
          }
        })
        .finally(() => {
          // Code to run regardless of success or error
          this.loading = false;
        });
    },
    submitViaAuthorize(){
        this.loading=true;
        const payload = {
        cardNumber: this.cardNumber,
        cvv: this.cvv,
        expiryMonth: this.expiryMonth,
        expiryYear: this.expiryYear,
        price: this.paymentAmount,
        items: this.items,
        membership_id: this.membership.id,
        method: 0, // with new card
        payment_for: 0, // membership
      };

      axios
        .post("/customer/membership-payment", payload)
        .then((res) => {
          this.message = res.message;
          this.error = {}; // Clear any previous error messages
          (this.showmemberships = true),
            (this.showBuymembership = false),
            (this.PaymentShow = false),
            (this.loading = false);
          this.loadStripe(),
            this.getCustomerMemberships(),
            this.getCustomerCards();
          this.items = {
            fname: "",
            lname: "",
            dob: "",
            gender: "",
            activity: "",
          };
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
              this.errorMessage = data.error;
              this.error = data.error;
              this.loading = false;
            }
          }
        })
        .finally(() => {
          // Code to run regardless of success or error
          this.loading = false;
        });
    },
    getCustomerCards() {
      axios.get("/customer-profile").then((res) => {
        this.cards = res.data;
      });
    },
    saveMembershipUsingCard() {
      if (!this.selectedCard) {
        Swal.fire({
          icon: "error",
          title: "Validation Error",
          text: "Please select your card before proceeding.",
        });
        return;
      }
      const payload = {
        paymentMethodId: this.selectedCard.paymentMethodId,
        card: this.selectedCard,
        price: this.paymentAmount,
        items: this.items,
        membership_id: this.membership.id,
        method: 1, // with old card
        payment_for: 0, // 0 for membership
      };
      this.loading = true;
      axios
        .post("/customer/membership-payment", payload)
        .then((res) => {
          this.message = res.message;
          this.error = {}; // Clear any previous error messages
          (this.showmemberships = true),
            (this.showBuymembership = false),
            (this.selectedCard = "");
          (this.payCard = false), (this.loading = false);
          this.loadStripe(),
            this.getCustomerMemberships(),
            (this.items = {
              fname: "",
              lname: "",
              dob: "",
              gender: "",
              activity: "",
            });
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
              this.errorMessage = data.error;
              this.error = data.error;
              this.loading = false;
            }
          }
        });
    },
  },
};
</script>
