@extends('master')

@section('title','Assign Class')

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
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Assign Class List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Assign Class</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

    <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Assign Class</span>
        </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('assign_class')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Class</span>
        </a>
         &nbsp;&nbsp;&nbsp;
        <a href="{{ route('assign_class', 'gen=trash') }}" class="btn medium hover-purple bg-black" style="background-color: #847070">
          <i class="fa fa-trash" aria-hidden="true"></i> <span style="margin-left: 5px">View Trash</span>
        </a>
        </div>
  </div>

 <table class="table table-hover table-condensed table-striped">
  <thead>
    <tr style="background-color:#f1eeee">
      <th style='text-align: center'>SL</th>
      <th>School Name</th>
      <th>Class Name</th>
      <th style='text-align: center'>Status</th>
      <th colspan="2" style='text-align: center'>Actions</th>
    </tr>
  </thead>

  <tbody>
   <?php
        $table_option = "";
        $serial_no = 1;
        foreach ($assignClasses as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td style='text-align: center'>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->school_name</td>";
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
            if($value->status != 1)
            {
                $table_option .= "<td><a href='#editMenu' class='tooltip-button editAssignClassItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('assign_class.restore', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Restore'><i class='nav-icon fas fa-window-restore text-success'></i></a></td>";

            }else{
                $table_option .= "<td><a href='#editMenu' class='tooltip-button editAssignClassItem' id='" .$value->id. "' data-original-title='Edit' style='padding-right: 10px' data-toggle='modal' data-target='#modal-default'>
          <i class='nav-icon fas fa-edit text-warning'></i></a>
          <a href='". route('assign_class.destroy', $value->id) ."' class='tooltip-button confirm_delete_dialog' data-original-title='Delete'><i class='nav-icon fas fa-trash text-danger'></i></a>
          </td>";

            }
          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>



  </tbody>
</table>

<div class="d-flex">
    <div class="mx-auto">
        {{$assignClasses->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>

  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Assign Class</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('assign_class.store') }}">
              @csrf

                <div class="card-body">
                    @if(!isset(Auth::user()->school_id))
                   <div class="form-group">
                     <label for="school_id">School Name<span style="color:red;">*</span></label>
                    <div class="col-md-12">
                      <select class="form-control" name="school_id" required id="school_id">
                          <option value="">Select School</option>
                           @foreach($school_info as $data)
                              <option value="{{$data->id}}">{{ $data->school_name}}</option>
                           @endforeach
                      </select>
                     </div>
                    </div>
                    @endif

                    <div class="form-group">
                      <label for="multiple-checkboxes">Class Name(s)<span style="color:red;">*</span></label>
                    <div class="col-md-12">
                      <select class="form-control" name="class_id[]" required="" id="multiple-checkboxes" multiple="multiple">
                           @foreach($class_info as $data)
                              <option value="{{$data->id}}">{{ $data->name}}</option>
                           @endforeach
                      </select>
                     </div>
                    </div>

                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
               </div>
              </form>

          </div>

        </div>
      </div>
    </div>
  </div>
  </div>


 <div class="modal fade" id="EditModal" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">

          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Update Assigned Class</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('assign_class.store') }}">
              @csrf

                <div class="card-body">
                  <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">
                   <div class="form-group">
                   <label for="school_id2">School Name<span style="color:red;">*</span></label>
                    <div class="col-md-12">
                      <select class="form-control" name="school_id" required id="school_id2">
                          <option value="">Select School Name</option>
                           @foreach($school_info as $data)
                              <option value="{{$data->id}}">{{ $data->school_name}}</option>
                           @endforeach
                      </select>
                     </div>
                    </div>

                    <div class="form-group">
                    <label for="school_id2">Class Names(s)<span style="color:red;">*</span></label>
                    <div class="col-md-12">
                      <select class="form-control" name="class_id" required id="class_id2">
                          <option value="">Select Class</option>
                           @foreach($class_info as $data)
                              <option value="{{$data->id}}">{{ $data->name}}</option>
                           @endforeach
                      </select>
                     </div>
                    </div>

                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
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

     function change_status(event) {
      var id  = $(event).data("id");
      let url = globalURL+'update_status/'+id;
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

</script>

@endsection
