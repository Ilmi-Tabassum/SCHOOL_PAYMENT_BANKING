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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Student Trash Lists</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Student Trash Lists</li>
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

<!-- <div style="clear:both; height:10px;"></div> -->
  <table class="table table-hover table-bordered table-sm" >
  <tbody>
    <tr>
      <th>SL</th>
      <th>Student Name</th>
      <th>Student ID</th>
      <th>Father Name</th>
      <th>Mother Name</th>
      <th>Mobile No</th>
      <th>Class</th>
      <th>School</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>


       <?php
        $table_option = "";
        $serial_no = 1;
        foreach ($students as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->name</td>";
          $table_option .= "<td>$value->student_id</td>";
          $table_option .= "<td>$value->father_name</td>";
          $table_option .= "<td>$value->mother_name</td>";
          $table_option .= "<td>$value->mobile_number</td>";
          $table_option .= "<td>$value->class_name</td>";
          $table_option .= "<td>$value->school_name</td>";
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
                $table_option .= "<td><a href='". route('students.edit_page', $value->id) ."' class='tooltip-button' style='padding-right: 10px'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('students.restore', $value->id) ."' class='tooltip-button confirm_restore_dialog' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

            }else{
                $table_option .= "<td><a href='". route('students.edit_page', $value->id) ."' class='tooltip-button' style='padding-right: 10px'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('students.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a></td>";

            }
            $table_option .= "</tr>";
        }
        echo $table_option;

    ?>

      </tbody>
</table>

</div>

 <div class="d-flex">
    <div class="mx-auto">
        {{$students->links("pagination::bootstrap-4")}}
    </div>
</div>


  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>
   @include('common.page-script')
   @yield('custom-script')
</body>
</html>
