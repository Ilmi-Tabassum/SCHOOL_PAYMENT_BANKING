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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Upload Bulk Student</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Student Batch Upload</li>
              </ol>
            </div>
          </div>
        </div>
      </section>


<div style="clear:both; height:10px;"></div>



  <div class="row">
    <div class="col-md-12">
       <form method="POST" action="{{ route('students.upload_student') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <!-- general form elements -->
        <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">Student Batch Upload</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
              <div class="row">
                    <div class="col-sm-4">
                    <div class="form-group">
                      <label for="school_id"> School Name <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="school_id" id="school_id" required="">
                            <option value="">Select School</option>
                            <?php
                              foreach ($schools as $key => $value) {
                                  echo "<option value='$value[id]'>$value[school_name]($value[school_ein])</option>";
                              }
                            ?>
                        </select>
                    </div>
                  </div>

                 <div class="col-sm-4">
                    <div class="form-group">
                      <label for="class_id"> Class <span style="color:red;">*</span></label>
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
                      <label for="shift_id"> Shift <span style="color:red;">*</span></label>
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
                      <label for="section_id"> Section <span style="color:red;">*</span></label>
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
                      <label for="session_id">Academic Session <span style="color:red;">*</span></label>
                       <select class="form-control select2" name="session_id" id="session_id" required="">
                            <option value="">Select Session</option>
                            <?php
                              foreach ($session as $key => $value) {
                                  echo "<option value='$value[name]'>$value[name]</option>";
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

                   <div class="col-sm-4">
                    <div class="form-group">
                      <label for="student_batch_file"> Select Formatted Excel File <span style="color:red;">*</span></label>
                       <input type="file" name="student_batch_file" class="form-control" accept=".xls" required="">
                    </div>
                  </div>

              </div>

            </div>
            <!-- /.card-body -->
        </div>

         <div class="card-footer">
              <button type="submit" class="btn btn-danger">Upload</button>
            </div>
          </form>

  </div>

</div>
   @include('common.page-script')
   @yield('custom-script')
</body>
</html> 