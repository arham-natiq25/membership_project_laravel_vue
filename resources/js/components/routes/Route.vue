<template>
  <div v-if="successMessage" class="alert alert-success">
    {{ successMessage }}
  </div>
  <!-- input starts  -->
  <div v-if="createTripForm">
    <button class="btn btn-default my-2" @click="goback">Back</button>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="title">Enter Title</label>
          <input
            type="text"
            class="form-control"
            id="title"
            v-model="routes.title"
            name="title"
          />
        </div>
        <span v-if="errors['route.title']" class="text-danger">
       Title Field is Required
      </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="short_desc">Enter Short Description</label>
          <input
            type="text"
            class="form-control"
            id="short_desc"
            v-model="routes.short_description"
            name="short_desc"
          />
          <span v-if="errors['route.short_description']" class="text-danger">
            Short Description is Required
        </span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="short_desc">Enter Long Description</label>
          <textarea
            type="text"
            class="form-control"
            v-model="routes.long_description"
            id="short_desc"
            name="short_desc"
          ></textarea>
          <span v-if="errors['route.long_description']" class="text-danger">
            Long Description is Required
        </span>
        </div>
      </div>
    </div>
    <button class="btn btn-primary" @click="saveRoutes">
        {{ isEditing ? "Update" : "Save" }}
    </button>
    <div v-if="showLocationError" class="alert alert-danger">
    At least one location is required.
  </div>
    <div v-if="!isEditing">
    <h3>Add Location</h3>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" v-model="locations.name" />
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="map">Map Link</label>
          <input type="text" class="form-control" id="map" name="map" v-model="locations.map_link" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="depart_time">Depart Time</label>
          <input
            type="time"
            class="form-control"
            id="depart_time"
            name="depart_time"
            v-model="locations.depart_time"
          />
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="return_time">Return Time</label>
          <input
            type="time"
            class="form-control"
            id="return_time"
            name="return_time"
            v-model="locations.return_time"
          />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" class="form-control"  id="address" name="address"  v-model="locations.address"/>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="desc">Description</label>
          <textarea type="text" v-model="locations.description" class="form-control" id="desc" name="desc">
          </textarea>
        </div>
      </div>
    </div>
    <button class="btn btn-primary" @click="addLocation">Add Location</button>

    <div>
    <div class="row m-2">
    <div class="col-md-10">
     <h5>All Locations</h5>
    </div>
    <div class="col-md-12 mt-2">
      <div class="table-responsive">
        <table class="table table-secondary table-bordered">
          <thead>
            <tr>
              <th scope="col" >#</th>
              <th scope="col" style="width: 40%;">Name</th>
              <th scope="col">Depart Time</th>
              <th scope="col">Return Time</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(location, index) in addedLocations" :key="index" >
                <td>{{ index + 1 }}</td>
                <td>{{ location.name }}</td>
                <td>{{ formatTime(location.depart_time) }}</td>
                <td>{{ formatTime(location.return_time) }}</td>
              <td>
                  <i class="fas fa-trash-alt btn btn-danger text-white btn-sm mx-2"  @click="removeLocation(index)"></i>

             </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    </div>
</div>
</div>

  </div>
  <!-- input ends -->
  <!-- details section  -->
  <div v-else>
    <div class="row m-2">
      <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-between">
          <h5>All Routes</h5>
          <button class="btn btn-primary" @click="showTripform">Create</button>
        </div>
      </div>
      <div class="col-md-12 mt-2">
        <div class="table-responsive">
          <table class="table table-secondary table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Route Name</th>
                <th scope="col">Locations</th>
                <th scope="col" style="width: 30%">Description</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(route, index) in totalroutes" :key="route.id">
                <td scope="row">{{ index + 1 }}</td>
                <td>{{ route.title }}</td>
                <td>
                  <ul>
                    <li v-for="location in route.locations" :key="location.id">
                      {{ location.name }}
                    </li>
                  </ul>
                </td>
                <td>{{ route.short_description }}</td>
                <td>
                  <i
                    class="fas fa-trash-alt btn btn-danger text-white btn-sm mx-lg-2"
                    @click="deleteRoute(route.id)"
                  ></i>
                  <i
                    class="far fa-edit btn btn-primary text-white btn-sm"
                    @click="editRoute(route)"
                  ></i>
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
import axios from "axios";
export default {
  name: "Route",
  data() {
    return {
      totalroutes: [],
      routes: {
        title: "",
        short_description: "",
        long_description: "",
    },
    showLocationError: false,
    addedLocations: [],
      locations:{
        name:"",
        map_link:"",
        depart_time:"",
        return_time:"",
        address:"",
        description:""
      },
      successMessage: "",
      errors: {},
      temp_id: null,
      isEditing: false,
      createTripForm: false,
    };
  },
  mounted() {
    this.getRoutes();
  },
  methods: {
    goback(){
        this.createTripForm = false;
        this.errors={};
        this.isEditing = false;
        this.addedLocations=[];
        this.temp_id = null;
        this.locations = {
          name: "",
          map_link: "",
          depart_time: "",
          return_time: "",
          address: "",
          description: "",
        };
        this.routes= {
        title:"",
        short_description: "",
        long_description: "",
        };
    },
    showTripform() {
      this.createTripForm = true;

    },
    showTripRecords() {
      this.createTripForm = false,
      this.getTrips();


    },
    addLocation() {
      // Validate the location data before adding it to the list
      if (this.locations.name && this.locations.map_link) {
        // Add the location to the list
        this.addedLocations.push({ ...this.locations });
        // Clear the input fields for the next location
        this.locations = {
          name: "",
          map_link: "",
          depart_time: "",
          return_time: "",
          address: "",
          description: "",
        };
      } else {
        // Show an error message or handle invalid input as needed
        console.error("Invalid location data");
      }
    },
    removeLocation(index) {
      // Remove the location at the specified index from the list
      this.addedLocations.splice(index, 1);
    },
    saveRoutes() {

        if(!this.isEditing){
        if (this.addedLocations.length === 0) {
      this.showLocationError = true;
      setTimeout(() => {
        this.showLocationError = false;
      }, 5000);

      return;
    }
    }

      let method = axios.post;
      let url = "/api/routes";

      if (this.isEditing) {
        method = axios.put;
        url = `/api/routes/${this.temp_id}`;
      }
      const payload = {
      route: { ...this.routes },
      addedLocations: [...this.addedLocations],
    };

      try {
        method(url,payload)
          .then(async (res) => {
            this.getRoutes();
            (this.routes = {
              title: "",
              short_description: "",
              long_description: "",
            }),
            this.addedLocations=[];
              (this.createTripForm = false);
            this.successMessage = res.data.message;
            this.isEditing = false;
            this.temp_id = null;
            this.errors = {};
            setTimeout(() => {
              this.successMessage = "";
            }, 5000);
          })
          .catch((error) => {
            if (error.response && error.response.data) {
              const { data } = error.response;
              if (data.errors) {
                this.errors = data.errors;
              }
            }
          });
      } catch (error) {
        console.log(error);
      }
    },
    getRoutes() {
      axios.get("/api/routes").then((res) => {
        this.totalroutes = res.data;
      });
    },
    formatTime(time) {
         return moment(time, 'HH:mm').format('hh:mm A');
    },
    editRoute(data) {
      this.routes = {
        title: data.title,
        short_description: data.short_description,
        long_description: data.long_description,
        locations_id: data.locations.map((location) => location.id),
      };
      (this.temp_id = data.id),
        (this.isEditing = true),
        (this.createTripForm = true);
    },
    deleteRoute(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this customer and associated members!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/routes/${id}`)
            .then(() => {
              this.getRoutes();
              Swal.fire("Deleted!", "Route Deleted Successfully", "success");
            })
            .catch((error) => {
              console.error("Error deleting customer", error);
              Swal.fire("Error!", "An error occurred while deleting.", "error");
            });
        }
      });
    },
  },
};
</script>
