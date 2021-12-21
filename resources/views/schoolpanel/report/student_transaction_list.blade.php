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
   <form method="POST" action="{{route('student_search_trxn')}}">
      @csrf
        <div class="row">
            <div class="col-align-self-end">
                    <h4><b></b></h4>
            </div>

            <div class="col-sm align-self-start">
                <input class="form-control" id="sdate" name="sdate" placeholder="Start Date" type="text"/>
            </div>


            <div class="col-sm">

                <h4> <input type="text" class="form-control" placeholder="Student ID " name="student_id" autocomplete="off"></h4>

            </div>


            <div class="col-sm">

             <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Search</button>

            </div>

        </div>
      </form>
    </div>

 <div style="clear:both; height:10px;"></div>
 <div class="card"  style="margin-right:1%;margin-left:20px">
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
          $table_option .= "<td style='text-align:center'>$value->student_id</td>";
          $table_option .= "<td>$value->trx_id</td>";
          $table_option .= "<td>$value->amount</td>";
          $table_option .= "<td>$value->trn_date</td>";
          $table_option .= "<td>$value->status</td>";
          $table_option .= "<td>No</td>";
          $table_option .= "<td style='text-align:center'>

          <a href='#show-details' class='tooltip-button showTrxn' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#transaction_modal'>
          <i class='nav-icon fas fa-eye text'></i></a>

          <a href='#update-transaction' class='tooltip-button UpdateTrxn' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#transaction_update_modal'>
          <i class='nav-icon fas fa-edit text-success'></i></a>


          </td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

  </tbody>
  </table>
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
          <form method="post" action="{{route('student_update_trxn')}}">
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
                    <tr>
                      <th style="width:30%">Student ID</th>
                      <td style="width:5%">:</td>
                      <td>
                        <input type="text" class="form-control" id="student_id11" name="student_id"  style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Amount</th>
                      <td style="width:5%">:</td>
                       <td>
                        <input type="text" class="form-control" id="amount11" name="amount"  style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Order ID</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="order_id11" name="order_id"  style="height: 30px">
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
                         <input type="text" class="form-control" id="method11" name="method" style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Trxn Date</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="trxn_date11" name="trn_date" style="height: 30px">
                      </td>
                    </tr>

                    <tr>
                      <th style="width:30%">Invoice No</th>
                      <td style="width:5%">:</td>
                      <td>
                         <input type="text" class="form-control" id="invoice_no11" name="invoice_no"  style="height: 30px">
                      </td>
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
                <button type="submit" class="btn btn-danger" style="background-color: #ee1b22;border-color: #ee1b22">Update</button>
               </div>

          </div>
           </form>
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
