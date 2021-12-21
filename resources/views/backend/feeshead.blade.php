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

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Particulars List</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Particulars</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" onclick="OpenFeesHeadModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Add Particular</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('feeshead')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Particulars</span>
        </a>

         &nbsp;&nbsp;&nbsp;
         <a href="{{ route('feeshead', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
          <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
         </a>

            <button type="button" class="btn btn-primary CallModal" data-submit="Save"  data-title="Assign particulars" data-toggle="modal" data-target="#modal-custom" data-url="{{ route('assign_particulars.create') }}" style="background-color:#ee1b22;border-color:#ee1b22 ;margin-left:5px">
                <i class="fa fa-plus" aria-hidden="true"></i> Assign particulars
            </button>
        </div>
  </div>






 <table class="table table-hover table-condensed table-striped" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th style='text-align: center;'>SL</th>
      <th>Particular Name</th>
      <th style='text-align: center;%'>Status</th>
      <th colspan="2" >Actions</th>
    </tr>
  </thead>

  <tbody>
       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($fees_heads as $key => $value) {
            //$feeshead =\App\Models\FeesHead::find($value->fees_head_id);
            $feeshead=($value->fees_head_name);

          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td style='text-align: center'>" . $serial_no++ . "</td>";
          $table_option .= "<td>$feeshead</td>";
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
            if($value->status != 1)
            {
                $table_option .= "<td style='text-align: center'><a href='#editMenu' class='tooltip-button EditfeesHead' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('feeshead.restore', $value->id) ."' class='tooltip-button ' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

            }else{
                $table_option .= "<td><a href='#editMenu' class='tooltip-button EditfeesHead' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('feeshead.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a>
          </td>";

            }

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

    </tbody>
</table>

{{--<div class="d-flex">
    <div class="mx-auto">
        {{$fees_heads->links("pagination::bootstrap-4")}}
    </div>
</div>--}}

</div>

  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="feeshead_title">Add Fees Particular</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{route('feeshead.store')}}">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">
                   <div class="form-group">
                    <label for="fees_head_name">Particular Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="fees_head_name" name="fees_head_name" autocomplete="off" placeholder="e.g. Admission Fees" required oninvalid="this.setCustomValidity('Write a Fees Particular Name')" oninput="setCustomValidity('')">
                    <span id="fees_head_nameError" class="alert-message"></span>
                  </div>
                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnText_fh" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
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

        </div>
@include('common.page-script')
@yield('custom-script')
</body>
</html>

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

    function OpenFeesHeadModal() {
       $('#modal-default').modal('show');
       $("#fees_head_name").val('');
       $("#hidden_menu_id").val('');
       document.getElementById("feeshead_title").innerText = "Add Fees Particular";
       document.getElementById("btnText_fh").innerText = "Save";
    }

     function change_status(event) {
      var id  = $(event).data("id");
      let url = globalURL+'update-status-fh/'+id;
      $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
          if(response) {
            var data_id = response.id;
            var active = "<span class='badge badge-success' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Active</span>"
            var inactive ="<span class='badge badge-danger' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Inactive</span>"
            var deleted = "<span class='badge badge-danger' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Deleted</span>"
            if(response.status==1){
              $("#row_"+id+" td:nth-child(3)").html(active);
            }
            if(response.status ==0){
              $("#row_"+id+" td:nth-child(3)").html(inactive);
            }
            if (response.status==2) {
              $("#row_"+id+" td:nth-child(3)").html(deleted);
            }

          }
       }
       });
    }



    $(".EditfeesHead").click(function(){
      document.getElementById("feeshead_title").innerText = "Update Fees Particular";
      document.getElementById("btnText_fh").innerText = "Update";
          id = $(this).attr('id');
          var url = globalURL + "edit_item/" + id;
          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    $("#fees_head_name").val(response["fees_head_name"]);
                    $("#hidden_menu_id").val(response["id"]);
                },
                error: function(){
                    alert('We are sorry. Please try again.');
                }
            });
    });

</script>
