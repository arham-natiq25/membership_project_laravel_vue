<template>

    <div class="row">
        <div class="col-md-12">
            <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
            <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
        </div>
    </div>
    <div>Active Gateway : <span class="text-primary"> {{active===1 ? "Stripe" : "Authorize" ?? "Stripe"}}</span></div>




   <label for="gateway-heading" class="mt-2">Select Payment Gateway</label>
   <div class="form-check mb-2">
    <input class="form-check-input" type="radio" name="gateway" v-model="gateway"  :value="1" id="stripe1">
    <label class="form-check-label" for="stripe1">
      Stripe
    </label>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="gateway"  v-model="gateway" :value="0" id="authorize1">
    <label class="form-check-label" for="authorize1">
      Authorize.net
    </label>
  </div>
  <button class="btn btn-success my-2" @click="setGateway">Update</button>
</template>
<script>
export default {
  name:"Setting",
  data() {
    return {
        gateway:null,
        errorMessage:"",
        successMessage:"",
        active:null,
    }
  },
  mounted() {
    this.getGateway();
  },
  methods: {
    setGateway() {
            if (this.gateway != null) {
                const value = { gateway: this.gateway };
                axios.post("/update/gateway", value).then((response)=>{
                    this.successMessage=response.data;
                    this.getGateway();
                });
                setTimeout(() => {
                    this.successMessage=""
                }, 10000);
                // window.location.href = window.location.href
            }else{
                Swal.fire({
                icon: "error",
                title: "Error",
                text: "Please Select Gateway",
              });
            }
     },
        getGateway(){
         axios.get('/get/gateway').then((res)=>{
            this.active=res.data
         })
        },
  },

}
</script>
