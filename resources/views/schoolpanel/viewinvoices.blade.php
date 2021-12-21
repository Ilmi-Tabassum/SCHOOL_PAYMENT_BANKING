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
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">View Invoices</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">View Invoices</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

  <div class="card-header">
    <form method="post" action="{{route('SearchInvoiceMonthWise')}}">
      @csrf
       <div class="row">
           <div class="col">
               <div class="form-group">
                   <select class="form-control select2" name="class_id" id="class_id" >
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
               <select class="form-control" name="month_number"  id="month_number" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
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
                       <option value="2021" selected>2021</option>
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
           <div class="col">
               <div class="form-group">
                   <select class="form-control" name="status" id="status" >
                       <option value="">Select status</option>
                        <option value=1>Paid</option>
                       <option value=0>Due</option>

                   </select>
               </div>
           </div>
        <div class="col-sm">
         <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22;">
          <span style="margin-left:5px">Search</span>
         </button>
        </div>

       </div>
    </form>

  </div>


 <table class="table table-hover table-condensed table-striped" >
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
      <td>{{$i}}</td>
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

            <a href='#' class='tooltip-button EditInvoice' id='{{$data->id}}' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
            <i class='nav-icon fas fa-edit text-success'></i></a>

          <!--   <a href="{{ route('invoice.show',$data->invoice_no) }}" class='tooltip-button ' id="invoice_no" data-toggle="modal" data-original-title='edit'data-target="#modal-default-edit" style='padding-right: 10px' >
                <i class='nav-icon fas fa-edit text-success'></i></a> -->
       </td>
    </tr>
    @endforeach
  </tbody>

</table>

<div class="d-flex">
    <div class="mx-auto">
         {{$invoices->links("pagination::bootstrap-4")}}
    </div>
</div>

    <div class="modal fade" id="modal-default-edit" data-backdrop="static" >
        <div class="modal-dialog">
            <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                <div class="modal-header ab_bank_modal_background_color">
                    <h4 class="modal-title white-color"> <i class="fas fa-user-edit mr-2"></i> Edit  </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <form method="POST" action="{{route('invoice.store')}}" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group"  >
                                        <div>
                                        <input type="hidden" name="hidden_invoice_id" id="hidden_invoice_id" value="">

                                        <select class="form-control" name="pay_status" id="ifpaid" onchange="hideShow()">
                                            <option value="" selected disabled>Select Payment status</option>
                                            <option value="1">Paid</option>
                                            <option value="0">Due</option>
                                            <option value="2">Unverified</option>
                                        </select>
                                        </div>
                                    </div>

                                        <div class="form-group" style="display: none" id="ifpaid2" >
                                            <select class="form-control select2"  name="pay_type" id="ifpaid4" onchange="hideShow2()" >
                                            <option value="" selected disabled>Select Payment type</option>
                                            <option value="1">Online</option>
                                            <option value="2">Cash</option>
                                        </select>
                                        </div>

                                    <div class="form-group" id="ifpaid3" style="display: none">
                                        <input type="text" class="form-control"  name="trxn" value=""  placeholder="Trxn ID / Invoice No"  >
                                    </div>

                                    </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection


@section('page_specific_script')

<script type="text/javascript">

   function hideShow() {
        var x = document.getElementById("ifpaid").value;
        var y = document.getElementById("ifpaid2");
        var z = document.getElementById("ifpaid3");

        if (x == 1) {
            y.style.display = "block";
            }
        else if(x == 2)
        {
            y.style.display = "none";
            z.style.display = "none";
            y.value="";
            z.value="";


        }
        else if(x == 3)
        {
            y.style.display = "none";
            z.style.display = "none";
            y.value="";
            z.value="";


        }
        else {
            y.style.display = "none";
            z.style.display = "none";
            y.value="";
            z.value="";

        }


    };

    function hideShow2() {
        var x = document.getElementById("ifpaid4").value;
        var y = document.getElementById("ifpaid3");
        if (x == 1) {
            y.style.display = "block";

        }
        else if (x == 2) {
            y.style.display = "block";

        }
        else {
            y.style.display = "none";
        }


    };



     $(".EditInvoice").click(function(){
       $('#modal-default-edit').modal('show');
        id = $(this).attr('id');
          var url = globalURL + "edit-invoice-details/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){

                    $("#ifpaid").val(response[0].status);

                    $("#hidden_invoice_id").val(response[0].id);
                    document.getElementById("ifpaid").value=response[0].id;
                    document.getElementById("hidden_invoice_id").value=response[0].invoice_no;
                    console.log(response[0].invoice_no);

                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });
    });

</script>

@endsection
