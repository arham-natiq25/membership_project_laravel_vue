<template>
    <!-- input starts  -->
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="title">Enter Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="short_desc">Enter Short Description</label>
                    <input type="text" class="form-control" id="short_desc" name="short_desc">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="short_desc">Enter Long Description</label>
                    <textarea type="text" class="form-control" id="short_desc" name="short_desc"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="location[]">Location</label> <br>
                <input type="checkbox" name="location[]" id="noo">  Location 1 <br>
                <input type="checkbox" name="location[]" id="noo">  Location 2 <br>
                <input type="checkbox" name="location[]" id="noo">  Location 3 <br>
            </div>
        </div>
        </div>

        <button class="btn btn-primary">Submit</button>
    </div>
    <!-- input ends -->
    <!-- details section  -->
    <div>
        <div class="row m-2">
    <div class="col-md-10">
      <div class="d-flex justify-content-between align-items-between">
        <h5>All Routes</h5>
        <button class="btn btn-primary">Create</button>
      </div>
    </div>
    <div class="col-md-10 mt-2">
      <div class="table-responsive">
        <table class="table table-secondary table-bordered">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col" style="width: 40%;">Title</th>
              <th scope="col">Locations</th>
              <th scope="col">Description</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr class="">
              <td scope="row">1</td>
              <td>Title here</td>
              <td>Loc 1 <br> Loc 2 </td>
              <td>Lorem ipsum dolor sit amet</td>
              <td>
                  <i class="fas fa-trash-alt btn btn-danger text-white btn-sm mx-2"></i>
                  <i class="far fa-edit btn btn-primary text-white btn-sm "></i>

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
    name:"Route"
}
</script>
