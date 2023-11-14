<template>
     <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
    </div>
    <!-- input starts  -->
    <div v-if="createTripForm">
        <button class="btn btn-default my-2" @click="showTripRecords">Back</button>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="title">Enter Title</label>
                    <input type="text" class="form-control" id="title" v-model="routes.title" name="title">
                    <span v-if="errors.title" class="text-danger">{{ errors.title[0] }}</span>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="short_desc">Enter Short Description</label>
                    <input type="text" class="form-control" id="short_desc" v-model="routes.short_description" name="short_desc">
                    <span v-if="errors.short_description" class="text-danger">{{ errors.short_description[0] }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="short_desc">Enter Long Description</label>
                    <textarea type="text" class="form-control" v-model="routes.long_description" id="short_desc" name="short_desc"></textarea>
                    <span v-if="errors.long_description" class="text-danger">{{ errors.long_description[0] }}</span>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-8">
            <label for="location">Location  </label> <br>
            <span v-if="errors.locations_id" class="text-danger">{{ errors.locations_id[0] }}</span>
            <br>
            <div class="form-group" v-for="location in locations" :key="location.id">
                <input type="checkbox" v-model="routes.locations_id" :value="location.id" name="location" id="noo">  {{ location.name }}<br>
            </div>
        </div>
        </div>

        <button class="btn btn-primary" @click="saveRoutes">{{ isEditing ? 'Update' : 'Save' }}</button>
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
              <th scope="col" >Route Name</th>
              <th scope="col">Locations</th>
              <th scope="col" style="width: 30%;">Description</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(route,index) in totalroutes" :key="route.id">
              <td scope="row">{{ index+1 }}</td>
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
                  <i class="fas fa-trash-alt btn btn-danger text-white btn-sm mx-lg-2" @click="deleteRoute(route.id)" ></i>
                  <i class="far fa-edit btn btn-primary text-white btn-sm " @click="editRoute(route)"></i>

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
import axios from 'axios'
export default {
    name:"Route",
    data(){
        return{
            totalroutes:[],
            locations:[],
            routes:{
                title:"",
                short_description:"",
                long_description:"",
                locations_id:[]

            },
            successMessage:"",
            errors:{},
            temp_id:null,
            isEditing:false,
            createTripForm:false,


        }
    },
    mounted(){
        this.getLocations()
        this.getRoutes()
    },
    methods:{
        showTripform(){
            this.createTripForm=true;
        },
        showTripRecords(){
            this.createTripForm=false,
            this.getTrips();
        },
        getLocations(){
            axios.get('/api/locations').then(res=>{
                this.locations = res.data
            });
        },
        saveRoutes(){
            let method = axios.post;
            let url = '/api/routes';

            if(this.isEditing){
                method=axios.put;
                url = `/api/routes/${this.temp_id}`
            }

            try {
                method(url,this.routes).then(async(res)=>{
                    this.getRoutes();
                    this.routes={
                    title:"",
                    short_description:"",
                    long_description:"",
                    locations_id:[]
                    },
                    this.createTripForm=false;
                    this.successMessage= res.data.message;
                    this.isEditing=false;
                    this.temp_id=null;
                    this.errors={}
                    setTimeout(() => {
                        this.successMessage='';
                    }, 5000);
                }).catch(error=>{
                    if(error.response && error.response.data){
                        const {data } = error.response
                        if(data.errors){
                            this.errors= data.errors;
                        }
                    }
                })
            } catch (error) {
                console.log(error)
            }
        },
        getRoutes(){
            axios.get('/api/routes').then(res=>{
                this.totalroutes=res.data
            });
        },
        editRoute(data){
            this.routes={
                title:data.title,
                short_description:data.short_description,
                long_description:data.long_description,
                locations_id: data.locations.map(location => location.id)
            }
            this.temp_id = data.id,
            this.isEditing=true,
            this.createTripForm=true

        },
        deleteRoute(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this customer and associated members!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
            axios.delete(`/api/routes/${id}`)
                .then(() => {
                    this.getRoutes()
                Swal.fire('Deleted!', 'Route Deleted Successfully', 'success');
            })
            .catch(error => {
              console.error('Error deleting customer', error);
              Swal.fire('Error!', 'An error occurred while deleting.', 'error');
            });
        }
      });
    }


    }
}
</script>
