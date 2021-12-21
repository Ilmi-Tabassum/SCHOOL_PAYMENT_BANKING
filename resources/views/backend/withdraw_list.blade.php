<!DOCTYPE html>
<html lang="en">
@include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">
    @include('common.preloader')
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
        <div id="page-content" style="margin-top: 0px;margin-left: 20px">
    <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px"> Withdraw List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">Withdraw List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div style="clear:both; height:10px;"></div>

    <div class="card"  style="margin-right:1%">
        <div class="card-header">
            <form method="POST" action="{{route('search_withdraw')}}">
                @csrf
                <div class="row">

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



                    @if(!(Auth::user()->school_id))
                    <div class="col-3">
                        <div class="form-group">
                            <select class="form-control select2" name="schoolid" id="schoolid" required="">
                                <option selected disabled>Select School</option>
                                <?php
                                foreach ($school_info as $key => $value) {

                                    echo "<option value='$value->id'>$value->school_name</option>";

                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm">
                        <button type="submit" class="btn btn-danger">Search</button>
                    </div>
                </div>
            </form>
        </div>


        <table class="table table-hover table-condensed table-striped table-bordered">

            <thead >
            <tr style="background-color:#fff">
                <th style="width:5%;text-align: center">SL</th>
                <th>School Name</th>
                <th>Request Date</th>
                <th>Start Date </th>
                <th>End Date</th>
                <th>Total Amount </th>

                <th>Service Charge </th>
                <th>Payable </th>
                <th style="text-align: center">Actions </th>
            </tr>
            </thead>

            <tbody>
            @php $i=1; @endphp
            @if(!empty($data))
            @foreach($data as $d)

                <tr>
                    <td>{{$i}}</td>
                    <td>{{$d->school_name}}</td>
                    <td>{{$d->req_date}}</td>
                    <td>{{$d->start_date}}</td>
                    <td>{{$d->end_date}}</td>
                    <td>{{$d->total_amount}}</td>
                    <td>{{$d->service_charge}}</td>
                    <td><?php
                        $due=($d->total_amount)-($d->service_charge);
                        echo $due;
                         ?></td>
                    <td style="text-align: center">
                        <a  data-title="Withdraw" class="btn btn-primary paynow"  id="{{ $d->id }}" onclick="withdraw_popup({{ $d->id }})" style="background-color:#50692a;border-color:#435b2f ">
                            <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Pay Now</span>
                        </a>
{{--                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-custom" style="background-color:#1d675f;border-color:#2c8274 ">
                            <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left:5px">View</span>
                        </button>--}}
                        <a href="{{route('denied',$d->id)}}" type="button" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22 ">
                            <i class="fa fa-minus" aria-hidden="true"></i> <span style="margin-left:5px">Deny</span>
                        </a>
                    </td>
                </tr>

                @php $i++; @endphp
            @endforeach
            @endif
            </tbody>
        </table>

        @if(!empty($data))
        <div class="d-flex">
            <div class="mx-auto">
                {{$data->links("pagination::bootstrap-4")}}
            </div>
        </div>
        @endif
    <div class="modal fade" id="modal-default-withdraw" data-backdrop="static" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                    <div class="modal-header ab_bank_modal_background_color">
                        <h4 class="modal-title white-color" id="addSectionTitle"> Withdraw </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <form method="POST" action="{{ route('settleWithdrawReq') }}" autocomplete="off" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" name="withdrawId" id="withdrawId" value="">


                                        <div class="form-group">
                                            <h3 >Withdraw Request</h3>
                                            <table class="table table-hover table-condensed table-striped">
                                                <tbody>
                                                <tr>
                                                    <th>Title</th>
                                                    <th id="withdraw_title" name="">Value</th>
                                                </tr>
                                                <tr>
                                                    <td>School Name</td>
                                                    <td id="withdraw_school_name" name="withdraw_school_name"></td>
                                                </tr>
                                                <tr>
                                                    <td>Request Method</td>
                                                    <td id="withdraw_request_method" name="withdraw_request_method">Bank Transfer</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Amount</td>
                                                    <td id="withdraw_total_amount" name="withdraw_total_amount"><h4 style="color:#4976af;"></h4></td>
                                                </tr>
                                                <tr>
                                                    <td>Commission</td>
                                                    <td id="withdraw_commision"><h4 style="color:#6f833a;"></h4></td>
                                                </tr>
                                                <tr>
                                                    <td>Payable</td>
                                                    <td id="withdraw_payable"><h4 style="color:#e53b3b;"></h4></td>
                                                </tr>
                                                <tr>
                                                    <td>Request Date</td>
                                                    <td id="withdraw_req_date">Value</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-group">
                                            <label for="payment_for">Payment For <span class="text-danger">*</span></label>
                                            <select class="form-control" id="payment_for" name="payment_for" required="">
                                                <option value="" selected disabled>Select</option>
                                                <option value="1"  > Online Transfer</option>
                                                <option value="2"  > Bank Transfer</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="w3review">Payment Details :</label>
                                            <textarea id="w3review" name="w3review" rows="3" cols=60">
                                            </textarea>

                                        </div>
{{--                                        <div class="form-group">
                                            <label for="payment_for">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="payment_for" name="payment_for">
                                                <option value="" selected disabled>Select </option>
                                                <option value=""> Approved</option>
                                                <option value=""> Denied</option>
                                            </select>
                                        </div>--}}
                                        <div class="form-group">
                                            <label for="class_name">Upload Doc</label>
                                            <input type="hidden" name="hidden_doc" id="hidden_doc" value="">
                                            <input type="file" class="form-control-file" id="supp_doc" name="supp_doc">
                                            <span class="school_logo_in_edit_mode"> <img id="supp_doc"></span>
                                        </div>


                                    </div>

                                    <!-- /.card-body -->

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="addSectionBtnTxt">Save</button>
                                    </div>
                                </form>
                                <!-- /.card -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <aside class="control-sidebar control-sidebar-dark">

    </aside>

</div>
@include('common.page-script')
@yield('custom-script')


</body>
</html>
