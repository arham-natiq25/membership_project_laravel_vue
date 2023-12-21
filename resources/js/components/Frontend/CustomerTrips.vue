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
                  <select
                    class="form-control select2"
                    v-model="selectedTrip"
                    style="width: 100%"
                  >
                    <option value="" selected disabled>Select</option>
                    <option
                      :value="trip"
                      v-for="trip in acitveTrips"
                      :key="trip.id"
                    >
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
                  <select
                    class="form-control select2"
                    v-model="selectedLocation"
                    style="width: 100%"
                  >
                    <option value="" selected disabled>Select</option>
                    <option
                      :value="loc"
                      v-for="loc in selectedTrip.route.locations"
                      :key="loc.id"
                    >
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
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :value="member.id"
                        v-model="selectedMembers"
                        :id="'memberCheckbox' + member.id"
                      />
                      <label
                        class="form-check-label"
                        :for="'memberCheckbox' + member.id"
                      >
                        {{ member.first_name + " " + member.last_name }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closeModal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-outline-dark"
              @click="showCardMethod"
            >
              Pay using Your Card
            </button>
            <button
              type="button"
              class="btn btn-outline-dark"
              @click="showPaymentMethod"
            >
              Proceed to Payment
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show"></div>
  </div>
  <div v-show="paymentShow">
    <div class="modal fade show" style="display: block">
      <div class="modal-dialog">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h4 class="modal-title">Payment of Trip</h4>
            <button type="button" class="close" @click="closePaymentMethod">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div v-if="errorMessage" class="alert alert-danger">
                  {{ errorMessage }}
                </div>
                <div class="form-group">
                  <label for="card-number">Card Number</label>

                  <div id="card-number" class="form-control"></div>
                </div>
              </div>
            </div>
            <div class="row">
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
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closePaymentMethod"
            >
              Close
            </button>
            <button
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
              <span v-else>Save</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show"></div>
  </div>
  <div v-show="payCard">
    <div class="modal fade show" style="display: block">
      <div class="modal-dialog">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h4 class="modal-title">Payment Using Card</h4>
            <button type="button" class="close" @click="closeCardMethod">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-8">
                    <label>Select Card</label>
                    <div v-for="card in cards" :key="card.id">
                        <input type="radio" :id="'card_' + card.id" name="selectedCard" class="m-2" :value="card" v-model="selectedCard">
                        <label :for="'card_' + card.id" class="m-2">
                            **** **** **** {{ card.last_four_digits }}
                        </label>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closeCardMethod"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-outline-dark"
              @click="saveMemberTripThroughCard"
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
          <th style="width: 20%">Trip Date</th>
          <th style="width: 10%">Price</th>
          <th style="width: 30%">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(trip, index) in ListOfCustomerTrips" :key="trip.id">
          <td>{{ index + 1 }}</td>
          <td>{{ trip.trip_name }}</td>
          <td>{{ formatDateString(trip.trip_date) }}</td>
          <td>{{ formatPrice(trip.price) }}</td>
          <td>
            <button class="btn btn-primary btn-sm">Members</button>
            <button class="btn btn-success btn-sm mx-2">Details</button>
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
      elements: null,
      stripe: null,
      cardNumberElement: null,
      cardExpiryElement: null,
      cardCvcElement: null,
      errorMessage: "",
      successMessage: "",
      loading: false,
      paymentShow: false,
      showModal: false,
      customer_id: null,
      alltripMembers: [],
      loginCustomer: {},
      customerTrips: [],
      selectedTrip: null,
      selectedMembers: [],
      selectedLocation: [],
      ListOfCustomerTrips: [],
      acitveTrips: [],
      alltripMembers: [],
      message: "",
      error: {},
      cards:[],
      selectedCard:{},
      payCard:false
    };
  },
  mounted() {
    // Access the user's ID and store it in customer_id
    if (this.user) {
      this.customer_id = this.user.id;
    }
    this.getActiveTrips();
    this.loadStripe();
    this.getLoginCusotmer();
    this.getCustomerTrips();
    this.getCustomerCards()
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
    showCardMethod(){
        if (
        this.selectedMembers.length === 0 ||
        this.selectedLocation.length === 0
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select at least one Member and Location",
        });
      } else {
        const locationId = this.selectedLocation;
        const availableSeats = this.availableSeats[locationId];

        if (availableSeats === 0) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No available seats for the selected location.",
          });
        } else {
          this.payCard = true;
          this.showModal = false;
        }
      }
    },
    closeCardMethod(){
        (this.showModal = true), (this.payCard = false);

    },
    showPaymentMethod() {
      if (
        this.selectedMembers.length === 0 ||
        this.selectedLocation.length === 0
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select at least one Member and Location",
        });
      } else {
        const locationId = this.selectedLocation;
        const availableSeats = this.availableSeats[locationId];

        if (availableSeats === 0) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No available seats for the selected location.",
          });
        } else {
          this.paymentShow = true;
          this.showModal = false;
        }
      }
    },
    closePaymentMethod() {
      (this.showModal = true), (this.paymentShow = false);
    },
    getTripMembers() {
      axios.get("/api/gettrip").then((res) => {
        this.alltripMembers = res.data;
      });
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
        this.saveMembersTrips(paymentMethod.id);
      }
      console.log("Final error message:", this.errorMessage); // Log final error message
    },
    getCustomerCards(){
        axios.get('/customer-profile').then((res)=>{
            this.cards=res.data;
        });
    },
    saveMembersTrips(paymentMethodId) {
      if (
        this.selectedMembers.length === 0 ||
        this.selectedLocation.length === 0
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select At least one Member and Location",
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
            paymentMethodId,
            loc_id: locationId,
            trip_id: this.selectedTrip.id,
            customer_id: this.loginCustomer.id,
            member: { ...this.selectedMembers },
          };

          axios
            .post("/api/savetrip", payload)
            .then((res) => {
              this.message = res.message;
              this.error = {}; // Clear any previous error messages
              this.selectedMembers = [];
              this.selectedLocation = null;
              this.selectedTrip = null;
              this.getCustomerTrips();
              (this.paymentShow = false), (this.loading = false);
              this.
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
                }
              }
            });
        }
      }
    },
    saveMemberTripThroughCard(){
        if (
        this.selectedMembers.length === 0 ||
        this.selectedLocation.length === 0
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select At least one Member and Location",
        });
      } else {
            this.loading=true;
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
            card:this.selectedCard,
            loc_id: locationId,
            trip_id: this.selectedTrip.id,
            customer_id: this.loginCustomer.id,
            member: { ...this.selectedMembers },
          };

          axios
            .post("/trip/pay-with-card", payload)
            .then((res) => {
              this.message = res.message;
              this.error = {}; // Clear any previous error messages
              this.selectedMembers = [];
              this.selectedLocation = null;
              this.selectedTrip = null;
              this.getCustomerTrips();
              (this.payCard = false), (this.loading = false);
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
                }
              }
            });
        }
      }
     },
    formatPrice(price) {
      return `${price.toFixed(2)}`;
    },
    getCustomerTrips() {
      axios.get("/customer-trips").then((res) => {
        this.ListOfCustomerTrips = res.data;
      });
    },
    openmodel() {
      this.showModal = true;
    },
    closeModal() {
      (this.selectedLocation = null),
        (this.selectedMembers = []),
        (this.selectedTrip = null);
      this.showModal = false;
    },
    getActiveTrips() {
      axios.get("/api/trips/active").then((res) => {
        this.acitveTrips = res.data;
      });
    },

    getLoginCusotmer() {
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

    onNext() {
      if (
        this.selectedTrip == null ||
        this.selectedTrip.length == 0 ||
        this.selectedLocation.length == 0 ||
        this.selectedMembers.length === 0
      ) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please select both Trip and Location with atleast one member",
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
              this.selectedLocation = null;
              this.selectedTrip = null;
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
