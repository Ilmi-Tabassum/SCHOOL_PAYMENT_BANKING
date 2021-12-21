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
                <section class="content-header" style="margin-right: 1%;height: 50px">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Student's List</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Student's List</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <div style="clear:both; height:10px;"></div>
                <form method="post" action="{{route('my_student.search')}}">
                    {{csrf_field()}}

                    <div class="row mb-2">

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Student ID " name="student_id" autocomplete="off"></h4>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Student Name " name="student_name" autocomplete="off"></h4>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Guardian Name " name="guardian_name" autocomplete="off"></h4>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Guardian Number" name="guardian_number" autocomplete="off"></h4>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Guardian Email " name="guardian_email" autocomplete="off"></h4>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm">
                                <h4> <input type="text" class="form-control" placeholder="Status" name="status" autocomplete="off"></h4>

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
                                <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22 ">
                                    <i class="fa fa-search" aria-hidden="true"></i> <span style="margin-left:5px">search</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

                <div style="clear:both; height:10px;"></div>


                <div class="input-group input-group-sm">
                    <a href="{{ route('my_student.create') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add Student
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="{{ route('my_student') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                        <i class="fa fa-eye" aria-hidden="true"></i> View Students
                    </a>

                    &nbsp;&nbsp;&nbsp;
                    <a href="{{ route('my_student', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
                        <i class="fa fa-trash" aria-hidden="true"></i> View Trash
                    </a>
                </div>


                <div style="clear:both; height:10px;"></div>
                <table class="table table-hover table-condensed table-striped">
                    <thead >
                    <tr style="background-color:#adafb1">
                        <th>SL</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Student Roll</th>
                        <th>Shift Name</th>
                        <th>Section Name</th>
                        <th>Class Name</th>
                        <th>School Name</th>
                        <th>Session Name</th>
                        <th colspan="2">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(!empty($students))
                    <?php
                    $table_option = "";
                    $serial_no = 1;
                    foreach ($students as $key => $value) {
                        $table_option .= "<tr>";
                        $table_option .= "<td>" . $serial_no++ . "</td>";
                        $table_option .= "<td>$value->studentid</td>";
                        $table_option .= "<td>$value->name</td>";
                        $table_option .= "<td>$value->std_roll</td>";
                        $table_option .= "<td>$value->shname</td>";
                        $table_option .= "<td>$value->sname</td>";
                        $table_option .= "<td>$value->cname</td>";
                        $table_option .= "<td>$value->schname</td>";
                        $table_option .= "<td>$value->sename</td>";
                        if($value->status == 2)
                        {
                            $table_option .= "<td><a href='javascript:;'  data-id='" .$value->id. "' data-original-title='Details' style='padding-right: 10px' data-toggle='modal' data-url='". route('my_student.details', $value->id) ."' data-target='#modal-custom' class='tooltip-button ViewDetails' >
          <i class='nav-icon fas fa-eye text-success'></i></a>
          <a href='". route('my_student.restore', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

                        }else{
                            $table_option .= "<td><a href='javascript:;'  data-id='" .$value->id. "' data-original-title='Details' style='padding-right: 10px' data-toggle='modal' data-url='". route('my_student.details', $value->id) ."' data-target='#modal-custom' class='tooltip-button ViewDetails' >
          <i class='nav-icon fas fa-eye text-success'></i></a>
          <a href='". route('my_student.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a>
          </td>";

                        }
                    }

                    echo $table_option;

                    ?>
                    @endif


                    </tbody>
                </table>







                <aside class="control-sidebar control-sidebar-dark">

                </aside>

            </div>
@include('common.page-script')
@yield('custom-script')
</body>
</html>
