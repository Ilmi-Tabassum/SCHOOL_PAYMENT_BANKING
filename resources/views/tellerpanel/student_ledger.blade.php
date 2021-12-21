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
                                <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Student ledger</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Student ledger</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <div style="clear:both; height:10px;"></div>

                <form method="post" action="{{route('student_ledger.search')}}">
                    {{csrf_field()}}

                    <div class="row mb-2">
                        @if(!isset(Auth::user()->school_id))
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control select2" name="school_name" id="school_name">
                                    <option value="" selected disabled>Select School</option>
                                    @foreach($school_info as $value)
                                        <option value="{{$value->id}}">{{$value->school_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control select2" name="class_name"  id="class_name">
                                    <option value="" selected disabled>Select Class</option>
                                    @foreach($classes as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control select2" name="shift_name"  id="shift_name">
                                    <option value="" selected disabled>Select Shift</option>
                                    @foreach($shifts as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <select class="form-control select2" name="section_name"  id="section_name">
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
                        </div>

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Student ID " name="student_id_box" autocomplete="off"></h4>

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
                @if(!empty($students))
                    @foreach ($students as $key => $value)

                <table class="table table-hover table-condensed table-striped">
                    <thead>
                    <tr style="background-color:#f1eeee">

                        <td style='text-align: left'><b> Name:</b> {{$value->student_name}}</td>
                        <td style='text-align: left'><b> ID:</b> {{$value->id}} </td>
                    </tr>
                    <tr>
                        <table class="table table-hover table-condensed table-striped table-bordered">
                            <thead>
                            <th colspan="3" style='text-align: center'>Paid</th>
                            <th colspan="3" style='text-align: center'>Due</th>
                            </thead>
                            <tbody>
                            <tr>
                            <th style='text-align: center'>Date</th>
                            <th style='text-align: center'>Invoice</th>
                            <th style='text-align: center'>Amount</th>
                                <th style='text-align: center'>Date</th>
                                <th style='text-align: center'>Invoice</th>
                            <th style='text-align: center'>Amount</th>
                            </tr>




                                @for($i=0;$i<sizeof($paid)||$i<sizeof($unpaid);$i=$i+1)
                                    <tr>
                                    @if(isset($paid[$i]))
                                        <td style='text-align: center'>{{$paid[$i]->payment_date}}</td>
                                        <td style='text-align: center'>{{$paid[$i]->invoice_no}}</td>
                                        <td style='text-align: center'>{{$paid[$i]->total_amount}}</td>
                                    @else
                                    <td style='text-align: center'></td>
                                    <td style='text-align: center'></td>
                                    <td style='text-align: center'></td>
                                    @endif
                                    @if(isset($unpaid[$i]))
                                            <td style='text-align: center'>{{$unpaid[$i]->created_at}}</td>
                                            <td style='text-align: center'>{{$unpaid[$i]->invoice_no}}</td>
                                            <td style='text-align: center'>{{$unpaid[$i]->total_amount}}</td>

                                    @else
                                            <td style='text-align: center'></td>
                                            <td style='text-align: center'></td>
                                            <td style='text-align: center'></td>

                                    @endif
                                    </tr>
                                @endfor




                            <tr >
                                @if(empty($paid_total))
                                    <th colspan="3" style='text-align: center'>Total:0 </th>
                                @else
                                    <th colspan="3" style='text-align: center'>Total:{{$paid_total[0]->grand_total}} </th>

                                @endif
                                 @if(empty($unpaid_total))
                                        <th colspan="3" style='text-align: center'>Total:0 </th>
                                @else
                                        <th colspan="3" style='text-align: center'>Total:{{$unpaid_total[0]->grand_utotal}} </th>
                                @endif

                            </tr>
                            @break
                            @endforeach
                                    @endif

                            </tbody>
                        </table>

                    </tr>
                    </thead>



                    <tbody>

                    </tbody>
                </table>




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
</script>
