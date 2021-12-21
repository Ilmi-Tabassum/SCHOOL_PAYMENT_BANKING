@extends('master')

@section('title','Income Statement')

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
                        <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Income Statement</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active">Income Statement</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div style="clear:both; height:10px;"></div>
        <form method="POST" action="{{route('income_statement.search_trxn')}}">
            @csrf
            <div class="row">

                <div class="col-sm">
                            <div class="form-group">
                                <input type="text" class="form-control textbox-n" id="start_date" name="start_date" onfocus="(this.type='date')"
                                       onblur="(this.type='text')" placeholder="Enter Starting Date">
                            </div>
                        </div>
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control textbox-n" id="end_date" name="end_date" onfocus="(this.type='date')"
                               onblur="(this.type='text')" placeholder="Enter Ending Date"  >
                    </div>
                </div>
                <div class="col-sm">
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22;margin-top: 2px;">Search</button>
                </div>

            </div>

    </form>

    </div>

    <div style="clear:both; height:10px;"></div>
    <div class="card"  style="margin-right:1%;margin-left:1% ;margin-right:40px;">
        <table class="table table-hover table-condensed table-striped table-sm">
            <thead style="background-color:#f1eeee">
            @if(!empty($transaction_lists))
            <tr>
                <th style="text-align: center;">SL</th>
                <th>Date </th>
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
                $table_option .= "<td>$value->payment_date</td>";
                $table_option .= "<td>$value->amount</td>";
                $table_option .= "</tr>";
            }

            echo $table_option;


            ?>

            </tbody>
            @else
                <span style="color: #8b0000;text-align: center;"><b> No data found</b></span>
            @endif
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


@endsection
