<div class="row">
    <div class="col-md-12">
        <form method="POST" id="PostForm" action="{{ route('payment_setup.store') }}/store">
            @csrf
            <div class="card-body">
                <input type="hidden" name="hidden_payment_id" id="hidden_payment_id" value="{{ $details->id }}">
                <div class="form-group">
                    <label for="payment_for">Payment For <span class="text-danger">*</span></label>
                    <select class="form-control" id="payment_for" name="payment_for">
                            <option value="" selected disabled>Select School</option>
                        @foreach($schools as $school)
                            @if(in_array($school->id,explode(',',$details->school_id)))

                                <option value="{{ $school->id }}" selected="selected">{{ $school->school_name }}</option>


                            @else
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_user_name">Payment User Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_user_name" placeholder="Title" value="{{ $details->payment_user_name }}" name="payment_user_name">
                </div>
                <div class="form-group">
                    <label for="payment_url">Payment Url <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_url" placeholder="Title" value="{{ $details->payment_url }}" name="payment_url">
                </div>
                <div class="form-group">
                    <label for="payment_password">Payment Password <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_password" placeholder="Title" value="{{ $details->payment_password }}" name="payment_password">
                </div>
                <div class="form-group">
                    <label for="payment_unique_code">Payment Unique Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_unique_code" placeholder="Title" value="{{ $details->payment_unique_code }}" name="payment_unique_code">
                </div>
                <div class="form-group">
                    <label for="payment_return_url">Payment Return Url<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_return_url" placeholder="Title" value="{{ $details->payment_return_url }}" name="payment_return_url">
                </div>
                <div class="form-group">
                    <label for="payment_webhook">Payment Webhook <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="payment_webhook" placeholder="Title" value="{{ $details->payment_webhook }}" name="payment_webhook">
                </div>
            </div>
        </form>
    </div>
</div>
