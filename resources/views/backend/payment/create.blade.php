<div class="row">

    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('payment_setup.store') }}/store" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <input type="hidden" name="hidden_payment_id" id="hidden_payment_id" value="">

                <div class="form-group">
                    <label for="payment_for">Payment For <span class="text-danger">*</span></label>
                    <select class="form-control" id="payment_for" name="payment_for">
                        <option value="" selected disabled>Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" >{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_user_name">Payment User Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_user_name" placeholder="User Name" value="" name="payment_user_name">
                </div>
                <div class="form-group">
                    <label for="payment_url">Payment Url <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_url" placeholder="Title" value="" name="payment_url">
                </div>
                <div class="form-group">
                    <label for="payment_password">Payment Password <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_password" placeholder="Url" value="" name="payment_password">
                </div>
                <div class="form-group">
                    <label for="payment_unique_code">Payment Unique Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_unique_code" placeholder="Unique Code" value="" name="payment_unique_code">
                </div>
                <div class="form-group">
                    <label for="payment_return_url">Payment Return Url<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_return_url" placeholder="Return Url" value="" name="payment_return_url">
                </div>
                <div class="form-group">
                    <label for="payment_webhook">Payment Webhook <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_webhook" placeholder="Webhook" value="" name="payment_webhook">
                </div>
            </div>
        </form>
        <!-- /.card -->
    </div>

</div>
