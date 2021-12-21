@extends('master')

@section('title','Collections')

@section('page_specific_css')

@endsection


@section('content')

    <section class="content-header" style="margin-right: 1%;height: 50px">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px"> Settlement List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">Settlement List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div style="clear:both; height:10px;"></div>

    <div class="card"  style="margin-right:1%;">
        <div class="card-header">
            <form method="POST" action="{{route('search_settled')}}">
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
                    <div class="col-sm">
                        <div class="form-group">
                            <select class="form-control select2" name="present_division_id" id="present_division_id" required="">
                                <option value="0" selected disabled>Select Division</option>
                                <?php
                                foreach ($divisions as $key => $value) {
                                    if(request()->get('present_division_id') == $value["id"]){
                                        echo "<option value='$value[id]' selected>$value[division_name]</option>";
                                    }else{
                                        echo "<option value='$value[id]'>$value[division_name]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm">
                        <div class="form-group">
                            <select class="form-control select2" name="present_district_id" id="present_district_id" >
                                <option value="0" selected disabled>Select District</option>
                                <?php
                                if(request()->get('present_district_id')){
                                    foreach ($districts as $key => $value) {
                                        if(request()->get('present_district_id') == $value["id"]){
                                            echo "<option value='$value[id]' selected>$value[name]</option>";
                                        }else{
                                            echo "<option value='$value[id]'>$value[name]</option>";
                                        }
                                    }
                                }else{
                                    foreach ($districts as $key => $value) {
                                        echo "<option value='$value[id]'>$value[name]</option>";
                                    }
                                }

                                ?>
                            </select>
                        </div>
                    </div>



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
                <th style="width:5%">SL</th>
                <th>School Name</th>
                <th>Total Amount</th>
                <th>Service Charge </th>
                <th>Status </th>
                <th colspan="2">Actions </th>
            </tr>
            </thead>

            <tbody>
            @php $i=1; @endphp
            @if(!empty($data))
            @foreach($data as $d)

                <tr>
                    <td>{{$i}}</td>
                    <td>{{$d->school_name}}</td>
                    <td>{{$d->total_amount}}</td>
                    <td>{{$d->service_charge}}</td>
                    @if($d->status== 1)
                        <td>Paid</td>
                    @else
                        <td>Pending</td>
                    @endif
                    <td>
                        <a href='#view' class='tooltip-button ' id='" .$value->id. "' data-original-title='View' style='padding-right: 10px' onclick="settlement_popup({{$d->id}})" data-toggle='modal' data-target='#modal-default' title='Edit'>
                            <i class='nav-icon fas fa-eye text-success'></i></a>
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

    </div>


 <div class="modal fade" id="modal-default-settlement" data-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                <div class="modal-header ab_bank_modal_background_color">
                    <h4 class="modal-title white-color" id="addSectionTitle"> Settlement </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <form method="POST" action="" autocomplete="off">
                                @csrf
                                <div class="card-body">


                                    <div class="form-group">
                                        <table class="table table-hover table-condensed table-striped">
                                            <tbody>
                                            <tr>
                                                <th>Title</th>
                                                <th id="withdraw_title">Value</th>
                                            </tr>
                                            <tr>
                                                <td>School Name</td>
                                                <td id="withdraw_school_name"></td>
                                            </tr>
                                            <tr>
                                                <td>Request Method</td>
                                                <td id="withdraw_request_method">Bank Transfer</td>
                                            </tr>
                                            <tr>
                                                <td>Total Amount</td>
                                                <td id="withdraw_total_amount"><h4 style="color:#4976af;"></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Commission</td>
                                                <td id="withdraw_commision"><h4 style="color:#6f833a;"></h4></td>
                                            </tr>
                                            <tr>
                                                <td>Request Date</td>
                                                <td id="withdraw_req_date">Value</td>
                                            </tr>
                                            <tr>
                                                <td>Settlement Date</td>
                                                <td id="settle_date">Value</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>



                                </div>

                                <!-- /.card-body -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                            <!-- /.card -->
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

        function settlement_popup(id){


            $('#modal-default-settlement').modal('show');
            var url = globalURL + "settled/list/" + id;
            console.log(url);
            $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    console.log(response[0].school_name);
                    let payable_amt = response[0].total_amount - response[0].service_charge;
                    document.getElementById("withdraw_school_name").innerText=response[0].school_name;
                    document.getElementById("withdraw_total_amount").innerText=response[0].total_amount;
                    document.getElementById("withdraw_commision").innerText=response[0].service_charge;
                    document.getElementById("withdraw_req_date").innerText=response[0].req_date;
                    document.getElementById("settle_date").innerText=response[0].sett_date;



                    /*            $("#withdraw_request_method").val(response["class_id"]);
                                $("#withdraw_total_amount").val(response["id"]);
                                $("#withdraw_commision").val(response["school_id"]);
                                $("#withdraw_payable").val(response["class_id"]);
                                $("#withdraw_req_date").val(response["id"]);*/
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });

        }

    </script>

@endsection
