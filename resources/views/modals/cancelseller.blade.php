<div class="modal fade" id="cancelmodalseller" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
        <form id = "frmCancelOrderSeller">
        @csrf
        <input type = "text" id = "hidReason" name = "hidReason">
        <input type = "text" id = "hidID" name = "hidID">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-2">
                <div id = "cancelmsg"></div>
                <div class="row">
                    <div class="col-12">Please select a reason for canceling your order.</div>
                </div>

                <div class="row ml-3">
                    <div class="col-12">

                        <div class="form-check mb-2 mt-2">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="Out of stock" id="r1">
                            <label class="form-check-label" for="r1">
                                Out of stock
                            </label>
                        </div>

                        <div class="form-check mb-2 ">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="Incorrect pricing" id="r2" >
                            <label class="form-check-label" for="r2">
                                Incorrect pricing
                            </label>
                        </div>

                        <div class="form-check mb-2 ">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="Sourcing delay" id="r3" >
                            <label class="form-check-label" for="r3">
                                Sourcing delay
                            </label>
                        </div>

                        <div class="form-check mb-2 ">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="Buyer requested due to duplicate order" id="r4" >
                            <label class="form-check-label" for="r4">
                                Buyer requested due to duplicate order
                            </label>
                        </div>

                        <div class="form-check mb-2 ">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="Ordered wrong product" id="r5" >
                            <label class="form-check-label" for="r5">
                                Ordered wrong product
                            </label>
                        </div>

                        <div class="form-check mb-2 ">
                            <input class="form-check-input cancelreason" name = "cancelreason" type="radio" value="1" id="r6" >
                            <label class="form-check-label" for="r6">
                                Others
                            </label>
                        </div>

                    </div>
                </div>
                <div class="row ml-3">
                    <div class="col-12">
                        <textarea id = "customreason" name = "customreason" class = "form-control" rows = "3" disabled></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id = "btncancelorderSeller">Proceed to cancellations</button>
            </div>
        </div>
        </form>
    </div>
</div>