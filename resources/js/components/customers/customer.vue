<template>
<div class="row">
    <h5>Customers Details</h5>
        <table class="table table-bordered">
    <thead>
      <tr>
          <th>#</th>
        <th>Name</th>
        <th>Phone No </th>
        <th style="width: 30%;">Members</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <tr v-for="(cus,index) in list" :key="cus.id">
        <td>{{ index+1 }}</td>
        <td>{{ cus.name }}</td>
        <td>{{ cus.phone_no }}</td>
        <td> Member Name <br> 2nd Name <br> 3rd Name</td>
        <td>
            <i class="far fa-eye btn btn-primary text-white btn-sm"></i>
            <i class="fas fa-trash-alt btn btn-danger text-white btn-sm"></i>
        </td>
    </tr>
    </tbody>
    </table>
</div>
</template>
<script>
import axios from 'axios'
export default {
   name:"Customer",

   data(){
    return{
        list:[],

    }
   },
   computed:{

   },
   mounted(){
    this.getData()
   },
   methods:{
    getData(){
        axios.get('/api/customer').then(res=>{
            this.list=res.data
        }) .catch(error => {
          console.error('Error fetching data', error);
        });;
    },
   }
}
</script>
