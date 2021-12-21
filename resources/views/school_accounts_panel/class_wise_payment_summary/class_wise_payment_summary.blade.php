@extends('master')

@section('title','Transactions')

@section('page_specific_css')
    .alert-message {
    color: red;
    }
@endsection

@section('content')

    <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Payment Summary</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">Payment Summary</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div id="page-content" style="margin-top: 0px;margin-left: 20px">
        <p style="font-size: 25px;">
            <a href="{{url('/school_accounts_panel/class_wise_payment_summary')}}" style="color: black"><b>Income statement</b></a>
        </p>
        <form method="POST" action="{{route('class_wise_payment_summary.search_trxn')}}">
            @csrf
            <div class="row">
                <div class="col-align-self-end">
                    <h4><b></b></h4>
                </div>

                <div class="col-sm align-self-start">
                    <input class="form-control" id="sdate" name="sdate" placeholder="Start Date" type="text"/>
                </div>

                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Search</button>

            </div>


        </form>
    </div>

    <div style="clear:both; height:10px;"></div>
    <div class="card"  style="margin-right:1%;margin-left:20px">
        <table class="table table-hover table-condensed table-striped table-sm">
            <thead style="background-color:#f1eeee">
            <tr>
                <th style="text-align: center;">SL</th>
                <th>Date </th>
                <th>Class Name </th>
                <th>Amount </th>
            </tr>
            </thead>
            <tbody>

            <?php

            $table_option = "";
            $serial_no = 1;
            foreach ($transaction_lists as $key => $value) {
                $table_option .= "<tr>";
                $table_option .= "<td style='text-align:center'>" . $serial_no++ . "</td>";
                $table_option .= "<td>$value->date</td>";
                $table_option .= "<td>$value->classname</td>";
                $table_option .= "<td>$value->amount</td>";
                $table_option .= "</tr>";
            }

            echo $table_option;

            ?>

            </tbody>
        </table>
    </div>
    <div>
        <h4 style='text-align:center'>Total amount: {{ $total_amount[0]->amount }}</h4>
    </div>
    <div class="d-flex">
        <div class="mx-auto">

        </div>
    </div>










    <aside class="control-sidebar control-sidebar-dark">

    </aside>
@endsection

@section('page_specific_script')

    <script type="text/javascript">

    </script>

@endsection
