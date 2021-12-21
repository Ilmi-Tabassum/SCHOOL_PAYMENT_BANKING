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
                                <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">My Students</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                    <li class="breadcrumb-item active">My Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="card"  style="margin-right:1%">
                    <div class="card-header">
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
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <form method="POST" action="{{ route('my_student.store') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!-- general form elements -->
                                <div class="card card-danger mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">Student Personal Information</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="student_id">Student ID * </label>
                                                    <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="name"> Name *</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Student Name" required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="name_bn">Bangla Name</label>
                                                    <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="Enter Student Bangla Name">
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="mobile_number">Mobile Number *</label>
                                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile Number" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="email_address">Email Address </label>
                                                    <input type="text" class="form-control" id="email_address" name="email_address" placeholder="Enter Email Address">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="date_of_birth">Date of Birth</label>
                                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Enter Date of Birth"  readonly='true'>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="blood_group"> Blood Group </label>
                                                    <select class="form-control select2" name="blood_group" id="blood_group">
                                                        <option value="">Select Blood Group</option>
                                                        <?php
                                                        foreach ($blood_groups as $key => $value) {
                                                            echo "<option value='$value'>$value</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="gender"> Gender *</label>
                                                    <select class="form-control select2" name="gender" id="gender" required="">
                                                        <option value="">Select Gender</option>
                                                        <?php
                                                        foreach ($gender as $key => $value) {
                                                            echo "<option value='$value'>$value</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="father_name">Father Name *</label>
                                                    <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Enter Father Name" required="">
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="mother_name">Mother Name *</label>
                                                    <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Enter Mother Name" required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="father_nid">Father NID</label>
                                                    <input type="text" class="form-control" id="father_nid" name="father_nid" placeholder="Enter Father NID">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="mother_nid">Mother NID</label>
                                                    <input type="text" class="form-control" id="mother_nid" name="mother_nid" placeholder="Enter Mother NID">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="guardian_name">Guardian Name</label>
                                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Enter Guardian Name">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="guardian_contact_no">Guardian Contact No</label>
                                                    <input type="text" class="form-control" id="guardian_contact_no" name="guardian_contact_no" placeholder="Enter Guardian Contact No">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="relation_with_student">Relation with Student</label>
                                                    <input type="text" class="form-control" id="relation_with_student" name="relation_with_student" placeholder="Enter relation with student">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>


                                <div class="card card-danger mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">Student Contact Information</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="present_division_id"> Present Division *</label>
                                                    <select class="form-control select2" name="present_division_id" id="present_division_id" required="">
                                                        <option value="0">Select Division</option>
                                                        <?php
                                                        foreach ($divisions as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[division_name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="present_district_id"> Present District *</label>
                                                    <select class="form-control select2" name="present_district_id" id="present_district_id" required="">
                                                        <option value="0">Select District</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="present_post_id"> Present Post *</label>
                                                    <select class="form-control select2" name="present_post_id" id="present_post_id" required="">
                                                        <option value="0">Select Post</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="present_address"> Present Address</label>
                                                    <input type="text" class="form-control" id="present_address" name="present_address" placeholder="Enter present address">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="permanent_division_id"> Permanent Division *</label>
                                                    <select class="form-control select2" name="permanent_division_id" id="permanent_division_id" required="">
                                                        <option value="0">Select Division</option>
                                                        <?php
                                                        foreach ($divisions as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[division_name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="permanent_district_id"> Permanent District *</label>
                                                    <select class="form-control select2" name="permanent_district_id" id="permanent_district_id" required="">
                                                        <option value="0">Select District</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="permanent_post_id"> Permanent Post *</label>
                                                    <select class="form-control select2" name="permanent_post_id" id="permanent_post_id" required="">
                                                        <option value="0">Select Post</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="permanent_address"> Permanent Address</label>
                                                    <input type="text" class="form-control" id="permanent_address" name="permanent_address" placeholder="Enter permanent address">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>


                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title"> Student Academic Information</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->

                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="std_roll">Student Roll No * </label>
                                                    <input type="text" class="form-control" id="std_roll" name="std_roll" placeholder="Enter student roll no" required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="group_id"> Group *</label>
                                                    <select class="form-control select2" name="group_id" id="group_id" required="">
                                                        <option value="">Select Group</option>
                                                        <?php
                                                        foreach ($group as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="class_id"> Class *</label>
                                                    <select class="form-control select2" name="class_id" id="class_id" required="">
                                                        <option value="">Select Class</option>
                                                        <?php
                                                        foreach ($classes as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="shift_id"> Shift *</label>
                                                    <select class="form-control select2" name="shift_id" id="shift_id" required="">
                                                        <option value="">Select Shift</option>
                                                        <?php
                                                        foreach ($shift as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="section_id"> Section *</label>
                                                    <select class="form-control select2" name="section_id" id="section_id" required="">
                                                        <option value="">Select Section</option>
                                                        <?php
                                                        foreach ($section as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="session_id"> Session *</label>
                                                    <select class="form-control select2" name="session_id" id="session_id" required="">
                                                        <option value="">Select Session</option>
                                                        <?php
                                                        foreach ($session as $key => $value) {
                                                            echo "<option value='$value[id]'>$value[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
@include('common.page-script')
@yield('custom-script')
</body>
</html>
