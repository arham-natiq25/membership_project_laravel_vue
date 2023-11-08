<template>
      <!-- checkout part  -->
      <div>
      <h3>Checkout</h3>
      <div class="row">
        <div class="col-4">
          Your Memberships are : One , Two
        </div>
      </div>
      <div class="row">
        <div class="col-4 text-danger">
          Your Total price = $1000
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          Card Method here
        </div>
      </div>
      <div class="col-md-4 my-2">
        <button class="btn btn-primary">Checkout</button>
      </div>
    </div>
</template>
<script>
export default{}
</script>
