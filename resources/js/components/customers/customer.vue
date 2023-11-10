<template>
<div v-if="!selectedCustomer">
<div class="row">
    <h5>Customers Details</h5>
        <table class="table table-bordered">
    <thead>
      <tr>
          <th>#</th>
        <th>Name</th>
        <th>Phone No </th>
        <th style="width: 30%;">Members</th>
        <th>Member Since</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <tr v-for="(customer,index) in list" :key="customer.id">
        <td>{{ index+1 }}</td>
        <td>{{ customer.name }}</td>
        <td>{{ customer.phone_no }}</td>
        <td>
            <ul>
              <li v-for="member in customer.members" :key="member.id">
                {{ member.first_name }} {{ member.last_name }}
              </li>
            </ul>
        </td>
        <td>{{ formatMemberSinceDate(customer.created_at) }}</td>
        <td>
            <i class="far fa-eye btn btn-primary text-white btn-sm" @click="showMemberDetails(customer)"></i>
            <i class="fas fa-trash-alt btn btn-danger text-white btn-sm" @click="deleteCustomer(customer.id)"></i>
        </td>
    </tr>
    </tbody>
    </table>
</div>
</div>

<div class="row" v-if="selectedCustomer">
    <div class="col-12">
        <button class="btn btn-default" @click="goBack">Back</button>
        <h3>Members Details for {{ selectedCustomer.name }}</h3>
        <div class="table-responsive table-bordered">
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Activity</th>
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="member in selectedCustomer.members" :key="member.id">
                        <td scope="row">{{ member.first_name }} {{ member.last_name }}</td>
                        <td>{{formatMemberSinceDate(member.dob) }}</td>
                        <td>{{member.gender===1 ?'Male':'Female'}}</td>
                        <td>{{ member.activity===1 ? 'Skier' : 'SnowBoarder' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
</template>
<script>
import axios from 'axios'
export default {
   name:"Customer",

   data(){
    return{
        list:[],
        selectedCustomer: null

    }
   },
   computed:{

   },
   mounted(){
    this.getData()
   },
   methods:{
    formatMemberSinceDate(date) {
      return moment(date).format('DD/MM/YYYY');
    },
    getData(){
        axios.get('/api/customer').then(res=>{
            this.list=res.data
        }) .catch(error => {
          console.error('Error fetching data', error);
        });;
    },
    showMemberDetails(customer) {
      this.selectedCustomer = customer;
    },
    goBack() {
      this.selectedCustomer = null;
    },
    deleteCustomer(customerId) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this customer and associated members!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/customer/${customerId}`)
            .then(() => {
              this.getData(); // Refresh the list after deletion
              this.selectedCustomer = null; // Hide member details
              Swal.fire('Deleted!', 'Customer and associated members have been deleted.', 'success');
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
