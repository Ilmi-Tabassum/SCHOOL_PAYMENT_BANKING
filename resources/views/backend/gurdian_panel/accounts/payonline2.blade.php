@extends('master')

@section('title','Pay Online')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Pay Online </h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Pay Online</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Pay Online</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('payonline')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Pay Online</span>
        </a>
        </div>
  </div>

 <table class="table table-hover table-bordered table-sm" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th></th>
      <th>SL</th>
      <th>Student Name</th>
      <th>Student ID</th>
      <th>Invoice ID</th>
      <th>Total Amount</th>
      <th>Transaction ID</th>
      <th>Status</th>
      <th>Method</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <input type="checkbox" name="check_mark" id="check_mark_1">
      </td>
      <td>1</td>
      <td>name 1 </td>
      <td>202100010005</td>
      <td>1659875</td>
      <td id="totalAmount_1" class="totalAmount">1250</td>
      <td>A59988ffdd</td>
      <td>Paid</td>
      <td>Bkash</td>
      <td>16/03/2021</td>
      <td>
        <a href="{{ route('payonline') }}" data-btn="no" data-title="Pay Now" data-original-title="Pay Now" style="padding-right: 10px" class="tooltip-button btn btn-primary btn-sm" title="Pay Now">
                         Pay Now
      </td>
    </tr>
    <tr>
      <td>
        <input type="checkbox" name="check_mark" id="check_mark_2">
      </td>
      <td>2</td>
      <td>name 2 </td>
      <td>202100010005</td>
      <td>1659875</td>
      <td id="totalAmount_2" class="totalAmount">1250</td>
      <td>A59988ffdd</td>
      <td>Paid</td>
      <td>Bkash</td>
      <td>16/03/2021</td>
      <td>
        <a href="{{ route('payonline') }}" data-btn="no" data-title="Pay Now" data-original-title="Pay Now" style="padding-right: 10px" class="tooltip-button btn btn-primary btn-sm" title="Pay Now">
                         Pay Now
      </td>
    </tr>

    <tr>
        <td align="right" colspan="8"><b>Total</b></td>
       <td align="center">
          <b id="totalAmount5">0</b>
       </td>
       <td colspan="2" align="center">
          <a href="{{ route('payonline') }}" data-btn="no" data-title="Pay Now" data-original-title="Pay Now" style="padding-right: 10px" class="tooltip-button btn btn-primary btn-sm" title="Pay Now">
                         Pay Now
       </td>
    </tr>
  </tbody>

</table>
<div class="d-flex">
  @if($hasData==1)
    <div class="mx-auto">
         {{$transactions->links("pagination::bootstrap-4")}}
    </div>
  @endif
</div>







</div>

  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="feeshead_title">Pay Online</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('gofeessubhead') }}">
              @csrf
                <div class="card-body">

                  <div class="form-group">
                    <label for="school_id">School Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="school_id" id="school_id" required oninvalid="this.setCustomValidity('Select a school name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select school name</option>
                          @foreach($schools as $data)
                            <option value="{{$data->id}}">{{$data->school_name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                  <div class="form-group">
                    <label for="student_id">Student ID <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                          <option value="">Select Student ID</option>
                          @foreach($students as $data)
                            <option value="{{$data->id}}">{{$data->student_id}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                   <div class="form-group">
                    <label for="class_name">Class Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="class_name" id="class_name" required oninvalid="this.setCustomValidity('Select a class name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select class name</option>
                          @foreach($class_names as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                   <div class="form-group">
                    <label for="session_name">Session <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="session_name" id="session_name" required oninvalid="this.setCustomValidity('Select a session name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select session name</option>
                          @foreach($sessions as $data)
                            <option value="{{$data->name}}">{{$data->name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>

                  <!--  <div class="form-group">
                    <label for="amount">Amount <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="amount" name="amount" autocomplete="off" placeholder="e.g. 200" required maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                   </div> -->

                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Next</button>
               </div>
              </form>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>



  <aside class="control-sidebar control-sidebar-dark">

  </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">

</script>

@endsection
