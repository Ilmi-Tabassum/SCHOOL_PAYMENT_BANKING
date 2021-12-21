@extends('master')

@section('title','Menus')

@section('page_specific_css')

@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Menu List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Menus</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
      <div class="row">
          <div class="form-group col-6" style="padding-top: 10px">
            <div class="col-md-12">
               <button type="button" class="btn btn-primary" onclick="OpenMenu()" style="background-color:#ee1b22;border-color:#ee1b22 ">
                  <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Add Menu</span>
                 </button>
                &nbsp;&nbsp;&nbsp;
                 <a href="{{route('menu')}}" class="btn medium hover-purple bg-red">
                  <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Menu</span>
                </a>

                 &nbsp;&nbsp;&nbsp;
                 <a href="{{ route('menu', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
                  <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
                 </a>
             </div>
          </div>

          <div class="form-group col-6" style="padding-top: 10px">
            <div class="col-md-12">
               <input type="text" id="menuSearch" class="form-control"  placeholder="Search Menu" >
             </div>
          </div>
      </div>



       <!--  <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Add Menu</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('menu')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Menu</span>
        </a>

         &nbsp;&nbsp;&nbsp;
         <a href="{{ route('menu', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
          <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
         </a>
        </div> -->


  </div>






 <table class="table table-hover table-condensed table-striped table-sm" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th style="text-align: center">SL</th>
      <th>Menu Name</th>
      <th>Menu Title</th>
      <th>Menu URL</th>
      <th>Menu Icon</th>
      <th>Status</th>
      <th colspan="2">Actions</th>
    </tr>
  </thead>

  <tbody id="menuTable">
       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($menus as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td style='text-align:center'>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->menu_name</td>";
          $table_option .= "<td>$value->menu_title</td>";
          $table_option .= "<td>$value->menu_url</td>";
          $table_option .= "<td>$value->menu_icon</td>";
          $table_option .= "<td>";
          if($value->status == 1){
            $table_option .= "<span class='badge badge-success'>Active</span>";
          }else{
            if($value->status == 0){
              $table_option .= "<span class='badge badge-danger'>Inactive</span>";
            }
          }
          $table_option .= "</td>";
          $table_option .= "<td><a href='#editMenu' class='tooltip-button editMenuItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default' title='Edit'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('menu.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger' title='Delete'></i></a></td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

    </tbody>
</table>

<div class="d-flex">
    <div class="mx-auto">
        {{$menus->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>

<div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="AddMenuTitle">Add Menu</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('menu.store') }}">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">
                  <div class="form-group">
                    <label for="menu_name">Menu Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="menu_name" placeholder="e.g. Student Lists" name="menu_name" required="" autocomplete="off">
                  </div>

                  <div class="form-group">
                    <label for="menu_title">Menu Title <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="menu_title" placeholder="e.g. Student Lists" name="menu_title" required="" autocomplete="off">
                  </div>

                   <div class="form-group">
                    <label for="menu_url">Menu Url</label>
                    <input type="text" class="form-control" id="menu_url" placeholder="e.g. students" name="menu_url" autocomplete="off">
                  </div>

                   <div class="form-group">
                    <label for="menu_icon">Menu Icon</label>
                    <input type="text" class="form-control" id="menu_icon" placeholder="e.g. fa fa-users" name="menu_icon" autocomplete="off">
                  </div>


                   <div class="form-group">
                      <label class="col-md-4 control-label" for="parent_menu">Parent Menu</label>
                      <div class="col-md-12">
                        <select class="form-control" name="parent_menu" id="parent_menu">
                          <option value="">Select Parent Menu</option>
                            <?php
                               $select_option = "";
                               foreach ($parent_menu as $key => $value) {
                                 $select_option .= "<option value='$value->id'>$value->menu_title</option>";
                               }
                              echo $select_option;
                            ?>
                        </select>
                       </div>
                    </div>

                </div>
               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="addMenuBtn">Save</button>
               </div>
              </form>
            <!-- /.card -->
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>




<aside class="control-sidebar control-sidebar-dark"> </aside>
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



  $(document).ready(function(){
  $("#menuSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#menuTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
     });
    });
  });

  function OpenMenu() {
    document.getElementById("AddMenuTitle").innerText = "Add Menu";
    document.getElementById("addMenuBtn").innerText = "Save";
    $("#menu_name").val('');
    $("#menu_title").val('');
    $("#menu_url").val('');
    $("#menu_icon").val('');
    $("#parent_menu").val('');
    $('#modal-default').modal('show');
  }

  // Edit menu item
  $(".editMenuItem").click(function(){
    document.getElementById("AddMenuTitle").innerText = "Update Menu";
    document.getElementById("addMenuBtn").innerText = "Update ";

    id = $(this).attr('id');
    console.log(id);

    var url = globalURL + "loading_menu_item_ajax_hit/" + id;
    $.ajax({
          url: url,
          type: "get",
          dataType: 'json',
          success: function(response){
            console.log(response);

            $("#menu_name").val(response["menu_name"]);
            $("#menu_title").val(response["menu_title"]);
            $("#menu_url").val(response["menu_url"]);
            $("#menu_icon").val(response["menu_icon"]);

            if(response["sub_id"]){
              $("#parent_menu").val(response["sub_id"]);
            }
            else{
              $("#parent_menu").val(response["id"]);
            }

            $("#hidden_menu_id").val(response["id"]);
          },
          error: function(){
              alert('We are sorry. Please try again.');
          }
      });

    });



</script>

@endsection
