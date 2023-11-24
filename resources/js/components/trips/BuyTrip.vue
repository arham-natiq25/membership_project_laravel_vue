<template>
    <div class="modal fade show" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h4 class="modal-title">Enter details to buy trip</h4>
                        <button type="button" class="close" @click="closeModal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="seats">Seats</label>
                                <input type="text" class="form-control" id="seats" name="seats" />
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
</template>
<script>

export default ({
    name:"BuyTrip",
   methods:{
    openEditSeatsModal(location) {
            this.selectedLocation = location;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedLocation = null;
            this.seats = "";
        },
   }
})
</script>
