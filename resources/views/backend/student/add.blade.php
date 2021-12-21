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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Add New Student</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Add New Student</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
        <div class="input-group input-group-sm">
         <a href="{{ route('students.create_page') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </a>
        &nbsp;&nbsp;&nbsp;
         <a href="{{ route('students') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View Students
    </a>

         &nbsp;&nbsp;&nbsp;
         <a href="{{ route('students', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> View Trash
    </a>
        </div>
  </div>


    <!--  <a href="{{ route('students.create_page') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </a>

    <a href="{{ route('students') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View Students
    </a>

    <a href="{{ route('students', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> View Trash
    </a> -->


<!-- <div style="clear:both; height:10px;"></div> -->

  <div class="row">
    <div class="col-md-12">
       <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" autocomplete="off">
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
                      <label for="student_id">Student ID <span style="color:red;">*</span> </label>
                      <input type="text" class="form-control" id="student_id" name="student_id"  value="<?php
                          echo $s_id;
                      ?>" readonly>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="name"> Name <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Student Full Name" required="">
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="name_bn">Bangla Name</label>
                      <input type="text" class="form-control" id="name_bn" name="name_bn" placeholder="Student Name in Bangla">
                    </div>
                  </div>


                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="mobile_number1">Mobile Number <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="mobile_number1" name="mobile_number" placeholder="e.g. 01XXXXXXXXX" required oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="checkMobileNumber()" maxlength="11">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="email_address">Email Address </label>
                      <input type="email" class="form-control" id="email_address" name="email_address" placeholder="e.g. test@shurjomukhi.com.bd">
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
                      <label for="gender"> Gender <span style="color:red;">*</span></label>
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
                      <label for="father_name">Father's Name <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Enter Father Name" required="">
                    </div>
                  </div>


                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="mother_name">Mother's Name <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Enter Mother Name" required="">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="father_nid">Father's NID</label>
                      <input type="text" class="form-control" id="father_nid" name="father_nid" placeholder="Enter Father NID">
                    </div>
                  </div>

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="mother_nid">Mother's NID</label>
                      <input type="text" class="form-control" id="mother_nid" name="mother_nid" placeholder="Enter Mother NID">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="guardian_name">Guardian's Name</label>
                      <input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="Enter Guardian Name">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="guardian_contact_no">Guardian's Contact No</label>
                      <input type="text" class="form-control" id="guardian_contact_no" name="guardian_contact_no" placeholder="Enter Guardian Contact No">
                    </div>
                  </div>

                 <div class="col-sm-4">
                    <div class="form-group">
                      <label for="relation_with_student">Relation with Student</label>
                      <input type="text" class="form-control" id="relation_with_student" name="relation_with_student" placeholder="Enter relation with student">
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="photo">Photo</label>
                     <input type="file" class="form-control-file" id="student_photo" name="student_photo" accept=".jpg, .jpeg, .png">
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
                <div class="card card mb-0">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Present Address</strong></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="present_division_id">  Division <span style="color:red;">*</span></label>
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

                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="present_district_id">  District <span style="color:red;">*</span></label>
                                    <select class="form-control select2" name="present_district_id" id="present_district_id" required="">
                                        <option value="0">Select District</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="present_post_id">  Postal code </label>
                                    <select class="form-control select2" name="present_post_id" id="present_post_id" required="">
                                        <option value="0">Select Postal code</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="present_address">  Address</label>
                                    <input type="text" class="form-control" id="present_address" name="present_address" placeholder="Enter present address">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             <div class="card card mb-0">
                 <div class="card-header">
                     <h3 class="card-title" style="padding-right:30px;"><strong> Permanent Address </strong></h3>
                     <input type="checkbox" id="sameas" name="sameas" onclick="hideShow()"><label style="padding-left:5px;color:red;">Same as present address</label>


                 </div>

                 <div class="card-body" id="sameas_div" >
                     <div class="row">
                         <div class="col-sm">
                             <div class="form-group">
                                 <label for="permanent_division_id">  Division <span style="color:red;">*</span></label>
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


                         <div class="col-sm">
                             <div class="form-group">
                                 <label for="permanent_district_id">  District <span style="color:red;">*</span></label>
                                 <select class="form-control select2" name="permanent_district_id" id="permanent_district_id" required="">
                                     <option value="0">Select District</option>
                                 </select>
                             </div>
                         </div>

                         <div class="col-sm">
                             <div class="form-group">
                                 <label for="permanent_post_id">  Postal code </label>
                                 <select class="form-control select2" name="permanent_post_id" id="permanent_post_id" required="">
                                     <option value="0">Select Post</option>
                                 </select>
                             </div>
                         </div>

                         <div class="col-sm">
                             <div class="form-group">
                                 <label for="permanent_address">  Address</label>
                                 <input type="text" class="form-control" id="permanent_address" name="permanent_address" placeholder="Enter permanent address">
                             </div>
                         </div>

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
                      <label for="std_roll">Student School ID / Roll No <span style="color:red;">*</span> </label>
                      <input type="text" class="form-control" id="std_roll" name="std_roll" placeholder="Enter student roll no" required="">
                    </div>
                  </div>
                  @if(!isset(Auth::user()->school_id))
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="school_id"> School Name <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="school_id" id="school_id" required="">
                            <option value="">Select School</option>
                            <?php
                              foreach ($schools as $value) {
                                  if(isset(Auth::user()->school_id)){
                                    if(Auth::user()->school_id == $value["id"]){
                                      echo "<option value='$value[id]' selected>$value[school_name]($value[school_ein])</option>";
                                    }else{
                                      echo "<option value='$value[id]'>$value[school_name]($value[school_ein])</option>";
                                    }
                                  }else{
                                    echo "<option value='$value[id]'>$value[school_name]($value[school_ein])</option>";
                                  }

                              }
                            ?>
                        </select>
                    </div>
                  </div>
                  @endif
                 <div class="col-sm-4">
                    <div class="form-group">
                      <label for="class_id"> Class <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="class_id" id="class_id" required="">
                            <option value="">Select Class</option>
                            <?php
                              foreach ($classes as $value) {
                                  echo "<option value='$value->id'>$value->name</option>";
                              }
                            ?>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="shift_id"> Shift <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="shift_id" id="shift_id" required="">
                            <option value="">Select Shift</option>
                            <?php
                              foreach ($shift as $key => $value) {
                                  echo "<option value='$value->id'>$value->name</option>";
                              }
                            ?>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="section_id"> Section <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="section_id" id="section_id" required="">
                            <option value="">Select Section</option>
                            <?php
                              foreach ($section as $key => $value) {
                                  echo "<option value='$value->id'>$value->name</option>";
                              }
                            ?>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="session_id"> Academic Session <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="session_id" id="session_id" required="">
                            <option value="">Select Session</option>
                            <?php
                              foreach ($session as $key => $value) {
                                  echo "<option value='$value->name'>$value->name</option>";
                              }
                            ?>
                        </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="group_id"> Group <span style="color:red;">*</span></label>
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
  <script>
          function hideShow() {
              var x = document.getElementById("sameas_div");
              if (x.style.display === "none") {
                  x.style.display = "block";
              } else {
                  x.style.display = "none";
              }


      };


      function checkMobileNumber() {
        var mobile_number = document.getElementById("mobile_number1").value;
        var length = mobile_number.length;
        if(length == 2){
          if(mobile_number != '01'){
            document.getElementById("mobile_number1").value = "01";
          }
        }

        if(length == 11){
          var first_portion = mobile_number.substring(0,2);
          var remaining = mobile_number.substring(2,11);
          if(first_portion != '01'){
            document.getElementById("mobile_number1").value = "01"+remaining;
          }
        }
      }



  </script>
</html>
