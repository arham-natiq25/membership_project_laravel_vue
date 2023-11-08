<template>

    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <!-- Form started -->

    <div v-if="createFormVisible">
        <button class="btn btn-default mb-2" @click="showRecordList">Back</button>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="title">Enter Title</label>
                    <input type="text" class="form-control" id="title" name="title" v-model="items.title">
                    <span v-if="errors.title" class="text-danger">{{ errors.title[0] }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="first_date">First Date</label>
                    <input type="date" class="form-control" id="first_date" name="first_date" v-model="items.first_date">
                    <span v-if="errors.first_date" class="text-danger">{{ errors.first_date[0] }}</span>
                </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="first_price">First Price</label>
                <input type="text" class="form-control" id="first_price" name="first_price" v-model="items.first_price">
                <span v-if="errors.first_price" class="text-danger">{{ errors.first_price[0] }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="second_date">Second Date</label>
                <input type="date" class="form-control" id="second_date" v-model="items.second_date" name="second_date">
                <span v-if="errors.second_date" class="text-danger">{{ errors.second_date[0] }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="second_price">Second Price</label>
                <input type="text" class="form-control" id="second_price" v-model="items.second_price" name="second_price">
                <span v-if="errors.second_price" class="text-danger">{{ errors.second_price[0] }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="third_date">Third Date</label>
                <input type="date" class="form-control" v-model="items.third_date" id="third_date" name="third_date">
                <span v-if="errors.third_date" class="text-danger">{{ errors.third_date[0] }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="third_price">Third Price</label>
                <input type="text" class="form-control" v-model="items.third_price" id="third_price" name="third_price">
                <span v-if="errors.third_price" class="text-danger">{{ errors.third_price[0] }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" v-model="items.limit" name="limit" :checked="items.limit" id="limit" value="1">
                    <label for="limit" class="custom-control-label">Limit</label>
                </div>
            </div>
        </div>
        <div class="col-2">
             <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" v-model="items.status" name="status" id="status" :checked="items.status" value="1">
                    <label for="status" class="custom-control-label"> Status</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="title">Display order</label>
                <input type="text" class="form-control" id="display_order" v-model="items.display_order" name="display_order">
                <span v-if="errors.display_order" class="text-danger">{{ errors.display_order[0] }}</span>
            </div>
        </div>
    </div>
    <div class=" col-md-4">
       <button class="btn btn-primary" type="button" @click="save">{{ isEditing ? 'Update':  'Submit' }}</button>
    </div>
</div>
<div v-else>

    <!-- form completed -->
    <div class="d-flex justify-content-between mb-2">

        <h4>Records</h4>   <button class="btn btn-primary" @click="showCreateForm">Create</button>
    </div>

    <div v-if="list.length>0">
        <table class="table table-bordered">
    <thead>
      <tr>
          <th>#</th>
        <th style="width: 40%;">Title</th>
        <th>Status</th>
        <th>Display order</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <tr v-for="item in list" :key="item.id">
        <td>{{ item.id }}</td>
        <td>{{ item.title }}</td>
        <td> {{item.status==1 ? 'Active': 'Inactive'}} </td>
        <td>{{ item.display_order }}</td>
        <td>
            <button class="btn btn-success btn-sm" @click="editMembership(item)">Edit</button>

            <button class="btn btn-danger btn-sm" @click="deleteMembership(item.id)"> Delete</button>
        </td>
    </tr>
    </tbody>
    </table>
    </div>
</div>
</template>

<script>
import axios from 'axios';

export default{
    name:"Create",
    // Data that are used in table to manuplate ....
    data(){
        return {
           list:[],
           items:{
            title:"",
            first_date:"",
            first_price:"",
            second_date:"",
            second_price:"",
            third_date:"",
            third_price:"",
            limit:false,
            status:false,
            display_order:"",
           },
           // for updating data store id in temp id
           temp_id:null,
           // by default it is false . after click on update its gonna b true
           isEditing:false,
           // empty object of errors for getting errors that comes from backend and get from response
           errors:{},
           // if data updated and created successfully this message gonna display
           successMessage:"",
           // by default form is hidden
           createFormVisible: false,
        }
    },
    // use to fetch data using http request .
    mounted(){
        // here calling method to fetech all data
        this.fetchAll()
    },
    // functions are created here thats implement on templet divs
    methods:{
        // by default form is false so on click of button it is visible
             showCreateForm() {
            this.createFormVisible = !this.createFormVisible;
            },
        // here after clicking on back button it again hidden form
            showRecordList() {
            this.createFormVisible = false;
            this.fetchAll();
            },
        // This function is getting request through axios and fetch data from db and we mount them .
            fetchAll(){

            axios.get('/api/membership').then(res=>{
                this.list=res.data

            })

        },



        // This is the save and update method . instead of making two methods both are solved using single if
        save(){
            // for save method ..
           let method = axios.post;
           let url ='/api/membership';

           // if editing is enabled update method is used
           if(this.isEditing){
            method=axios.put;
            url = `/api/membership/${this.temp_id}`
           }
            try{
                if (this.items.status === true) {
            // Set the status of all existing memberships to 0
                    this.list.forEach((item) => {
                        item.status = 0;
                        axios.put(`/api/membership/${item.id}`, item)
                    .catch((error) => {
                        console.error("An error occurred while updating membership status:", error);
                    });
            });
        }
                //store data
                method(url,this.items).then(async (res)=>{
                    // after storing data again hide form
                    this.createFormVisible = false;
                    // again fetch all data with new entry
                    this.fetchAll()
                    // make all the fields empty
                    this.items = {
                        title:"",
                        first_date:"",
                        first_price:"",
                        second_date:"",
                        second_price:"",
                        third_date:"",
                        third_price:"",
                        limit:"",
                        status:"",
                        display_order:"",
                    };
                    // again make temp_id null if mode is editing
                    this.temp_id=null;
                    this.isEditing=false;
                    // store success message in Your message
                    this.successMessage = res.data.message;
                    // make errors object null .
                    this.errors = {};
                    setTimeout(() => {
                        this.successMessage = ''; // Clear success message after 10 seconds time
                    }, 10000); // Hide message after 5 seconds (adjust as needed)
                }).catch(error => {
                    if (error.response && error.response.data) {
                        // store response of errors in data var.
                        const { data } = error.response;
                        if (data.errors) {
                        // Set the errors object received from the server
                        this.errors = data.errors;
                        }
                    } else {
                        console.error("An error occurred:", error);
                    }
                    });

            }catch(e){
             console.log(e)
            }
        },
        // for editing data recieve object and make fields fill by assinging values of editing data send from
        // object
        editMembership(membership){
            this.items = {
                title:membership.title,
                first_date:membership.first_date,
                first_price:membership.first_price,
                second_date:membership.second_date,
                second_price:membership.second_price,
                third_price:membership.third_price,
                third_date:membership.third_date,
                limit:membership.limit===1,
                status:membership.status===1,
                display_order:membership.display_order
            }

            // store that id in temp id for editing

            this.temp_id = membership.id,
            // make editing mode true
            this.isEditing=true,
            // show form with filled fields
            this.createFormVisible = true;

        },
        // for deleting data using with sweet alerts.
        // get id of data what want to delete
        async deleteMembership(id) {
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
          await axios.delete(`/api/membership/${id}`);
          await this.fetchAll();
          Swal.fire('Deleted!', 'Your membership has been deleted.', 'success');
        }
        // if getting any error in deleting
      } catch (error) {
        console.error(error);
        Swal.fire('Error', 'An error occurred while deleting the membership.', 'error');
      }
        }
    }
}
</script>
