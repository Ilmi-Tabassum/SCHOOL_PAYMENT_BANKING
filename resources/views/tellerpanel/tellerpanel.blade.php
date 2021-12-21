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

            <!-- Alert part -->
            <div id="page-content" style="margin-top: 0px;margin-left: 20px;margin-right: 20px">
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

            <div id="page-content" style="margin-top: 0px;margin-left: 20px">

                <section class="content-header" style="margin-right: 1%;height: 50px">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Teller Panel</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Tellerpanel</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <div style="clear:both; height:10px;"></div>

                <form method="post" action="{{route('tellerpanel.search')}}">
                    {{csrf_field()}}

                    <div class="row mb-2">

        {{--            <div class="form-group">
                        <div class="col-sm">
                            <select class="form-control" name="school_name" id="school_name">
                                <option value="" selected disabled>Select School</option>
                                @foreach($school_info as $value)
                                    <option value="{{$value->id}}">{{$value->school_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control" name="class_name"  id="class_name">
                                    <option value="" selected disabled>Select Class</option>
                                    @foreach($classes as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control" name="shift_name"  id="shift_name">
                                    <option value="" selected disabled>Select Shift</option>
                                    @foreach($shifts as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control" name="section_name"  id="section_name">
                                    <option value="" selected disabled>Select Section</option>
                                    @foreach($sections as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control select2" name="session_name"  id="session_name">
                                    <option value="" selected disabled>Select Session</option>
                                    @foreach($sessions as $value)
                                        <option value="{{$value->name}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    <div class="form-group">
                        <div class="col-sm">
                            <h4> <input type="text" class="form-control" placeholder="Roll " name="roll" autocomplete="off"></h4>

                        </div>
                    </div>--}}

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Student ID " name="student_id_box" autocomplete="off"></h4>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="invoice ID " name="invoice_id_box" autocomplete="off"></h4>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm">
                                <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22 ">
                                    <i class="fa fa-search" aria-hidden="true"></i> <span style="margin-left:5px">search</span>
                                </button>
                            </div>
                        </div>

                   </div>

                </form>

                <div style="clear:both; height:10px;"></div>

                <table class="table table-hover table-condensed table-striped">
                    <thead>
                    <tr style="background-color:#f1eeee">
                        <th style='text-align: center'>Name</th>
                        <th>Student ID</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Shift</th>
                        <th>Section</th>
                        <th>Session</th>
                        <th>Month</th>
                        <th>Due</th>
                        <th>Pay</th>
                    </tr>
                    </thead>
                    @if(!empty($students))
                    @foreach($students as $student)
                    <tr >
                        <td style='text-align: center'>{{$student->stu_name}}</td>
                        <td>{{$student->student_id}}</td>
                        <td>{{$student->schname}}</td>
                        <td>{{$student->cname}}</td>
                        <td>{{$student->shname}}</td>
                        <td>{{$student->sname}}</td>
                        <td>{{$student->sename}}</td>
                        <td>{{$student->month}}</td>
                        <td>{{$student->due}}</td>

                        <td><a type="button" id="partial_btnt" class="btn btn-sm partial_btn" name="{{$student->invoice_no}}" data-toggle="modal" data-target="#modal-default"   style="background-color:#67aeaf;border-color:#72b3be ">
                                <span style="margin-left:5px">Pay</span></a></td>
               {{--         <td style='text-align: center'><button type="button" data-title="Fees collection " data-studentid="{{ $student_id }}" data-schoolid="{{ $school_id }}" data-classid="{{ $class_id }}" class="btn btn-primary paynow" data-toggle="modal" data-target="#modal-xl" data-url="{{ route('tellerpanel.payment') }}"  style="background-color:green;border-color:green ">
                                <i class="fa fa-money-bill" aria-hidden="true"></i> <span style="margin-left:5px">Pay</span>
                            </button></td>--}}
                    </tr>
                        @endforeach
                    @endif
                    <tbody>

                    </tbody>
                </table>




            </div>

            <div class="modal fade" id="modal-default-edit" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                        <div class="modal-header ab_bank_modal_background_color">
                            <h4 class="modal-title white-color"> <i class="fas fa-user-edit mr-2"></i>  Payment  </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <form method="POST" action="{{route('tellerpanel.payment')}}" enctype="multipart/form-data" autocomplete="off">
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
                                                    <label for="bTrxiD">Bank Trx Id :</label>
                                                    <input type="text" name="bTrxiD" id="bTrxiD" value="" required>
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
        @include('common.page-script')
        @yield('custom-script')


</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.paynow',function(){
            var title=$(this).data('title');
            var student_id=$(this).data('studentid');
            var school_id=$(this).data('schoolid');
            var class_id=$(this).data('classid');
            var post_url = $(this).data('url');
            console.log(school_id);
            console.log(class_id);

            $('.modal-title').html(title);
            $.post(post_url, {
                    school_id:school_id,
                    class_id:class_id,
                    student_id:student_id,
                },
                function(data, status) {

                    $(".modal-body").html(data);
                });
        });
    });

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
                document.getElementById("amountPaid").value=response[0].due;

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
