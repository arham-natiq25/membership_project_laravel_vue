<template>
 <!-- input starts -->
 <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
    </div>
<div>
    <h4>Location Entry</h4>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            v-model="items.name"
          />
          <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="map">Map Link</label>
          <input
            type="text"
            class="form-control"
            id="map"
            name="map"
            v-model="items.map_link"

          />
          <span v-if="errors.map_link" class="text-danger">{{ errors.map_link[0] }}</span>

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
          v-model="items.depart_time"

          />
          <span v-if="errors.depart_time" class="text-danger">{{ errors.depart_time[0] }}</span>

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
            v-model="items.return_time"

          />
          <span v-if="errors.return_time" class="text-danger">{{ errors.return_time[0] }}</span>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" class="form-control"  id="address" name="address"  v-model="items.address"/>
          <span v-if="errors.address" class="text-danger">{{ errors.address[0] }}</span>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="desc">Description</label>
          <textarea
            type="text"
            class="form-control"
            id="desc"
            name="desc"
            v-model="items.description"
          >
        </textarea>
        <span v-if="errors.description" class="text-danger">{{ errors.description[0] }}</span>
        </div>
      </div>
    </div>
    <button class="btn btn-primary" @click="saveLocation">{{ isEditing ? 'Update' : 'Save' }}</button>
</div>
<!-- input ends  -->
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
            <tr v-for="(item,index) in list" :key="item.id">
              <td>{{ index+1 }}</td>
              <td>{{ item.name }}</td>
              <td>{{ formatTime(item.depart_time) }}</td>
              <td>{{ formatTime(item.return_time) }}</td>
              <td>
                  <i class="fas fa-trash-alt btn btn-danger text-white btn-sm mx-2" @click="deleteLocation(item.id)"></i>
                  <i class="far fa-edit btn btn-primary text-white btn-sm "  @click="editLocation(item)"></i>

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
import moment from 'moment';
export default {
   name:"Location",
   data(){
    return {
        list:[],
        items:{
            name:"",
            map_link:"",
            depart_time:"",
            return_time:"",
            address:"",
            description:""
        },
        isEditing:false,
        temp_id:null,
        errors:{},
        successMessage:'',

    }
   },
   mounted(){
    this.getLocations();
   },
   methods:{
    getLocations(){
        axios.get('/api/locations').then(res=>{
                this.list=res.data
            });
    },
    saveLocation(){
            let method = axios.post;
            let url = '/api/locations';

            if(this.isEditing){
                method=axios.put;
                url = `/api/locations/${this.temp_id}`
            }
            try {
                    method(url,this.items).then(async(res)=>{
                    this.getLocations()
                    this.items={
                        name:"",
                        map_link:"",
                        depart_time:"",
                        return_time:"",
                        address:"",
                        description:""
                    },
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
    editLocation(data){

        this.items={
            name:data.name,
            map_link:data.map_link,
            depart_time:data.depart_time,
            return_time:data.return_time,
            address:data.address,
            description:data.description,
        }
        this.temp_id=data.id;

        this.isEditing=true;
    },
    formatTime(time) {
         return moment(time, 'HH:mm').format('hh:mm A');
    },
    async deleteLocation(id){
            try {
            // on click a popup shows of sweet alert
            const confirmed = await Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this membership!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
            });

            // if click on yes ... request using axios and deleted and show message ..
        if (confirmed.isConfirmed) {
          await axios.delete(`/api/locations/${id}`);
         this.getLocations();
          Swal.fire('Deleted!', 'Your trip has been deleted.', 'success');
        }
        // if getting any error in deleting
      } catch (error) {
        console.error(error);
        Swal.fire('Error', 'An error occurred while deleting the trip.', 'error');
      }
        }


   }
}
</script>
