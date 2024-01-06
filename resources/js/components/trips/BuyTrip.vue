<template>
  <button class="btn btn-warning" @click="openmodel">Buy Trip</button>
  <div v-show="showModal">
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
                  <select
                    class="form-control select2"
                    v-model="selectedTrip"
                    style="width: 100%"
                  >
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
                  <select
                    class="form-control select2"
                    v-model="selectedCustomer"
                    style="width: 100%"
                  >
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
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closeModal"
            >
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
                  <div
                    v-for="member in selectedCustomer.members"
                    :key="member.id"
                  >
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

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="routes">Select Locations</label>
                  <select
                    class="form-control select2"
                    v-model="selectedLocation"
                  >
                    <option value="" disabled>Select</option>
                    <option
                      :value="location.id"
                      v-for="location in selectedTrip.route.locations"
                      :key="location.id"
                    >
                      {{ location.name }} ({{ availableSeats[location.id] }})
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closeModalTwo"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-outline-dark"
              @click="showPaymentMethod"
            >
              Save
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
            <div v-show="active===1">
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
            <div v-show="active===0">
                <div class="row">
                    <div v-if="errorMessage" class="alert alert-danger">
                      {{ errorMessage }}
                    </div>
                    <div class="col-md-8">
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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                      <label for="expiryYear" class="form-label">Expiry Year</label>
                      <select
                        class="form-control"
                        id="expiryYear"
                        v-model="expiryYear"
                        required
                      >
                        <option value="" disabled>Select year</option>
                        <option
                          v-for="year in dynamicYears"
                          :key="year"
                          :value="year"
                        >
                          {{ year }}
                        </option>
                      </select>
                    </div>
                  </div>
            </div>
            <!-- Auhtorize  -->

          </div>

          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-outline-danger"
              @click="closePaymentMethod"
            >
              Close
            </button>
            <button v-show="active===1"
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
            <button v-show="active===0"
              type="button"
              class="btn btn-outline-dark"
              @click="saveMemberTipsViaAuthorize"
              :disabled="loading"
            >
              <span
                v-if="loading"
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
              ></span>
              <span v-else>Save </span>
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

export default {
  name: "BuyTrip",
  data() {
    return {
      elements: null,
      stripe: null,
      cardNumberElement: null,
      cardExpiryElement: null,
      cardCvcElement: null,
      errorMessage: "",
      successMessage: "",
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
      tripMembers: [],
      paymentShow: false,
      loading: false,
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
      active: null,

    };
  },
  mounted() {
    this.loadStripe();
    this.getActiveGateway();
    this.getTrips(), this.getCustomers(), this.getTripMembers(); // Add this line
  },
  computed: {
    soldSeats() {
      const soldSeatsCounts = {};

      // Initialize soldSeatsCounts for each location
      this.selectedTrip.route.locations.forEach((location) => {
        soldSeatsCounts[location.id] = 0;
      });

      // Count sold seats for each location
      this.tripMembers.forEach((data) => {
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

    getActiveGateway() {
      axios.get("/get/gateway").then((res) => {
        this.active = res.data;
      });
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
          this.showModalTwo = false;
        }
      }
    },

    closePaymentMethod() {
      (this.paymentShow = false), (this.showModalTwo = true);
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
    getTripMembers() {
      axios.get("/api/gettrip").then((res) => {
        this.tripMembers = res.data;
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
        const locationId = this.selectedLocation;
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
            customer_id: this.selectedCustomer.id,
            member: { ...this.selectedMembers },
            method: 0,
            payment_for: 1, // 1 for tripe
          };

          axios
            .post("/savetrip", payload)
            .then((res) => {
              this.message = res.data.message;
              this.error = {}; // Clear any previous error messages
              this.selectedCustomer = [];
              this.selectedMembers = [];
              this.selectedLocation = [];
              this.selectedTrip = [];
              this.showModalTwo = false;
              (this.paymentShow = false), (this.loading = false);
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
        }
      }
    },

    saveMemberTipsViaAuthorize(){
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
        const locationId = this.selectedLocation;
        const availableSeats = this.availableSeats[locationId];

        if (availableSeats === 0) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No available seats for the selected location.",
          });
        } else {
            this.loading = true;
          const payload = {
            cardNumber: this.cardNumber,
            cvv: this.cvv,
            expiryMonth: this.expiryMonth,
            expiryYear: this.expiryYear,
            loc_id: locationId,
            trip_id: this.selectedTrip.id,
            customer_id: this.selectedCustomer.id,
            member: { ...this.selectedMembers },
            method: 0,
            payment_for: 1, // 1 for tripe
          };

          axios
            .post("/savetrip", payload)
            .then((res) => {
              this.message = res.data.message;
              this.error = {}; // Clear any previous error messages
              this.selectedCustomer = [];
              this.selectedMembers = [];
              this.selectedLocation = [];
              this.selectedTrip = [];
              this.showModalTwo = false;
              cardNumber: "",
              this.cvv= "",
              this.expiryMonth= "",
              this.expiryYear= "",
            (this.paymentShow = false), (this.loading = false);
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
        }
      }
    },

    getTrips() {
      axios.get("/api/trips").then((res) => {
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
      axios.get("/api/customer").then((res) => {
        this.customers = res.data;
      });
    },

    openmodel() {
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      (this.selectedCustomer = []), (this.selectedTrip = []);
    },
    closeModalTwo() {
      this.showModalTwo = false;
      this.showModal = true;
      (this.selectedMembers = []),
        (this.selectedLocation = []),
        (this.error = {}),
        (this.message = "");
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
      this.showModalTwo = true;
      this.loadStripe();
    },
  },
};
</script>
