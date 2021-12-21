@extends('master')

@section('title','Student Ledger')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')
     
   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Student Ledger</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Student Ledger</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

            
<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  
 <table class="table table-hover table-condensed table-striped" >

   <div class="card-header">
     <form method="post" action="{{route('searchStudentledger')}}">
      @csrf
         <div class="row">
            <div class="col-md-4 col-sm-6">
                <div>
                  <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                      <option value="">Select Student ID</option>
                      @foreach($students as $data)
                        <option value="{{$data->id}}">{{$data->student_id}}</option>
                      @endforeach
                  </select>
                 </div>
              
            </div>

            <div class="col-md-8 col-sm-6">
             <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22;">
              <span style="margin-left:5px">Search Student Ledger</span>
             </button>
            </div>

         </div>
      </form>

     </div>
  <thead >
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>School Name</th>
      <th>Student ID</th>
      <th>Invoice No</th>
      <th>Month</th>
      <th>Amount</th>
    </tr>
  </thead>

 @if($hasData==1)
  <tbody>
      @php $i=0;@endphp
      @foreach($student_ledger_data as $d)
        @php $i++;@endphp
        <tr>
          <td>{{$i}}</td>
          <td>{{$d->school_name}}</td>
          <td>{{$d->full_student_id}}</td>
          <td>{{$d->invoice_no}}</td>
          <td>
            <?php 
              $numeric_month = (int)$d->month;
               
              if ($numeric_month==1) {
                echo "January";
              }
              if ($numeric_month==2) {
                echo "February";
              }
              if ($numeric_month==3) {
                echo "March";
              }
              if ($numeric_month==4) {
                echo "April";
              }
              if ($numeric_month==5) {
                echo "May";
              }
              if ($numeric_month==6) {
                echo "June";
              }
              if ($numeric_month==7) {
                echo "July";
              }
              
              if ($numeric_month == 8) {
                echo "August";
              }
              if ($numeric_month == 9) {
                echo "September";
              }

              if ($numeric_month == 10) {
                echo "October";
              }
              if ($numeric_month == 11) {
                echo "November";
              }
              if ($numeric_month == 12) {
                echo "December";
              }
              
            ?>
          </td>
          <td>{{$d->total_amount}}</td>
          
        </tr>
      @endforeach
  </tbody>
  @endif                   
</table>

<div class="d-flex">
  @if($hasData==1)
  <div class="mx-auto">
        {{$student_ledger_data->links("pagination::bootstrap-4")}}
  </div>
  @endif
</div>  

</div>   

  
<aside class="control-sidebar control-sidebar-dark"> </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
 /*Page specific script*/

</script>

@endsection