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
                       <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Manage Section</h3>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                           <li class="breadcrumb-item active">Manage Section</li>
                       </ol>
                   </div>
               </div>
           </div><!-- /.container-fluid -->
       </section>
       <div style="clear:both; height:10px;"></div>

    <button type="button" class="btn btn-primary" onclick="OpenSectionModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
       <i class="fa fa-plus" aria-hidden="true"></i> Add New Section
    </button>

    <a href="{{ route('section_info') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-eye" aria-hidden="true"></i> View All Sections
    </a>

    <a href="{{ route('section_info', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
      <i class="fa fa-trash" aria-hidden="true"></i> View Trash
    </a>
       <button type="button" class="btn btn-primary CallModal" data-submit="Save"  data-title="Assign Section" data-toggle="modal" data-target="#modal-custom" data-url="{{ route('assign_section.create') }}" style="background-color:#ee1b22;border-color:#ee1b22 ">
           <i class="fa fa-plus" aria-hidden="true"></i> Assign Section
       </button>
       @if(!isset(Auth::user()->school_id))
           <a href="{{ route('assign_section') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
               <i class="fa fa-eye" aria-hidden="true"></i> View Assigned Section
           </a>
       @endif
<div style="clear:both; height:10px;"></div>
 <table class="table table-hover table-condensed table-striped table-bordered">
  <tbody>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
        @if(isset(Auth::user()->school_id))
            <th>Class Name</th>
        @endif
      <th>Section Name</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>


       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($sections as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
            if(Auth::user()->school_id)
            {
                $table_option .= "<td>$value->classname</td>";
                $table_option .= "<td>$value->secname</td>";
            }else{
                $table_option .= "<td>$value->name</td>";

            }

          $table_option .= "<td>";
          if($value->status == 2){
            $table_option .= "<span class='badge badge-danger'>Deleted</span>";
          }else{
            if($value->status == 1){
              $table_option .= "<span class='badge badge-success'>Active</span>";
            }else{
              if($value->status == 0){
                $table_option .= "<span class='badge badge-info'>Inactive</span>";
              }
            }
          }
          $table_option .= "</td>";
            if($value->status != 1)
            {

                $table_option .= "<td><a href='#editSectionInfoItem' class='tooltip-button editSectionInfoItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('section_info.restore', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

            }else{
                $table_option .= "<td><a href='#editSectionInfoItem' class='tooltip-button editSectionInfoItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('section_info.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a>
          </td>";

            }
          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

      </tbody>
</table>


{{-- <div class="d-flex">
    <div class="mx-auto">
        {{$sections->links("pagination::bootstrap-4")}}
    </div>
</div>--}}



  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="addSectionTitle"> Add Section </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('section_info.store') }}" autocomplete="off">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_section_id" id="hidden_section_id" value="">
                  <div class="form-group">
                    <label for="class_name">Section Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="section_name" placeholder="e.g. Section A" name="section_name" required="">
                  </div>
                </div>
                <!-- /.card-body -->

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="addSectionBtnTxt">Save</button>
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
<script>

    function OpenSectionModal() {
        $('#modal-default').modal('show');
  /*      $("#fees_head_name").val('');
        $("#hidden_menu_id").val('');
        document.getElementById("feeshead_title").innerText = "Add Fees Particular";
        document.getElementById("btnText_fh").innerText = "Save";
        */

    }

</script>
