@extends('master')

@section('title','Fees Subhead')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

  <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Fees Subhead List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Fees Subhead</li>
            </ol>
          </div>
        </div>
      </div>
  </section>

<div style="clear:both; height:10px;"></div>
<div class="card"  style="margin-right:1%">
   <div class="card-header">

        <div class="input-group input-group-sm">
        <button type="button" class="btn btn-primary" onclick="openFeesSubheadModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
         <i class="fa fa-plus" aria-hidden="true"></i><span style="margin-left:5px">Add Fees Subhead</span>
        </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{ route('subhead') }}" id="" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i><span style="margin-left:5px"> View Fees Subhead</span>
         </a>

         &nbsp;&nbsp;&nbsp;
        <a href="{{ route('subhead', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
          <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
        </a>
        </div>
  </div>

<table class="table table-hover table-condensed table-striped table-sm">
  <thead>
    <tr style="background-color:#f1eeee">
     <th style='text-align: center;width: 5%'>SL</th>
      <th style="width:25%">Fees Head Name</th>
      <th>Fees Subhead Name</th>
      <th style='text-align: center;width:10%'>Status</th>
      <th colspan="2" style='text-align: center;width:10%'>Actions</th>
    </tr>
  </thead>

  <tbody>

       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($fees_sub_head as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td style='text-align: center'>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->fees_head_name</td>";
          $table_option .= "<td>$value->fees_subhead_name</td>";
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

          $table_option .= "<td style='text-align: center'><a href='#' class='tooltip-button editSubHeadItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default' title='Delete'>
          <i class='nav-icon fas fa-edit text-success'></i></a>
          <a href='". route('subhead.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger' title='Delete'></i></a></td>";

          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

    </tbody>

</table>

<div class="d-flex">
    <div class="mx-auto">
        {{$fees_sub_head->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>


  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="subhead_title"> Add Fees Subhead</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('subhead.store') }}">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">

                  <div class="form-group">
                      <label for="feesHeadName">Fees Head Name <span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control" name="fees_head_id" id="feesHeadName" required oninvalid="this.setCustomValidity('Select a Fees Head Name')" oninput="setCustomValidity('')">
                            <option value="">Select Fees Head Name</option>
                            @foreach($fees_heads as $data)
                            <option value="{{$data->id}}">{{$data->fees_head_name}}</option>
                            @endforeach
                        </select>
                       </div>
                    </div>


                  <div class="form-group">
                    <label for="fees_subhead_name">Fees Subhead Name <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="fees_subhead_name" name="fees_subhead_name" autocomplete="off" placeholder="e.g. June Tution Fee" required oninvalid="this.setCustomValidity('Write a Fees Subhead Name')" oninput="setCustomValidity('')">
                  </div>

                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnTxt_fsh" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
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

    function openFeesSubheadModal() {
      $('#modal-default').modal('show');
      $("#feesHeadName").val('');
      $("#fees_subhead_name").val('');
      document.getElementById("subhead_title").innerText = "Add Fees Subhead";
      document.getElementById("btnTxt_fsh").innerText = "Save";
    }

     function change_status(event) {
      var id  = $(event).data("id");
      let url = globalURL+'update-status-fsh/'+id;
      $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
          if(response) {
            var active = "<span class='badge badge-success' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Active</span>"
            var inactive ="<span class='badge badge-danger' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Inactive</span>"
            var deleted = "<span class='badge badge-danger' data-id='' onclick='change_status(event.target)' style='cursor:pointer'>Deleted</span>"
            if(response.status==1){
              $("#row_"+id+" td:nth-child(4)").html(active);
            }
            if(response.status ==0){
              $("#row_"+id+" td:nth-child(4)").html(inactive);
            }
            if (response.status==2) {
              $("#row_"+id+" td:nth-child(4)").html(deleted);
            }

          }
       }
       });
    }


    $(".editSubHeadItem").click(function(){
      document.getElementById("subhead_title").innerText = "Update Fees Sub Head";
      document.getElementById("btnTxt_fsh").innerText = "Update";

      id = $(this).attr('id');
      var url = globalURL + "edit_subhead_item/" + id;
      $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            success: function(response){
              console.log(response);
                $("#feesHeadName").val(response["fees_head_id"]);
                $("#fees_subhead_name").val(response["fees_subhead_name"]);
                $("#hidden_menu_id").val(response["id"]);
            },

        });

    });



</script>

@endsection
