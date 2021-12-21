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
                       <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">School Type</h3>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                           <li class="breadcrumb-item active">School Type</li>
                       </ol>
                   </div>
               </div>
           </div>
       </section>
       <div style="clear:both; height:10px;"></div>

    <button type="button" class="btn btn-primary" onclick="OpenSchoolTypeModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
       <i class="fa fa-plus" aria-hidden="true"></i>Add School Type
    </button>

<div style="clear:both; height:10px;"></div>
 <table class="table table-hover table-condensed table-striped">
  <tbody>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>School Type</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>
       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($school_types as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->school_type</td>";
          $table_option .= "<td>";
          if($value->status == 1){
            $table_option .= "<span class='badge badge-success'>Active</span>";
          }else{
            if($value->status == 0){
              $table_option .= "<span class='badge badge-danger'>Inactive</span>";
            }
          }
          $table_option .= "</td>";
          $table_option .= "<td><a href='#' class='tooltip-button EditSchoolType' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning' title='Edit'></i></a>
          
          <a href='". route('DeleteSchoolType', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger' title='Delete'></i></a>
          </td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

      </tbody>
</table>



  <div class="modal fade" id="modal-default" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="addSchoolTypeTitle"> Add School Type </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('school_types.store') }}">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_school_type_id" id="hidden_school_type_id" value="">
                  <div class="form-group">
                    <label for="school_type">School Type <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="school_type" placeholder="e.g. Community School" name="school_type" required="" autocomplete="off">
                  </div>


                   <div class="form-group">
                      <label class="col-md-4 control-label" for="school_status">Status <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select class="form-control" name="school_status" id="school_status" required="">
                            <option value="">Select Status</option>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                       </div>
                    </div>
                </div>
                <!-- /.card-body -->

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="schooTypeBtnTxt">Save</button>
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
