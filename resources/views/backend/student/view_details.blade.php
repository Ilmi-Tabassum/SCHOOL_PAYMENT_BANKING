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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Student View Details</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Student View Details</li>
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

<div id="page-content" align="center">
        <!-- ENDS title -->
  <div align="center">   <br>
  <h3 style="color: #dc3545"><u>Student Details Information - <?php echo $student[0]->std_id; ?>  </u></h3>
  <?php
    echo "<a href='". route('students.edit_page', $student[0]->main_id) ."' class='tooltip-button btn medium hover-purple bg-red' style='padding-right: 10px; float: right; margin-right: 10px; margin-top: -40px'>
          <span>Edit This</span></a>";
  ?>            
  <br>

      
  <table style="width:70%">
    <tbody>
       <tr>
      <td align="right" colspan="5">  
          <?php 
              if($student[0]->photo){
                 if($student[0]->photo){
                    $filename = $student[0]->photo;
                    echo "<img src='/storage/student/$filename' width='100' style='border: solid 2px #ddd; border-radius: 10%;'>";
                  }else{
                    echo "<img src='/dist/img/image-not-available.jpg'>";
                  }
              }
          ?> 
      </td>
    </tr>

    <tr>
      <td><strong>Name</strong></td><td>:</td><td> <?php echo $student[0]->name; ?> </td><td align="right"><strong>Student ID</strong></td><td>:</td><td> <?php echo $student[0]->std_id; ?> </td>
    </tr>
    <tr>
      <td><strong>Mobile No</strong></td><td>:</td><td> <?php echo $student[0]->mobile_number; ?> </td><td align="right"><strong>Email Address</strong></td><td>:</td><td> <?php echo $student[0]->email_address; ?> </td>
    </tr>

    <tr>
      <td><strong>Date Of Birth</strong></td><td>:</td><td> <?php if($student[0]->date_of_birth){ echo date("d/m/Y", strtotime($student[0]->date_of_birth)); } ?> </td><td align="right"><strong>Blood Group</strong></td><td>:</td><td> <?php echo $student[0]->blood_group; ?> </td>
    </tr>

    <tr>
      <td><strong>Gender</strong></td><td>:</td><td> <?php echo $student[0]->gender; ?> </td>
      <td align="right"><strong>Father Name</strong></td><td>:</td><td> <?php echo $student[0]->father_name; ?> </td>
    </tr>


    <tr>
      <td><strong>Mother Name</strong></td><td>:</td><td> <?php echo $student[0]->mother_name; ?> </td><td align="right"><strong>Father NID</strong></td><td>:</td><td> <?php echo $student[0]->father_nid; ?> </td>
    </tr>

     <tr>
      <td><strong>Mother NID</strong></td><td>:</td><td> <?php echo $student[0]->mother_nid; ?> </td><td align="right"><strong>Guardian Name</strong></td><td>:</td><td> <?php echo $student[0]->guardian_name; ?> </td>
    </tr>

    <tr>
      <td><strong>Guardian Contact No</strong></td><td>:</td><td> <?php echo $student[0]->guardian_contact_no; ?> </td><td align="right"><strong>Relation with Student</strong></td><td>:</td><td> <?php echo $student[0]->relation_with_student; ?> </td>
    </tr>

    <tr>
      <td colspan="6"><hr></td>
    </tr>

    <tr>
      <td colspan="6" align="center"> <strong>Student Contact Information</strong> </td>
    </tr>


    <tr>
        <td align="left"><strong>Present Address</strong></td><td>:</td><td> <?php echo $student[0]->present_address; ?> </td>
    </tr> 

    <tr>
      <td><strong>Present Division</strong></td><td>:</td>
        <td> 
             <?php 
                if($student[0]->present_division_id > 0){
                    $division_id = $student[0]->present_division_id;
                    foreach($divisions as $key => $v) {
                         if ($division_id == $v->id) {
                             echo $v->division_name;
                            break;
                        }
                    } 
                }

                ?>
         </td>
        <td align="right"><strong>Present District</strong></td><td>:</td>

        <td>
          <?php 
            if($student[0]->present_district_id > 0){
                $district_id = $student[0]->present_district_id;
                foreach($districts as $key => $value) {
                     if ($district_id == $value->id) {
                         echo $value->name;
                        break;
                    }
                } 
            }

          ?>
         </td>
</tr>    
<tr>
  <td align="left"><strong>Present Post Office</strong></td><td>:</td>
      <td>
        <?php 
          if($student[0]->present_post_id > 0){
              $post_id = $student[0]->present_post_id;
              foreach($school_posts as $key => $value) {
                   if ($post_id == $value->id) {
                       echo $value->name;
                      break;
                  }
              } 
          }

        ?>
       </td>
</tr> 
 

    <tr>
        <td align="left"><strong>Permanent Address</strong></td><td>:</td><td> <?php echo $student[0]->permanent_address; ?> </td>
    </tr> 

    <tr>
      <td><strong>permanent Division</strong></td><td>:</td>
        <td> 
             <?php 
                if($student[0]->permanent_division_id > 0){
                    $division_id = $student[0]->permanent_division_id;
                    foreach($divisions as $key => $v) {
                         if ($division_id == $v->id) {
                             echo $v->division_name;
                            break;
                        }
                    } 
                }

                ?>
         </td>
        <td align="right"><strong>Permanent District</strong></td><td>:</td>

        <td>
          <?php 
            if($student[0]->permanent_district_id > 0){
                $district_id = $student[0]->permanent_district_id;
                foreach($permanent_districts as $key => $value) {
                     if ($district_id == $value->id) {
                         echo $value->name;
                        break;
                    }
                } 
            }

          ?>
         </td>
</tr>    
<tr>
  <td align="left"><strong>Permanent Post Office</strong></td><td>:</td>
      <td>
        <?php 
          if($student[0]->permanent_post_id > 0){
              $post_id = $student[0]->permanent_post_id;
              foreach($permanent_school_posts as $key => $value) {
                   if ($post_id == $value->id) {
                       echo $value->name;
                      break;
                  }
              } 
          }

        ?>
       </td>
</tr> 

  <tr>
    <td colspan="6"><hr></td>
  </tr>

  <tr>
      <td colspan="6" align="center"> <strong>Student Academic Information</strong> </td>
  </tr>


    <tr>
        <td align="left"><strong>Roll No</strong></td><td>:</td><td> <?php echo $student[0]->std_roll; ?> </td>
        <td align="right"><strong>School Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->school_id > 0){
                  $school_id = $student[0]->school_id;
                  foreach($schools as $key => $value) {
                       if ($school_id == $value->id) {
                           echo $value->school_name;
                          break;
                      }
                  } 
              }

            ?>
       </td>
    </tr> 

    <tr>
        <td align="left"><strong>Class Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->class_id > 0){
                  $class_id = $student[0]->class_id;
                  foreach($classes as $key => $value) {
                       if ($class_id == $value->id) {
                           echo $value->name;
                          break;
                      }
                  } 
              }

            ?>
       </td>
      <td align="right"><strong>Shift Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->shift_id > 0){
                  $shift_id = $student[0]->shift_id;
                  foreach($shift as $key => $value) {
                       if ($shift_id == $value->id) {
                           echo $value->name;
                          break;
                      }
                  } 
              }

            ?>
       </td>
    </tr> 

    <tr>
      <td align="left"><strong>Section Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->section_id > 0){
                  $section_id = $student[0]->section_id;
                  foreach($section as $key => $value) {
                       if ($section_id == $value->id) {
                           echo $value->name;
                          break;
                      }
                  } 
              }

            ?>
       </td>

       <td align="right"><strong>Session Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->session_id > 0){
                  $session_id = $student[0]->session_id;
                  foreach($session as $key => $value) {
                       if ($session_id == $value->id) {
                           echo $value->name;
                          break;
                      }
                  } 
              }

            ?>
       </td>
    </tr>

    <tr>
      <td align="left"><strong>Group Name</strong></td><td>:</td>
         <td>
            <?php 
              if($student[0]->group_id > 0){
                  $group_id = $student[0]->group_id;
                  foreach($group as $key => $value) {
                       if ($group_id == $value->id) {
                           echo $value->name;
                          break;
                      }
                  } 
              }

            ?>
       </td>
    </tr>

  
        

    </tbody></table>
    
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
