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
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Terminated School List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Terminated School List</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
<div style="clear:both; height:10px;"></div>
 <table class="table table-hover table-condensed table-striped table-sm">
  <tbody>
      <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>School Name</th>
      <th>School EIN</th>
      <th>School Mobile Number</th>
      <th>School Email Address</th>
      <th>School Division</th>
      <th>School District</th>
      <th>School Post Office</th>
     <!--  <th colspan="2">Actions</th> -->
    </tr>


       <?php

        $table_option = "";
        $serial_no = 1;
      if(count($schools) > 0){
        foreach ($schools as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->school_name</td>";
          $table_option .= "<td>$value->school_ein</td>";
          $table_option .= "<td>$value->school_mobile</td>";
          $table_option .= "<td>$value->school_email</td>";
          $table_option .= "<td>$value->division_name</td>";
          $table_option .= "<td>$value->district_name</td>";
          $table_option .= "<td>$value->post_name</td>";
          // $table_option .= "<td>
          // <a href='". route('school_info.changeApprovedToActive', $value->id) ."' class='tooltip-button confirm_school_approved_from_pending_dialog' data-original-title='Change Status to Active'><h5 title='Change status from pending to active'><span class='badge badge-info'><i class='nav-icon fas fa-exchange-alt'></i> Active</span></h5></a></td>";

          // $table_option .= "</tr>";
        }
      }else{
          $table_option .= "<tr><td colspan='8' class='text-center'>No data available in table.</td></tr>";
        }

        echo $table_option;

    ?>

      </tbody>
</table>


 <div class="d-flex">
    <div class="mx-auto">

    </div>
</div>



  <div class="modal fade" id="modal-default" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color"> Add School </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('school_info.store') }}" enctype="multipart/form-data" autocomplete="off">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_school_info_id" id="hidden_school_info_id" value="">
                  <div class="form-group">
                    <label for="school_name">School Name * </label>
                    <input type="text" class="form-control" id="school_name" placeholder="School Name" name="school_name" required>
                  </div>

                   <div class="form-group">
                    <label for="school_ein">School EIN *</label>
                    <input type="number" class="form-control" id="school_ein" placeholder="School EIN" name="school_ein" required>
                  </div>

                  <div class="form-group">
                    <label for="school_mobile">School Mobile Number</label>
                    <input type="number" class="form-control" id="school_mobile" placeholder="School Mobile Number" name="school_mobile">
                  </div>

                    <div class="form-group">
                    <label for="school_email">School Email Address</label>
                    <input type="text" class="form-control" id="school_email" placeholder="School Email Address" name="school_email">
                  </div>

                  <div class="form-group">
                    <label for="school_address">School Address * </label>
                    <input type="text" class="form-control" id="school_address" placeholder="School address" name="school_address" required>
                  </div>

                   <div class="form-group">
                    <label for="school_division">School Division</label>
                      <select class="custom-select" name="school_division" id="school_division">
                        <option selected value="0">Select School Division</option>
                        <?php
                          foreach ($divisions as $key => $value) {
                              echo "<option value='$value[id]'>$value[division_name]</option>";
                          }
                        ?>
                      </select>
                  </div>

                   <div class="form-group">
                    <label for="school_district">School District</label>
                      <select class="custom-select school_district" name="school_district" id="school_district" disabled>

                      </select>
                  </div>


                   <div class="form-group">
                    <label for="school_post">School Post</label>
                      <select class="custom-select" name="school_post" id="school_post" disabled>
                      </select>
                  </div>

                   <div class="form-group">
                    <label for="school_logo">School Logo</label>
                      <input type="text" name="hidden_school_logo" id="hidden_school_logo" value="">
                      <input type="file" class="form-control-file" id="school_logo" name="school_logo">
                      <span class="school_logo_in_edit_mode">
                        <img id="school_logo_box">
                      </span>
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
