<!DOCTYPE html>
<html lang="en">
  @include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">
  @include('common.preloader')

  <!-- Top navigation bar start-->
    @include('common.top-navbar')
  <!-- Top navigation bar end-->

  <!-- left navigationbar start -->
    @include('common.left-navbar')
  <!-- left navigationbar end -->

  <div class="content-wrapper">

  <div id="page-content" style="margin-top: 20px;margin-left: 20px">

       <!-- Alert part -->
      <div id="page-content" style="margin-top: 20px;margin-left: 20px">
          @if(Session::has('success'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-success collapse">
                       <i class="nav-icon fas fa-info-circle"></i> {{ Session::get('success') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-times"></i></a>
                  </div>
              </div>
          </div>
          @endif



          @if(Session::has('error'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-danger collapse">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>  {{ Session::get('error') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-trash-alt"></i></a>
                  </div>
              </div>
          </div>
          @endif
      </div>

      <!-- ./ alert part -->
       <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Invoice List </h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Invoice List</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  <!--  <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Pay Online</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('payonline')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Pay Online</span>
        </a>
        </div>
  </div> -->
      <form method="post" action="{{route('paySearch')}}">
          @csrf
          <div class="row">
              <div class="col-md-4 col-sm-6">
                  <div>
                      <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                          <option value="">Select Student ID</option>
                          @foreach($students as $data)
                              <option value="{{$data->full_student_id}}">{{$data->full_student_id}}</option>
                          @endforeach
                      </select>
                  </div>

              </div>

              <div class="col-md-8 col-sm-6">
                  <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22;">
                      <span style="margin-left:5px">Search</span>
                  </button>
              </div>

          </div>
      </form>

      @if($search===1)
 <table class="table table-hover table-bordered myTable">
  <thead>
    <tr style="background-color:#f1eeee">
      <th></th>
      <th style="text-align: center">SL</th>
      <th style="text-align: center">Student Name</th>
      <th style="text-align: center">Student ID</th>
      <th style="text-align: center">Invoice ID</th>
        <th style="text-align: center">Total Amount</th>
        <th style="text-align: center">Due Amount</th>

        <th style="text-align: center">Status</th>
      <th style="text-align: center">Actions</th>
      <th style="text-align: center">Invoice</th>
    </tr>
  </thead>

  <tbody>

    @php
      $i = 1;
    @endphp

    @foreach($invoices as $key => $value)

      <tr>
       <form method="POST" action="{{ route('paynow') }}" enctype="multipart/form-data" autocomplete="off">
          @csrf
        <td>
          <?php
              if($value->status == 0){
                ?>
                <input type="checkbox" name="check_mark" class="checkmark" value="{{$i}}">
                <input type="hidden" name="hiddenPayableAmount" value="{{$value->total_amount}}" id="hiddenTotalAmount_{{$i}}">
                <input type="hidden" name="hiddenInvoiceNo" value="{{$value->invoice_no}}" id="hiddenTotalInvoiceNo_{{$i}}">
                <input type="hidden" name="hiddenStudentID" value="{{$value->student_id}}" id="hiddenTotalStudentId_{{$i}}">
                <?php
              }else{
                ?>
                <input type="checkbox" name="check_mark" class="checkmark" value="{{$i}}" disabled="">
                <input type="hidden" name="hiddenPayableAmount" value="{{$value->total_amount}}" id="hiddenTotalAmount_{{$i}}">
                <input type="hidden" name="hiddenInvoiceNo" value="{{$value->invoice_no}}" id="hiddenTotalInvoiceNo_{{$i}}">
                <input type="hidden" name="hiddenStudentID" value="{{$value->student_id}}" id="hiddenTotalStudentId_{{$i}}">
                <?php
              }
          ?>

        </td>
        <td style="text-align: center">{{ $i }}</td>
        <td style="text-align: center"> {{ $value->name }} </td>
        <td style="text-align: center"> {{ $value->full_student_id }}</td>
        <td style="text-align: center"> {{ $value->invoice_no }}</td>
           <td id="totalAmount_{{$i}}" style="text-align: center">{{$value->total_amount}}</td>

       @if($value->due >0)
           <td style="color: red;text-align: center">{{$value->due}}</td>
           @else
           <td style="text-align: center">{{$value->due}}</td>
          @endif

        <td style="text-align: center">
          <?php
            if($value->status != 1){
              echo "<span class='badge badge-danger'>Due</span>";
            }else{
              echo "<span class='badge badge-success'>Paid</span>";
            }
          ?>
        </td>
        <td style="text-align: center">

          <?php
              if($value->status == 0){
                echo "
                  <button type='submit' class='btn btn-sm'  id='buttononclickdisable_$i' style='background-color: #639158; margin-right: 2px;'>Pay Now</button>";
              }else{
                echo "
                  <button class='btn btn-sm' id='buttononclickdisable_$i' style='background-color: #635e5e;
    border-color: #938c8c; color: #000;display: none;'>Pay Now</button>";
              }
            if($value->is_partial == 1 && $value->status != 1 && $value->status != 2){
                if($value->due > 0)
                    {
                        echo "<a type='button' id='partial_btnt' class='btn btn-sm partial_btn' name=$value->invoice_no data-toggle='modal' data-target='#modal-default'   style='background-color:#67aeaf;border-color:#72b3be '>
            <span style='margin-left:5px'>Partial
        </a>";
                    }else{
                    echo "<a type='button' id='partial_btnt' class='btn btn-sm '  style='background-color:#566060;border-color:#5b6667 ' disabled='true'>
            <span style='margin-left:5px'>Partial
        </a>";
                }

                }
            else{
                echo "<button  data-toggle='modal' data-target='#modal-custom' style='background-color:#477f78;border-color:#487880;display: none' >
            <span style='margin-left:5px'>Partial
        </button>";
            }
          ?>
        </td>
           <td style="text-align: center">
             <a href="{{ route('invoice.show',$value->invoice_no) }}" id="" class="btn medium hover-purple " role="presentation" href="" title="" >
                   <i class="fa fa-eye" aria-hidden="true"></i> Invoice
               </a>
           </td>
      </form>
    </tr>

    @php
          $i++;
    @endphp
    @endforeach

    <tr>
      <form method="POST" action="{{ route('payNowInTotal') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <td align="right" colspan="5"><b>Total</b></td>
       <td align="center">
          <input type="hidden" name="hiddenTotalInvoiceNos" id="hiddenTotalInvoiceNos" value="0">
          <input type="hidden" name="hiddenTotalStudentIDs" id="hiddenTotalStudentIDs" value="0">
          <input type="hidden" name="hiddenTotalPayableAmounts" id="hiddenTotalPayableAmounts" value="0">
          <input type="hidden" name="hiddenTotalPayableAmount" id="hiddenTotalPayableAmount" value="0">
          <b id="totalPayableAmount">0</b>
       </td>
       <td colspan="4" align="center">

           <button type="submit" class="btn btn-primary btn-sm" id="totalPaynowBtn" disabled="">
             Pay Now
           </button>
       </td>
       </form>
    </tr>
  </tbody>

</table>
      @endif
</div>

      <div class="modal fade" id="modal-default-edit" data-backdrop="static" >
          <div class="modal-dialog">
              <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                  <div class="modal-header ab_bank_modal_background_color">
                      <h4 class="modal-title white-color"> <i class="fas fa-user-edit mr-2"></i> Partial Payment  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <div class="modal-body">
                      <div class="row">

                          <div class="col-md-12">
                              <form method="POST" action="{{route('payNowPartial')}}" enctype="multipart/form-data" autocomplete="off">
                                  @csrf
                                  <div class="card-body">

                                      <div class="form-group">
                                          <div>
                                              <input type="hidden" name="hidden_invoice_no" id="hidden_invoice_no" value="">
                                             {{-- <label style="float: left;"> <b>Month: </b></label> <label id="labelForMonth"> January</label>--}}
                                          </div>
                                      </div>
  {{--                                    <div class="form-group" >
                                          <div>
                                              <label > <b>Name: </b></label> <label  id="labelForName">Mowmita </label>
                                          </div>
                                      </div>
                                      <div class="form-group" >
                                          <div>
                                              <label> <b>ID: </b></label> <label id="labelForID"> 30003261635</label>
                                          </div>
                                      </div>--}}
                                      <div class="form-group">
                                          <div>
                                              <label> <b>Total Amount: </b></label> <label id="labelForTAmount"style="color: #6480b0;font-size: large;"> 10500</label>
                                          </div>
                                      </div>
                                      <div class="form-group" >
                                          <div>
                                              <label> <b>Paid Amount: </b></label> <label id="labelForPAmount" style="color: darkgreen;font-size: large;"> 0</label>
                                          </div>
                                      </div>
                                      <div class="form-group" >
                                          <div>
                                              <label> <b>Due Amount: </b></label>  <label id="labelForDAmount" style="color: darkred;font-size: large"> 10500</label>
                                          </div>
                                      </div>
                                      <div class="form-group" >
                                          <div>
                                              <label for="amountPaid">Payable Amount:</label>
                                              <input type="text" name="amountPaid" id="amountPaid" value=""  oninput="this.value = this.value.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1');">
                                          </div>
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

  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>
</div>
   @include('common.page-script')
   @yield('custom-script')

</body>
</html>
<script>
    $(".partial_btn").click(function(){
        $('#modal-default-edit').modal('show');
        id = $(this).attr('name');
        //alert(id);
        var url = globalURL + "invoice/partial/" + id;
        $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            success: function(response){

                $("#labelForTAmount").val(response[0].total_amount);

                $("#labelForDAmount").val(response[0].due);
/*
                $("#hidden_invoice_no").val(response[0].invoice_no);
*/
var paid=(response[0].total_amount)-((response[0].due));
//                alert(paid);
                document.getElementById("hidden_invoice_no").value=response[0].invoice_no;
                document.getElementById("labelForDAmount").innerText=response[0].due;
                document.getElementById("labelForPAmount").innerText=paid;
var due=response[0].due;
                document.getElementById("labelForTAmount").innerText=response[0].total_amount;

                $("#amountPaid").keyup(function(){
                    //alert('hello');
                    var payable=document.getElementById("amountPaid").value;
                    //payable=parseInt(payable);
                  /*  alert(payable);
                    alert(due);*/
                    if(payable>due)
                    {
                        document.getElementById("amountPaid").value=due;
                    }
                });
                //console.log(response[0].invoice_no);

            },
            error: function(){
                alert('We are sorry. Please try again.');
            }
        });
    });

</script>
