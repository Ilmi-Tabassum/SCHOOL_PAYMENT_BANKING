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
                    
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
       <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </button> -->

     <a href="{{ route('students.create_page') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </a>
                
    <a href="{{ route('students') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View Students
    </a>
                
    <a href="{{ route('students', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> View Trash
    </a>

                
<div style="clear:both; height:10px;"></div>
  
  <div class="row">
    <div class="col-md-12">
       <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <!-- general form elements -->
        <div class="card card-danger">
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
                      <input type="hidden" name="hidden_student_id" value="{{ $student[0]->id }}">
                      <input type="hidden" name="hidden_std_guardian_id" value="{{ $student[0]->std_guardian_id }}">
                      <input type="hidden" name="hidden_std_academic_id" value="{{ $student[0]->std_academic_id }}">
                      <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID" required value="{{ $student[0]->student_id }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="name"> Name *</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Student Name" required="" value="{{ $student[0]->name }}">
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="name_bn">Bangla Name</label>
                      <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="Enter Student Bangla Name" value="{{ $student[0]->name_bn }}">
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="date_of_birth">Date of Birth</label>
                      <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Enter Date of Birth" value="{{ $student[0]->date_of_birth }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="blood_group"> Blood Group </label>
                       <select class="form-control select2" name="blood_group" id="blood_group" required="">
                            <option value="ab-">Select Blood Group</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="gender"> Gender </label>
                       <select class="form-control select2" name="gender" id="gender" required="">
                            <option value="male">Select Gender</option>
                        </select>
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="father_name">Father Name *</label>
                      <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Enter Father Name" required="" value="{{ $student[0]->father_name }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="father_nid">Father NID</label>
                      <input type="text" class="form-control" id="father_nid" name="father_nid" placeholder="Enter Father NID" value="{{ $student[0]->father_nid }}">
                    </div>
                  </div>


                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="mother_name">Mother Name *</label>
                      <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Enter Mother Name" required="" value="{{ $student[0]->mother_name }}">
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="mother_nid">Mother NID</label>
                      <input type="text" class="form-control" id="mother_nid" name="mother_nid" placeholder="Enter Mother NID" value="{{ $student[0]->mother_nid }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="guardian_name">Guardian Name</label>
                      <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Enter Guardian Name" value="{{ $student[0]->guardian_name }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="guardian_contact_no">Guardian Contact No</label>
                      <input type="text" class="form-control" id="guardian_contact_no" name="guardian_contact_no" placeholder="Enter Guardian Contact No" value="{{ $student[0]->guardian_contact_no }}">
                    </div>
                  </div>

                 <div class="col-sm-4">
                    <div class="form-group">
                      <label for="relation_with_student">Relation with Student</label>
                      <input type="text" class="form-control" id="relation_with_student" name="relation_with_student" placeholder="Enter relation with student" value="{{ $student[0]->relation_with_student }}">
                    </div>
                  </div>

              </div>

            </div>
            <!-- /.card-body -->
        </div>


         <div class="card card-danger">
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
                            <option value="1">Select Division</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="present_district_id"> Present District *</label>
                       <select class="form-control select2" name="present_district_id" id="present_district_id" required="">
                            <option value="1">Select District</option>
                        </select>
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="present_post_id"> Present Post *</label>
                       <select class="form-control select2" name="present_post_id" id="present_post_id" required="">
                            <option value="1">Select Post</option>
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
                            <option value="1">Select Division</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="permanent_district_id"> Permanent District *</label>
                       <select class="form-control select2" name="permanent_district_id" id="permanent_district_id" required="">
                            <option value="1">Select District</option>
                        </select>
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="permanent_post_id"> Permanent Post *</label>
                       <select class="form-control select2" name="permanent_post_id" id="permanent_post_id" required="">
                            <option value="1">Select Post</option>
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
                      <input type="text" class="form-control" id="std_roll" name="std_roll" placeholder="Enter student roll no" required="" value="{{ $student[0]->std_roll }}">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="school_id"> School *</label>
                       <select class="form-control select2" name="school_id" id="school_id" required="">
                            <option value="1">Select School</option>
                        </select>
                    </div>
                  </div>

                 <div class="col-sm-4">
                    <div class="form-group">
                      <label for="class_id"> Class *</label>
                       <select class="form-control select2" name="class_id" id="class_id" required="">
                            <option value="1">Select Class</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="shift_id"> Shift *</label>
                       <select class="form-control select2" name="shift_id" id="shift_id" required="">
                            <option value="1">Select Shift</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="section_id"> Section *</label>
                       <select class="form-control select2" name="section_id" id="section_id" required="">
                            <option value="1">Select Section</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="session_id"> Session *</label>
                       <select class="form-control select2" name="session_id" id="session_id" required="">
                            <option value="1">Select Session</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="group_id"> Group *</label>
                       <select class="form-control select2" name="group_id" id="group_id" required="">
                            <option value="1">Select Group</option>
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
   @include('common.page-script')
   @yield('custom-script')
</body>
</html>