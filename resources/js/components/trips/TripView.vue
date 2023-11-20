<template>
 <div v-if="message" class="alert alert-success" role="alert">
      {{ message }}
</div>
<!-- MODEL WINDOW THAT OPENS ON CLICK OF EDIT SEATS  -->
<div v-if="showModal">
  <div class="modal fade show" style="display: block;">
    <div class="modal-dialog">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h4 class="modal-title">Edit Seats for Location</h4>
          <button type="button" class="close" @click="closeModal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <label for="seats">Seats</label>
              <input type="text" class="form-control" id="seats" name="seats" v-model="seats">
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

    <!-- WINDOW ENDS -->

    <!-- ALL TRIPS THAT ARE ON FRONT WHENEVEN PAGE OPENS  -->
    <div v-if="showTrips">
        <h4>Trip view</h4>
        <div class="col-md-12 mt-2">
            <div class="table-responsive">
                <table class="table table-secondary table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" style="width: 40%">Trip Name</th>
                            <th scope="col">Route</th>

                            <th scope="col" style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in list" :key="data.id">
                            <td scope="row">{{ index + 1 }}</td>
                            <td>{{ data.trip_name }}</td>
                            <td>{{ data.route.title }}</td>
                            <td>
                                <i class="far fa-eye btn btn-primary text-white btn-sm"
                                    @click="viewInfo(data)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- TRIPS END  -->

    <div v-if="showTripInfo">
        <h4>View Trip</h4>
        <br/>
        <button class="btn btn-default my-3" @click="goBack" >Back</button>
        <div class="row">
            <div class="col-2 fw-bold">
                <label for=""> Trip Info </label>
            </div>
        </div>
        <div class="row">
            <div class="col-4">{{formatDateString(getData.trip_date) ?? ''}}</div>
            <div class="col-md-3">{{ getDayOfWeek(getData.trip_date) }}</div>
            <div class="col-md-3">Alpine</div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <label for="">Last Date of Booking : </label>
                <span class="col-4">{{ formatDateTime(getData.booking_close_date) }}</span>
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
        <button class="btn btn-warning">Trip Members</button>
        <div class="row mt-3">
            <div class="col-4">
                <label for="">Total Trips limit </label>
            </div>
            <div class="col-4">42</div>
        </div>
        <div class="row">
            <div class="col-4">
                <label for=""> Trips Sold </label>
            </div>
            <div class="col-4">10</div>
        </div>

        <div class="row">
            <div class="col-4">
                <label for=""> Trips Avaliable </label>
            </div>
            <div class="col-4">1</div>
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
                    <table class="table table-primary table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Location Bus stop Name</th>
                                <th scope="col">Seat / Avaliable / Sold</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="location in getData.route.locations" :key="location.id" >
                                <td scope="row">{{ location.name }}</td>
                                <td>{{ location.total_seats ?? 0 }} / {{ location.avaliable_seats ?? 0 }}  / {{ location.sold_seats ?? 0 }}</td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" @click="openEditSeatsModal(location)" >Edit Seats</button>
                                    <button class="btn btn-primary btn-sm" >
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
</template>
<script>
export default {
    name: "TripView",
    data() {
        return {
            list: [],
            showTripInfo:false,
            showTrips:true,
            getData:[],
            showModal: false,
            selectedLocation: null,
            seats:"",
            message:"",
            temp_seat:""
        };
    },
    mounted() {
        this.getTrips();
    },
    methods: {
        openEditSeatsModal(location) {
            this.selectedLocation = location;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedLocation=null
            this.seats=""
        },

        updateSeats() {
        axios.post('/api/updateSeats',{ seats: this.seats, location: this.selectedLocation })
      .then(response => {
        this.getTrips();
        this.message=response.data.message;

        // setTimeout(() => {
        //     this.message=""
        // }, 50000);
        console.log('Seats updated successfully:', response.data);
        // Close the modal after updating seats
        this.closeModal();
      })
      .catch(error => {
        console.error('Error updating seats:', error);
      });
  },

        formatDateString(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }
            return '';
        },
        getDayOfWeek(dateString) {
            if (dateString) {
                const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const date = new Date(dateString);
                const dayIndex = date.getDay();
                return daysOfWeek[dayIndex];
            }
            return '';
        },
        formatDateTime(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                const options = {
                    year: '2-digit',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                };
                return date.toLocaleDateString('en-US', options).replace(/(\d+)\/(\d+)\/(\d+),/, '$2-$1-$3');
            }
            return '';
        },
        getTrips() {
            axios.get("/api/trips").then((res) => {
                this.list = res.data.filter((item) => item.status === 1);
            });
        },
        viewInfo(data){
            this.getData=data;
            this.showTripInfo=true,
            this.showTrips=false
        },

        goBack(){
            this.showTripInfo=false,
            this.showTrips=true
            this.getData=[]
        }
    },
};
</script>
