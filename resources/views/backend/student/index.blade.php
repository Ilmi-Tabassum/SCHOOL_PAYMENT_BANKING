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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Student list</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Student list</li>
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
         <a href="{{ route('students', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section" style="margin-right: 7px;">
      <i class="fa fa-trash" aria-hidden="true" ></i> View Trash
    </a>
            <a class="btn medium btn-success" href="{{ route('exportStudentList') }}">Exel Download</a>
        </div>
  </div>

   <div class="card-header">
       <form method="GET" action="{{ route('students.search') }}"  enctype="multipart/form-data" autocomplete="off">
        @csrf

         <div class="row">
          @if(!isset(Auth::user()->school_id))
          <div class="col-sm-3">
              <div class="form-group">
                  <select class="form-control select2" name="division_id" id="present_division_id" required="">
                      <option value="0">Select Division</option>
                      <?php
                      foreach ($divisions as $key => $value) {
                        if(request()->get('division_id') == $value["id"]){
                          echo "<option value='$value[id]' selected>$value[division_name]</option>";
                        }else{
                          echo "<option value='$value[id]'>$value[division_name]</option>";
                        }
                      }
                      ?>
                  </select>
              </div>
          </div>

          <div class="col-sm-3">
              <div class="form-group">
                  <select class="form-control select2" name="district_id" id="present_district_id" required="">
                      <option value="0">Select District</option>
                      <?php
                      if(request()->get('district_id')){
                        foreach ($districts as $key => $value) {
                         if(request()->get('district_id') == $value["id"]){
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


           <div class="col-sm-3">
               <div class="form-group">
                  <select class="form-control select2" name="school_id" id="school_id" required="">
                     <option value="0">Select School</option>
                      <?php
                      foreach ($schools as $key => $value) {
                         if(request()->get('school_id') == $value["id"]){
                            echo "<option value='$value[id]' selected>$value[school_name]</option>";
                          }else{
                            echo "<option value='$value[id]'>$value[school_name]</option>";
                          }
                      }
                      ?>
                  </select>
              </div>
          </div>
             @endif

           <div class="col-sm-3">
               <div class="form-group">
                  <select class="form-control select2" name="class_id" id="class_id" required="">
                     <option value="0">Select Class</option>
                      <?php
                      foreach ($classes as $value) {
                          echo "<option value='$value->id'>$value->name</option>";
                      }
                      ?>
                  </select>
              </div>
          </div>

             <div class="col-sm-3">
                 <div class="form-group">
                    <select class="form-control select2" name="section_id" id="section_id" required="">
                         <option value="0">Select Section</option>
                      <?php
                      foreach ($sections as $key => $value) {
                        if(request()->get('section_id') == $value["id"]){
                          echo "<option value='$value[id]' selected>$value[name]</option>";
                        }else{
                          echo "<option value='$value[id]'>$value[name]</option>";
                        }
                      }
                      ?>
                    </select>
                </div>
            </div>

           <div class="col-sm-3">
                 <div class="form-group">
                    <input type="text" name="search_by_anykey" class="form-control" placeholder="Search By Student ID, Name, Mobile Number, Father Name, Mother Name" value="<?php if(request()->get("search_by_anykey")){  echo request()->get("search_by_anykey");}else{echo ""; }  ?>">
                </div>
            </div>

             <div class="col-sm-2">
                 <div class="form-group">
                   <!--  <input type="submit" name="search" class="form-control btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22" value="Submit"> -->
                     <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22 ">
                        <i class="fa fa-search" aria-hidden="true"></i> <span style="margin-left:5px">search</span>
                    </button>
                </div>
            </div>
        </div>
      </form>
  </div>


    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
       <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </button> -->

   <!--   <a href="{{ route('students.create_page') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-plus" aria-hidden="true"></i> Add Student
    </a>

    <a href="{{ route('students') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View Students
    </a>

    <a href="{{ route('students', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> View Trash
    </a> -->


<!-- <div style="clear:both; height:10px;"></div> -->
 <table class="table table-hover table-condensed table-striped table-bordered table-sm" >
  <tbody>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <?php
        if(Auth::user()->school_id){
          //
        }else{
           echo "<th>School Name</th>";
        }
      ?>
      <th>Student's Name</th>
      <th>Student's ID</th>
      <th>Father's Name</th>
      <th>Mother's Name</th>
      <th>Mobile No</th>
      <th>Class</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>




       <?php
        $table_option = "";
        $serial_no = 1;
        foreach ($students as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";

          if(Auth::user()->school_id){
             //
          }else{
              $table_option .= "<td>$value->school_name</td>";
          }


          $table_option .= "<td>$value->name</td>";
          $table_option .= "<td>$value->student_id</td>";
          $table_option .= "<td>$value->father_name</td>";
          $table_option .= "<td>$value->mother_name</td>";
          $table_option .= "<td>$value->mobile_number</td>";
          $table_option .= "<td>$value->class_name</td>";
          $table_option .= "<td>";

          if($value->status == 2){
            $table_option .= "<span class='badge badge-danger'>Deleted</span>";
          }else{
            if($value->status == 1){
              $table_option .= "<span class='badge badge-success'>Active</span>";
            }else{
              if($value->status == 0){
                $table_option .= "<span class='badge badge-danger'>Inactive</span>";
              }
            }
          }
          $table_option .= "</td>";
            if($value->status == 2)
            {
                $table_option .= "<td>


                <a href='". route('students.edit_page', $value->id) ."' class='tooltip-button editSectionInfoItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('students.restore', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

            }else{
                $table_option .= "<td>
                <a href='". route('students.view_details', $value->id) ."' data-btn='no' data-title='View Details' data-original-title='View Details' style='padding-right: 10px' class='tooltip-button' title='View Details'>
                        <i class='nav-icon fas fa-eye text-success'></i></a>

              <a href='". route('students.edit_page', $value->id) ."' class='tooltip-button' style='padding-right: 10px' title='Edit'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('students.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete' title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a></td>";

            }
            $table_option .= "</tr>";
        }
        echo $table_option;

    ?>

      </tbody>
</table>


 <div class="d-flex">
    <div class="mx-auto">
        {{$students->links("pagination::bootstrap-4")}}
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
