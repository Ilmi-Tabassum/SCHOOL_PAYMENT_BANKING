<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{route('manageProfile')}}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">

                <div class="form-group"  >
                    <div>
                        <input type="hidden" name="hidden_invoice_id" value="">

                        <select class="form-control select2" name="pay_status" id="ifpaid" onchange="hideShow()" required="">
                            <option value="" selected disabled>Select Payment status</option>
                            <option value="1">Paid</option>
                            <option value="2">Pending</option>
                            <option value="3">Hold</option>

                        </select>
                    </div>
                </div>

                <div class="form-group"  >
                    <select class="form-control select2" style="display: none" name="pay_type" id="ifpaid2" onchange="hideShow2()" required="">
                        <option value="0" selected disabled>Select Payment type</option>
                        <option value="1">Online</option>
                        <option value="2">Cash</option>
                    </select>
                </div>

                <div class="form-group" id="ifpaid3" style="display: none">
                    <input type="text" class="form-control"  name="name" value=""  placeholder="Trxn ID / Invoice No"  required="">
                </div>

            </div>




</form>
    </div>
</div>
