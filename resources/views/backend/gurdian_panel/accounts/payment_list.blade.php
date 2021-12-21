@extends('master')

@section('title','Payment List')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Payment List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Payment List</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

 <table class="table table-hover table-condensed table-striped table-sm" >
   <div class="card-header">
     <form method="post" action="{{route('paymentList')}}">
      @csrf
         <div class="row">
            <div class="col-md-4 col-sm-6">
                <div>
                  <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                      <option value="">Select Student ID</option>
                      @foreach($students as $data)
                        <option value="{{$data->student_id}}">{{$data->student_id}}</option>
                      @endforeach
                  </select>
                 </div>

            </div>

            <div class="col-md-8 col-sm-6">
             <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22;">
              <span style="margin-left:5px">Search Payment</span>
             </button>
            </div>

         </div>
      </form>


     </div>
  <thead >
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>Student ID</th>
      <th>Invoice No</th>
      <th>Amount</th>
        <th>Invoice</th>
      <th>Date</th>
    </tr>
  </thead>



 @if($hasData==1)
  <tbody>
      @php $i=0; @endphp
      @foreach($payment_list as $datum)
       @php $i++; @endphp
        <tr>
          <td>{{$i}}</td>
          <td>{{$datum->student_id}}</td>
          <td>{{$datum->invoice_no}}</td>
          <td>{{$datum->total_amount-$datum->due}}</td>
            <td>
                <a href="{{ route('invoice.show',$datum->invoice_no) }}" id="" class="btn medium hover-purple " role="presentation" href="" title="" >
                    <i class="fa fa-eye" aria-hidden="true"></i> Invoice
                </a>
            </td>
          <td>{{$datum->created_at}}</td>
        </tr>
      @endforeach
  </tbody>
  @endif
</table>

<div class="d-flex">
   @if($hasData==1)
    <div class="mx-auto">
        {{$payment_list->links("pagination::bootstrap-4")}}
    </div>
  @endif
</div>

</div>





  <aside class="control-sidebar control-sidebar-dark"> </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
 //Page specific script

</script>

@endsection
