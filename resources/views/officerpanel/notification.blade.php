@extends('master')

@section('title','Notification')

@section('page_specific_css')

@endsection
<!--  -->

@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Notification List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Notification</li>
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
        <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Create Notification</span>
       </button>
      &nbsp;&nbsp;&nbsp;
       <a href="{{route('notificationIndex')}}" class="btn medium hover-purple bg-red">
        <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Notification</span>
      </a>
      </div>
    </div>


  <table class="table table-hover table-condensed table-striped table-sm">
    <thead >
      <tr style="background-color:#f1eeee">
        <th style="width:5%">SL</th>
        <th style="width:35%">Title</th>
          @if(!isset(auth::user()->school_id))
        <th style="width:10% ">All</th>
          @endif
        <th colspan="2" style="width: 10%;text-align: center">Actions</th>
      </tr>
    </thead >

    <tbody>
        <?php
          $i = 1;
          foreach ($notificationIndex as $key => $value) {
        ?>

        <tr>
            <td>{{$i++}}</td>
            <td>{{$value->notification_title}}</td>
            @if(!isset(auth::user()->school_id))

            @if($value->for_all==1)
                <td>Yes</td>
            @else
                <td>No</td>
            @endif
            @endif

            <td style="text-align: center">
                <a href="javascript:void(0)" data-id="{{$value->id}}" data-toggle="modal" id="ViewDetails" onclick="ViewDetails({{$value->id}})" class="tooltip-button">
                  <i class='nav-icon fas fa-eye text-success'></i>
                </a>

                 &nbsp;&nbsp;&nbsp;&nbsp;

                <a href="{{route('deleteNotification',$value->id) }}" class="tooltip-button confirm_delete_dialog">
                    <i class='nav-icon fas fa-trash text-danger'></i>
                </a>
            </td>



        </tr>

        <?php } ?>

    </tbody>
  </table>


<div class="d-flex">
    <div class="mx-auto">
        {{$notificationIndex->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>

<div class="modal fade" id="modal-default" data-backdrop="static" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
      <div class="modal-header ab_bank_modal_background_color">
        <h4 class="modal-title white-color" id="feeshead_title">Create Notification</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">

          <div class="col-md-12">
            <form method="POST" action="{{route('storeNotification')}}">
            @csrf
              <div class="card-body">
                <input type="hidden" name="hidden_menu_id" id="hidden_menu_id" value="">

                  <div class="form-group">
                    <label for="notification_title">Notification Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="notification_title" placeholder="e.g. Urgent Meeting at 2:00 PM" value="" name="notification_title" required="" autocomplete="off" maxlength="255">
                  </div>
                  @if(!isset(auth::user()->school_id))
                  <div class="form-group">
                    <label for="notification_for">Notification For <span class="text-danger">*</span></label>
                    <select class="form-control" id="notification_for" name="notification_for" required="">
                        <option value="">Select Notification For</option>
                        <option value="All">For all school</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" >{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                   </div>
                  @endif
                    <div class="form-group">
                    <label for="class_id">Select Class</label>
                    <select class="form-control" id="class_id" name="class_id">
                        <option value="">Select Class</option>
                        @foreach($classes as $data)
                            <option value="{{ $data->id }}" >{{ $data->name }}</option>
                        @endforeach
                    </select>
                   </div>


                  <div class="form-group">
                  <label for="notification_body">Notification Body <span class="text-danger">*</span></label>
                  <textarea class="form-control" rows="8" id="notification_body" name="notification_body" required=""></textarea>
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



  <div class="modal fade" id="Details" data-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
        <div class="modal-header ab_bank_modal_background_color">
          <h4 class="modal-title white-color">Details Notification</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card-body">

                <div class="form-group">
                  <label for="notification_title">Notification Title</label>
                  <p id="notifi_title"></p>
                </div>

                <div class="form-group">
                  <label for="notification_for">Notification For</label>
                   <p id="notifi_for"></p>
                 </div>

                <div class="form-group">
                  <label for="notification_body">Notification Body</label>
                  <textarea class="form-control" rows="10" id="notifi_body" readonly=""></textarea>
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>

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

     function ViewDetails(id) {
     $('#Details').modal('show');
      let url = globalURL+'details-notification/'+id;
      $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
          if(response) {
            document.getElementById("notifi_title").innerText = response[0].notification_title;
            document.getElementById("notifi_for").innerText = response[0].school_name;
            document.getElementById("notifi_body").innerText = response[0].notification_body;
          }
       }
       });
    }

</script>

@endsection
