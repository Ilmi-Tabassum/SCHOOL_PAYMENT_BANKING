@extends('master')

@section('title','Transactions')

@section('page_specific_css')
    .alert-message {
    color: red;
    }
@endsection

@section('content')

    <div id="page-content" style="margin-top: 0px;margin-left: 20px">
        <section class="content-header" style="margin-right: 1%;height: 50px">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Transaction List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active">Transaction List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div style="clear:both; height:10px;"></div>
        <form method="POST" action="{{route('search_trxn')}}">
            @csrf
            <div class="row">
                <div class="col-align-self-end">
                    <h4><b></b></h4>
                </div>

                <div class="col-sm">
                    <div class="form-group">

                        <input type="text" class="form-control " id="start_date" name="start_date" onfocus="(this.type='date')"
                               onblur="(this.type='text')" placeholder="Enter Starting Date">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="end_date" name="end_date" onfocus="(this.type='date')"
                               onblur="(this.type='text')" placeholder="Enter Ending Date"  >
                    </div>
                </div>


                <div class="col-sm">
                    <div class="form-group">
                        <h4> <input type="text" class="form-control" placeholder="Student ID " name="student_id" autocomplete="off"></h4>
                    </div>
                </div>
                @if(!(Auth::user()->school_id))
                <div class="col-sm">
                    <div class="form-group">
                        <select class="form-control select2" name="schoolid" id="schoolid" required="">
                            <option selected disabled>Select School</option>
                            <?php
                            foreach ($school_list as $key => $value) {
                                if(request()->get('schoolid') == $value["id"]){
                                    echo "<option value='$value[id]' selected>$value[school_name]</option>";
                                }else{
                                    echo "<option value='$value[id]'>$value[school_name]</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-sm">
                    <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Search</button>

                </div>

            </div>
        </form>
    </div>

    <div style="clear:both; height:10px;"></div>
    <div class="card"  style="margin-right:1%;margin-left:20px">
        @if(!empty($transaction_lists))
            <table class="table table-hover table-condensed table-striped table-sm">
                <thead style="background-color:#f1eeee">
                <tr>
                    <th style="text-align: center;">SL</th>
                    <th style="text-align: center;">Student ID</th>
                    <th>TRX ID</th>
                    <th>Amount </th>
                    <th>Date </th>
                    <th>Status </th>
                    <th>Remarks </th>
                    <th style="text-align: center;">Actions</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $table_option = "";
                $serial_no = 1;
                foreach ($transaction_lists as $key => $value) {
                    $table_option .= "<tr>";
                    $table_option .= "<td style='text-align:center'>" . $serial_no++ . "</td>";
                    $table_option .= "<td style='text-align:center'>$value->sid</td>";
                    $table_option .= "<td>$value->trx_id</td>";
                    $table_option .= "<td>$value->amount</td>";
                    $table_option .= "<td>$value->trn_date</td>";
                    $table_option .= "<td>$value->status</td>";
                    $table_option .= "<td>No</td>";
                    $table_option .= "<td style='text-align:center'>

          <a href='#show-details' class='tooltip-button showTrxn' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#transaction_modal' title='Details'>
          <i class='nav-icon fas fa-eye text-success'></i></a>

          <a href='#update-transaction' class='tooltip-button UpdateTrxn' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#transaction_update_modal' title='Delete'>
          <i class='nav-icon fas fa-edit text-success'></i></a>


          </td>";

                    $table_option .= "</tr>";
                }

                echo $table_option;

                ?>

                </tbody>

            </table>
        @else
            <span style="color: #8b0000;text-align: center;"><b> No data found</b></span>
        @endif
    </div>
    <div class="d-flex">
        <div class="mx-auto">

        </div>
    </div>


    <div class="modal fade" id="transaction_modal" data-backdrop="static" >
        <div class="modal-dialog">
            <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                <div class="modal-header ab_bank_modal_background_color">
                    <h4 class="modal-title white-color" id="student_id_display"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title" style="font-weight: bolder;">Transaction Details</h3>
                                        </div>
                                        <table class="table table-sm">
                                            <tbody>
                                            <tr>
                                                <th style="width:30%">Student ID</th>
                                                <td style="width:5%">:</td>
                                                <td id="student_id1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Amount</th>
                                                <td style="width:5%">:</td>
                                                <td id="amount1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Order ID</th>
                                                <td style="width:5%">:</td>
                                                <td id="order_id1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Trxn ID</th>
                                                <td style="width:5%">:</td>
                                                <td id="trxn_id1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Bank Trxn ID</th>
                                                <td style="width:5%">:</td>
                                                <td id="bank_txn_id1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Return Code</th>
                                                <td style="width:5%">:</td>
                                                <td id="return_code1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Status</th>
                                                <td style="width:5%">:</td>
                                                <td id="status1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Method</th>
                                                <td style="width:5%">:</td>
                                                <td id="method1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Trxn Date</th>
                                                <td style="width:5%">:</td>
                                                <td id="trxn_date1"></td>
                                            </tr>

                                            <tr>
                                                <th style="width:30%">Invoice No</th>
                                                <td style="width:5%">:</td>
                                                <td id="invoice_no1"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="transaction_update_modal" data-backdrop="static" >
      <div class="modal-dialog">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">

          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="student_id_display1"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{route('update_trxn')}}">
            @csrf
          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
            <div class="card-body">
            <div class="form-group">
             <div class="card">
              <div class="card-header">
                <h3 class="card-title" style="font-weight: bolder;">Update Transaction Details</h3>
              </div>

                <table class="table table-sm">
                  <tbody>
                     <input type="hidden" name="item_id_trxn" id="item_id_trxn" value="">
                     <input type="hidden" name="studentID" id="hidenStudentID" value="">
                    <tr>
                      <th style="width:30%">Student ID</th>
                      <td style="width:5%">:</td>
                      <td>
                        <input type="text" class="form-control" id="student_id11" name="student_id"  style="height: 30px" readonly>
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Amount</th>
                      <td style="width:5%">:</td>
                       <td>
                        <input type="text" class="form-control" id="amount11" name="amount"  style="height: 30px" readonly >
                      </td>
                    </tr>


                    <tr>
                      <th style="width:30%">Trxn ID</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="trxn_id11" name="trx_id"  style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Bank Trxn ID</th>
                      <td style="width:5%">:</td>
                      <td>
                        <input type="text" class="form-control" id="bank_txn_id11" name="bank_trx_id"  style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Return Code</th>
                      <td style="width:5%">:</td>
                      <td>
                        <input type="text" class="form-control" id="return_code11" name="return_code"  style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Status</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="status11" name="status"  style="height: 30px">
                      </td>
                    </tr>

                     <tr>
                      <th style="width:30%">Method</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="method11" name="method" style="height: 30px" readonly>
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Trxn Date</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="trxn_date11" name="trn_date" style="height: 30px" readonly>
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Invoice No</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="invoice_no11" name="invoice_no"  style="height: 30px" readonly>
                      </td>
                    </tr>
                  </tbody>
                </table>

              </div>

                </div>

            </div>
        </div>
    </div>
          </div>
          </form>
        </div>
      </div>
    </div>





    <aside class="control-sidebar control-sidebar-dark">

    </aside>
@endsection

@section('page_specific_script')

    <script type="text/javascript">

        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var port = window.location.port;
        var pathname = window.location.pathname;
        pathname = pathname.split("/");
        var domainName = pathname[1];

        if(port){
            var globalURL = protocol + "//" + hostname + ":" + port + "/";
        }else{
            var globalURL = protocol + "//" + hostname + "/";
        }


        $(".showTrxn").click(function(){
            id = $(this).attr('id');
            var url = globalURL + "transactions-details/" + id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                    document.getElementById("student_id_display").innerText = response[0].student_id;
                    document.getElementById("student_id1").innerText = response[0].student_id;
                    document.getElementById("amount1").innerText = response[0].amount;
                    document.getElementById("order_id1").innerText = response[0].order_id;
                    document.getElementById("trxn_id1").innerText = response[0].trx_id;
                    document.getElementById("bank_txn_id1").innerText = response[0].bank_trx_id;
                    document.getElementById("return_code1").innerText = response[0].return_code;
                    document.getElementById("status1").innerText = response[0].status;
                    document.getElementById("method1").innerText = response[0].method;
                    document.getElementById("trxn_date1").innerText = response[0].trn_date;
                    document.getElementById("invoice_no1").innerText = response[0].invoice_no;
                },
            });
        });


        $(".UpdateTrxn").click(function(){
            id = $(this).attr('id');
            var url = globalURL + "edit-transactions/" + id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    document.getElementById("student_id_display1").innerText = response[0].s_id;
                    $("#student_id11").val(response[0].s_id);
                    $("#amount11").val(response[0].amount);
                    $("#trxn_id11").val(response[0].trx_id);
                    $("#bank_txn_id11").val(response[0].bank_trx_id);
                    $("#return_code11").val(response[0].return_code);
                    $("#status11").val(response[0].status);
                    $("#method11").val(response[0].method);
                    $("#trxn_date11").val(response[0].trn_date);
                    $("#invoice_no11").val(response[0].invoice_no);
                    $("#item_id_trxn").val(response[0].id);
                    $("#hidenStudentID").val(response[0].student_id);

                },
            });
        });


    </script>

@endsection
