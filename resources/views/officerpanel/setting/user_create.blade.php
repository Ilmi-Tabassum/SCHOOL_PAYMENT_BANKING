@extends('master')

@section('title','Create User')

@section('page_specific_css')
  .alert-message {
    color: red;
  }
@endsection


@section('content')

  <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Create User List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Create User</li>
            </ol>
          </div>
        </div>
      </div>
  </section>

<div style="clear:both; height:10px;"></div>
<div class="card"  style="margin-right:1%">
   <div class="card-header">

        <div class="input-group input-group-sm">
        <button type="button" class="btn btn-primary" onclick="OpenCreateUserModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
         <i class="fa fa-plus" aria-hidden="true"></i><span style="margin-left:5px">Create User</span>
        </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{ route('createUser')}}" id="" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i><span style="margin-left:5px"> View User List</span>
         </a>
        </div>
  </div>

<table class="table table-hover table-condensed table-striped table-sm">
  <thead>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>User Name</th>
      <th>Email Address</th>
      <th>Mobile Number</th>
      <th>User Type</th>
      <th colspan="2">Actions</th>
    </tr>
  </thead>

  <tbody>
     <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($users_info as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->name</td>";
          $table_option .= "<td>$value->email</td>";
          $table_option .= "<td>$value->mobile_number</td>";
          $table_option .= "<td>$value->user_type_name</td>";

          $table_option .= "<td style='text-align: center'><a href='#' class='tooltip-button editUser' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#Create' title='Edit'>
          <i class='nav-icon fas fa-edit text-success'></i></a>
          <a href='". route('userdestroy.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger' title='Delete'></i></a></td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>


  </tbody>

</table>

<div class="d-flex">
    <div class="mx-auto">
       {{$users_info->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>

  <div class="modal fade" id="Create" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="createUser_title">Create User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('storeCreateUser') }}">

              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_id" id="hidden_id" value="">

                  <div class="form-group">
                    <label for="userNameCreateUser">Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="userNameCreateUser" name="name" autocomplete="off" placeholder="e.g. Mominur Rahman" required maxlength="100" oninvalid="this.setCustomValidity('Write a User Name')" oninput="setCustomValidity('')">
                  </div>

                  <div class="form-group">
                    <label for="userEmailCreateUser">Email <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="userEmailCreateUser" name="email" autocomplete="off" placeholder="e.g. user@gmail.com" required maxlength="100" oninvalid="this.setCustomValidity('Write a Email Address')" oninput="setCustomValidity('')">
                  </div>

                  <div class="form-group">
                    <label for="mobileNumberCreateUser">Mobile Number <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="mobileNumberCreateUser" name="mobile_number" autocomplete="off" placeholder="e.g. 01XXXXXXXXX" required  oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="11" onkeyup="checkMobileNumber()">
                  </div>

                  <div class="form-group">
                      <label for="userTypeCreateUser">User Type<span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control" name="user_type_id" id="userTypeCreateUser" required oninvalid="this.setCustomValidity('Select a User Group')" oninput="setCustomValidity('')">
                            <option value="">Select User Type</option>
                            @foreach($userType as $data)
                              <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                       </div>
                  </div>

                   <div class="form-group" id="HideShowShcoolName">
                      <label for="schoolIDCreateuser">School Name</label>
                      <div>
                        <select class="form-control select2" name="school_id" id="schoolIDCreateuser">
                            <option value="">Select School Name</option>
                            @foreach($schools as $data)
                              <option value="{{$data->id}}">{{$data->school_name}}</option>
                            @endforeach
                        </select>
                       </div>
                    </div>


                  <div class="form-group">
                    <label for="createUserpassword">Password <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="createUserpassword" name="password" autocomplete="off" placeholder="e.g. ********" required maxlength="50" oninvalid="this.setCustomValidity('Write a Password')" oninput="setCustomValidity('')">
                  </div>

                </div>
               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnTxt_createUser" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
               </div>
              </form>
          </div>

        </div>
      </div>
    </div>
    </div>
  </div>



  <aside class="control-sidebar control-sidebar-dark"></aside>

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


    function OpenCreateUserModal() {
      $("#userNameCreateUser").val('');
      $("#userEmailCreateUser").val('');
      $("#mobileNumberCreateUser").val('');
      $("#userTypeCreateUser").val('');
      $("#schoolIDCreateuser").val('');
      $("#createUserpassword").val('');
      $("#hidden_id").val('');
      $('#Create').modal('show');
    }

  $('#HideShowShcoolName').hide();
   $("#userTypeCreateUser").change(function(){
      var uti = parseInt($(this).val());
      // console.log(uti);

      if (uti==2) {
        $('#HideShowShcoolName').show();
      }
       if (uti!=2) {
           $('#HideShowShcoolName').hide();
       }
       if (uti==3) {
           $('#HideShowShcoolName').show();
       }


    });


    $(".editUser").click(function(){
      document.getElementById("createUser_title").innerText = "Update User Account";
      document.getElementById("btnTxt_createUser").innerText = "Update";

      id = $(this).attr('id');
      var url = globalURL + "edit_user/" + id;
      $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            success: function(response){
                console.log(response);
                console.log(response.name);
                $("#userNameCreateUser").val(response["name"]);
                $("#userEmailCreateUser").val(response["email"]);
                $("#mobileNumberCreateUser").val(response["mobile_number"]);
                $("#userTypeCreateUser").val(response["user_type_id"]);
                $("#schoolIDCreateuser").val(response["school_id"]);
                $("#hidden_id").val(response["id"]);
            }

        });
    });


     function checkMobileNumber() {
        var mobile_number = document.getElementById("mobileNumberCreateUser").value;
        var length = mobile_number.length;
        if(length == 2){
          if(mobile_number != '01'){
            document.getElementById("mobileNumberCreateUser").value = "01";
          }
        }

        if(length == 11){
          var first_portion = mobile_number.substring(0,2);
          var remaining = mobile_number.substring(2,11);
          if(first_portion != '01'){
            document.getElementById("mobileNumberCreateUser").value = "01"+remaining;
          }
        }
      }


</script>

@endsection
