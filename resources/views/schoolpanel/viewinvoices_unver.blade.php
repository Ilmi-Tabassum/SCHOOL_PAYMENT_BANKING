@extends('master')

@section('title','View Invoices')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Unverified Invoices</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Unverified Invoices</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

  <div class="card-header">

    <form method="post" action="{{route('SearchInvoice_unver')}}">
      @csrf
       <div class="row">
           <div class="col">
           <div class="form-group">
               <select class="form-control select2" name="class_id" id="class_id" required="">
                   <option value="">Select Class</option>
                   <?php
                   foreach ($classes as $value) {
                       echo "<option value='$value->id'>$value->name</option>";
                   }
                   ?>
               </select>
           </div>
           </div>
          <div class="col">
            <div>
               <select class="form-control" name="month_number"  id="month_number" required="" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
                <option value="">Select Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
             </div>
          </div>

           <div class="col">
               <div>
                   <select class="form-control" name="year_number" id="year_number">
                       <option value="">Select Year</option>
                       <option value="2018">2018</option>
                       <option value="2019">2019</option>
                       <option value="2020">2020</option>
                       <option value="2021" selected >2021</option>
                       <option value="2022">2022</option>
                       <option value="2023">2023</option>
                       <option value="2024">2024</option>
                       <option value="2025">2025</option>
                       <option value="2026">2026</option>
                       <option value="2027">2027</option>
                       <option value="2028">2028</option>
                       <option value="2029">2029</option>
                       <option value="2030">2030</option>
                   </select>
               </div>
           </div>

        <div class="col-sm">
         <button type="submit" class="btn btn-primary" required="" style="background-color:#ee1b22;border-color:#ee1b22;">
          <span style="margin-left:5px">Search</span>
         </button>
        </div>

       </div>
    </form>

      <div style="clear:both; height:10px;"></div>
      <input type="checkbox" name="invoice_all" onclick="hideShow()"style="padding-right: 5px" > <strong>Select All</strong>
  </div>

    <form method="post" action="{{route('invoice.approveMultiple')}}">
        @csrf
 <table class="table table-hover table-condensed table-striped table-bordered" >
  <thead >
    <tr style="background-color:#fff">
      <th>SL</th>
      <th>Invoice No</th>
      <th>Student ID</th>
      <th>Class Name</th>
      <th>Total Amount</th>
      <th>Payment ID</th>
      <th>Month</th>
      <th>Year</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>

  <tbody>
    @php $i=0; @endphp
    @foreach($invoices as $data)
     @php $i++; @endphp
    <tr>
      <td> <input type="checkbox" name="invoice[]" id="invoice" class="checkAll" value="{{$data->id}}" disabled style="padding-right: 5px" > {{$i}}</td>
      <td>{{$data->invoice_no}}</td>
      <td>{{$data->full_student_id}}</td>
      <td>{{$data->class_name}}</td>
      <td>{{$data->total_amount}}</td>
      <td>{{$data->payment_id}}</td>
      <td>
        <?php

          if ($data->month=='01') {
            echo "January";
          }
          elseif ($data->month=='02') {
            echo "February";
          }
          elseif ($data->month=='03') {
            echo "March";
          }
          elseif ($data->month=='04') {
            echo "April";
          }
          elseif ($data->month=='05') {
            echo "May";
          }
          elseif ($data->month=='06') {
            echo "June";
          }
          elseif ($data->month=='07') {
            echo "July";
          }
          elseif ($data->month=='08') {
            echo "August";
          }
          elseif ($data->month=='09') {
            echo "September";
          }
          elseif ($data->month=='10') {
            echo "October";
          }
          elseif ($data->month=='11') {
            echo "November";
          }
          elseif ($data->month=='12') {
            echo "December";
          }
          else{
            echo " ";
          }

        ?>
      </td>
      <td>{{$data->year}}</td>
      <td>
        <?php
          if ($data->status==1) {
            echo "Paid";
          }
          elseif ($data->status==2) {
              echo "Unverified";
          }
          else{
            echo "Due";
          }

        ?>
      </td>
        <td><a href="{{ route('invoice.show',$data->invoice_no) }}" class='tooltip-button editSectionInfoItem' id="invoice_no" data-original-title='edit' style='padding-right: 10px' >
                <i class='nav-icon fas fa-eye text-success'></i></a>

          <!--  <a href="javascript:;" data-id="{{$data->invoice_no}}" data-original-title="Edit Notice" data-toggle="modal" data-url="{{ route('invoice.edit') }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                <i class='nav-icon fas fa-edit text-success'></i>
            </a> -->

            <a href="{{ route('invoice.approve',$data->id) }}" class='tooltip-button EditInvoice' id='{{$data->id}}' data-original-title='Edit' style='padding-right: 10px' >
            <i class='nav-icon fas fa-check-circle text-success'></i></a>

          <!--   <a href="{{ route('invoice.show',$data->invoice_no) }}" class='tooltip-button ' id="invoice_no" data-toggle="modal" data-original-title='edit'data-target="#modal-default-edit" style='padding-right: 10px' >
                <i class='nav-icon fas fa-edit text-success'></i></a> -->
       </td>
    </tr>
    @endforeach
  </tbody>

</table>
    <button type="submit" class="btn btn-primary"  id='approve' style="background-color:#1d675f;border-color:#2c8274;display: none;width: 100%;" >
       <span style="margin-left:5px">Approve All</span>
    </button>
    </form>
<div class="d-flex">
    <div class="mx-auto">
         {{$invoices->links("pagination::bootstrap-4")}}
    </div>
</div>


</div>




<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection


@section('page_specific_script')

<script type="text/javascript">


        function hideShow() {

        let y = document.getElementById("approve");
        if (y.style.display === "none") {
        y.style.display = "block";
            $( ".checkAll").prop( "checked", true );
            $( ".checkAll").prop( "disabled", false );
            //document.getElementsByClassName("checkAll").disabled =false;
    }
        else {
            y.style.display = "none";
            $( ".checkAll").prop( "checked", false );
        }
    }

/*     $("#approve").click(function(){
          var url = globalURL + "view-invoices/Approve_all";

          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    console.log("hello")
                }
            });
         window.location.reload();
    });*/

</script>

@endsection
