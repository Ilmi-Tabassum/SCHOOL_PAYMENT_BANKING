@extends('master')

@section('title','User Type')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">User Type</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">User Type</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" onclick="openUserTypeModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Add User Type</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('userType')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View User Type</span>
        </a>

         &nbsp;&nbsp;&nbsp;
         <a href="{{ route('userType', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
          <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
         </a>
        </div>
  </div>






 <table class="table table-hover table-condensed table-striped table-sm" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th style='width:5%;text-align: center'>SL</th>
      <th>User Type</th>
      <th style='width:10%;text-align: center'>Status</th>
      <th colspan="2" style='width:10%;text-align: center;'>Actions</th>
    </tr>
  </thead>

  <tbody>
       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($user_types as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td style='text-align: center'>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->name</td>";
          $table_option .= "<td style='text-align: center'>";
          if($value->status == 1){
            $table_option .= "<span class='badge badge-success' data-id='$value->id' onclick='change_status(event.target)' style='cursor:pointer'>Active</span>";
          }else{
            if($value->status == 0){
              $table_option .= "<span class='badge badge-danger' data-id='$value->id' onclick='change_status(event.target)' style='cursor:pointer'>Inactive</span>";
            }
            else if($value->status == 2){
              $table_option .= "<span class='badge badge-danger' data-id='$value->id' onclick='change_status(event.target)' style='cursor:pointer'>Deleted</span>";
            }
          }
          $table_option .= "</td>";
          $table_option .= "<td style='text-align: center'><a href='#' class='tooltip-button EditUserType' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-success'></i></a>
          <a href='". route('UserTypeDestroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-times text-danger'></i></a></td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

    </tbody>
</table>

<div class="d-flex">
    <div class="mx-auto">
        {{$user_types->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>

  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="userType_title">Add User Type</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('storeUserType') }}">
              @csrf

                <div class="card-body">

                  <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">

                  <div class="form-group">
                    <label for="usertype_name">User Type Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="usertype_name" placeholder="e.g Guardian Panel" name="name" required maxlength="100" oninvalid="this.setCustomValidity('Enter a user type name')" oninput="setCustomValidity('')">
                  </div>


                   <div class="form-group">
                      <label class="col-md-4 control-label" for="userTypeStatus">Status <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select class="form-control" name="status" id="userTypeStatus" required oninvalid="this.setCustomValidity('Please select a status name in the list')" oninput="setCustomValidity('')">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </select>
                       </div>
                    </div>

                </div>


              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="btnText">Save</button>
               </div>
              </form>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>



  <aside class="control-sidebar control-sidebar-dark">

  </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
  var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var port = window.location.port;
    var pathname = window.location.pathname;
    pathname = pathname.split("/");
    var domainName = pathname[1];

    if(port){
      var globalURL = protocol + "//" + hostname + ":" + port + "/";
    }else{
      var globalURL = protocol + "//" + hostname + "/";
    }

  function openUserTypeModal() {
     $('#modal-default').modal('show');
     $("#usertype_name").val('');
     $("#userTypeStatus").val('');
     $("#hidden_menu_id").val('');
     document.getElementById("userType_title").innerText = "Add User Type";
     document.getElementById("btnText").innerText = "Save";
  }

   $(".EditUserType").click(function(){
          document.getElementById("userType_title").innerText = "Update User Type";
          document.getElementById("btnText").innerText = "Update";
          id = $(this).attr('id');

          var url = globalURL + "edit-user-type/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#hidden_menu_id").val(response["id"]);
                    $("#usertype_name").val(response["name"]);
                    $("#userTypeStatus").val(response["status"]);
                }

            });

    });


</script>

@endsection
