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

            <div id="page-content" style="margin-top: 20px;margin-left: 20px">

                <button type="button" class="btn btn-primary CallModal" data-submit="Save"  data-title="Add New Payment" data-toggle="modal" data-target="#modal-custom" data-url="{{ route('payment_setup.create') }}" style="background-color:#ee1b22;border-color:#ee1b22 ">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New Payment
                </button>

                <a href="{{ route('payment_setup') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                    <i class="fa fa-eye" aria-hidden="true"></i> View Payment
                </a>

                <a href="{{ route('payment_setup', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
                    <i class="fa fa-trash" aria-hidden="true"></i> View Trash
                </a>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12"><h3>Payment List</h3></div>
                </div>
                <div style="clear:both; height:10px;"></div>
                <table class="table table-hover table-bordered table-condensed table-striped">
                    <tbody>
                    <tr>
                        <th>SL</th>
                        <th >User Name</th>
                        <th>School</th>
                        <th>URL</th>
                        <th>Unique code</th>


                        <th colspan="3">Actions</th>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($payments as $key => $value) {
                    ?>
                    <tr>
                        <td>{{$sl++}}</td>
                        <td>{{ $value->payment_user_name }}</td>
                        <td>{{ $value->school }}</td>
                        <td>{{ $value->payment_url }}</td>
                        <td>{{ $value->payment_unique_code	 }}</td>
                        @if($value->status==2)
                            <td>
                                <a href="javascript:;" data-title="Payment Details" data-id="{{$value->id}}" data-toggle="modal" data-url="{{ route('payment_setup.details',$value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                                    <i class='nav-icon fas fa-eye text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript:;" data-id="{{$value->id}}" data-original-title="Edit Notice" data-toggle="modal" data-url="{{ route('payment_setup.edit', $value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                                    <i class='nav-icon fas fa-edit text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('payment_setup.restore', $value->id) }}" class="tooltip-button confirm_delete_dialog">
                                    <i class='nav-icon fas fa-window-restore text-success'></i>
                                </a>
                            </td>
                        @else
                            <td>
                                <a href="javascript:;" data-title="Payment Details" data-id="{{$value->id}}" data-toggle="modal" data-url="{{ route('payment_setup.details',$value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                                    <i class='nav-icon fas fa-eye text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript:;" data-id="{{$value->id}}" data-original-title="Edit Notice" data-toggle="modal" data-url="{{ route('payment_setup.edit', $value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                                    <i class='nav-icon fas fa-edit text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('payment_setup.destroy', $value->id) }}" class="tooltip-button confirm_delete_dialog">
                                    <i class='nav-icon fas fa-trash text-danger'></i>
                                </a>
                            </td>
                        @endif


                    </tr>
                    <?php } ?>

                    </tbody>

                </table>





                <aside class="control-sidebar control-sidebar-dark">

                </aside>

            </div>
@include('common.page-script')
@yield('custom-script')
</body>
</html>
