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

                <button type="button" class="btn btn-primary CallModal" data-submit="Save"  data-title="Add New Notification" data-toggle="modal" data-target="#modal-custom" data-url="{{ route('all_notification.create') }}" style="background-color:#ee1b22;border-color:#ee1b22 ">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Notification
                </button>

                <a href="{{ route('all_notification') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                    <i class="fa fa-eye" aria-hidden="true"></i> View Notifications
                </a>

                <a href="{{ route('all_notification', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
                    <i class="fa fa-trash" aria-hidden="true"></i> View Trash
                </a>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12"><h3>Notification List</h3></div>
                </div>
                <div style="clear:both; height:10px;"></div>
                <table class="table table-hover table-condensed table-striped table-sm">
                    <tbody>
                    <tr style="background-color:#f1eeee">
                        <th>SL</th>
                        <th>Title</th>
                        <th>All School</th>
                        <th>Status</th>


                        <th colspan="3">Actions</th>
                    </tr>
                    <?php
                    $sl = 1;
                    foreach ($notices as $key => $value) {
                    ?>
                    <tr>
                        <td>{{$sl++}}</td>
                        <td>{{ $value->notification_title }}</td>
                        @if($value->for_all==1)
                            <td>Yes</td>
                        @else
                            <td>No</td>
                        @endif
                        @if($value->status==3)
                            <td><a href="{{ route('all_notification.activate', $value->id) }}"><span class='badge badge-danger'>Inactive</span></a></td>
                        @else
                            <td><a href="{{ route('all_notification.inactivate', $value->id) }}"><span class='badge badge-success'>Active</span></a></td>
                        @endif
                        @if($value->status==2)
                            <td>
                                <a href="javascript:void(0)" data-title="Notification Details" data-id="{{$value->id}}" data-toggle="modal" data-url="{{route('all_notification.details',$value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails" title="Details">
                                    <i class='nav-icon fas fa-eye text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript:;" data-id="{{$value->id}}" data-original-title="Edit Notification" data-title="Edit Notification" data-toggle="modal" data-url="{{ route('all_notification.edit', $value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails" title="Edit" >
                                    <i class='nav-icon fas fa-edit text-warning'></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('all_notification.restore', $value->id) }}" class="tooltip-button confirm_delete_dialog" title="Restore">
                                    <i class='nav-icon fas fa-window-restore text-success'></i>
                                </a>
                            </td>
                        @else
                            <td>
                                <a href="javascript:;" data-title="Notification Details" data-id="{{$value->id}}" data-toggle="modal" data-url="{{ route('all_notification.details',$value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails" title="Delete">
                                    <i class='nav-icon fas fa-eye text-success'></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript:;" data-id="{{$value->id}}" data-original-title="Edit Notification" data-title="Edit Notification" data-toggle="modal" data-url="{{ route('all_notification.edit', $value->id) }}" data-target="#modal-custom" class="tooltip-button ViewDetails">
                                    <i class='nav-icon fas fa-edit text-warning'></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('all_notification.destroy', $value->id) }}" class="tooltip-button confirm_delete_dialog">
                                    <i class='nav-icon fas fa-trash text-danger'></i>
                                </a>
                            </td>
                        @endif


                    </tr>
                    <?php } ?>

                    </tbody>

                </table>



                <!-- details notification popup  -->

               <div class="modal fade" id="modal-default" data-backdrop="static" >
                    <div class="modal-dialog">
                        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                            <div class="modal-header ab_bank_modal_background_color">
                                <h4 class="modal-title white-color"> Details </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <form method="POST" action="{{ route('class_info.store') }}" enctype="multipart/form-data" autocomplete="off">
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="class_name" style="color:red;">Title</label>
                                                    <p style="overflow:auto;">
                                                        ontrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.

                                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                                                    </p>



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


            <!-- Add notification Popup -->

            <div class="modal fade" id="modal-default-add-notification" data-backdrop="static" >
                <div class="modal-dialog">
                    <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                        <div class="modal-header ab_bank_modal_background_color">
                            <h4 class="modal-title white-color"> Add Notification </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <form method="POST" action="{{ route('class_info.store') }}" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="class_name">Type <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_type" placeholder="Notification Type" name="notification_type" required="">
                                            </div>

                                            <div class="form-group">
                                                <label for="class_name">Title <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_title" placeholder="Notification Title" name="notification_title" required="">
                                            </div>
                                            <div class="form-group">
                                                <label for="class_name">Details <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_details" placeholder="Notification Details" name="notification_details" required="">
                                            </div>
                                            <div class="form-group">
                                                <label for="class_name">Status <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_status" placeholder="Notification Status" name="notification_status" required="">
                                            </div>
                                            <div class="form-group">
                                                <label for="class_name">All School <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_all_school" placeholder="All School" name="notification_all_school" required="">
                                            </div>
                                            <div class="form-group">
                                                <label for="class_name">School Id <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="notification_school_id" placeholder="School Id" name="notification_school_id" required="">
                                            </div>




                                        </div>



                            <!-- /.card-body -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
                            </div>
                            </form>
                            <!-- /.card -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit notification Popup -->

    <div class="modal fade" id="modal-default-edit-notification" data-backdrop="static" >
        <div class="modal-dialog">
            <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
                <div class="modal-header ab_bank_modal_background_color">
                    <h4 class="modal-title white-color"> Edit Notification </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <form method="POST" action="{{ route('class_info.store') }}" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="class_name">Type </label>
                                        <input type="text" class="form-control" id="notification_type" placeholder="Notification Type" name="notification_type" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="class_name">Title </label>
                                        <input type="text" class="form-control" id="notification_title" placeholder="Notification Title" name="notification_title" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="class_name">Details </label>
                                        <input type="text" class="form-control" id="notification_details" placeholder="Notification Details" name="notification_details" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="class_name">Status </label>
                                        <input type="text" class="form-control" id="notification_status" placeholder="Notification Status" name="notification_status" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="class_name">All School </label>
                                        <input type="text" class="form-control" id="notification_all_school" placeholder="All School" name="notification_all_school" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="class_name">School Id </label>
                                        <input type="text" class="form-control" id="notification_school_id" placeholder="School Id" name="notification_school_id" required="">
                                    </div>




                                </div>



                    <!-- /.card-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
                    </div>
                    </form>
                    <!-- /.card -->
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
