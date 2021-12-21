@extends('master')

@section('title','Notice/Notification')

@section('page_specific_css')
  <!-- page css -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Notice/Notification List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Notice/Notification</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  <div class="card-header">
      <div class="input-group input-group-sm">
       <a href="{{url('/school-wise-notice')}}" class="btn medium hover-purple bg-red">
        <i class="fa fa-eye" aria-hidden="true"></i><span style="margin-left: 5px">View Notice/Notification</span>
      </a>
      </div>
  </div>

 <table class="table table-hover table-condensed table-striped table-sm" >
  <thead>
    <tr style="background-color:#f1eeee">
      <th style="width: 5%">SL</th>
      <th style="width:25%">School Name</th>
      <th style="width:10%">Class</th>
      <th style="width:20%">Title</th>
      <th>Notification</th>
    </tr>
  </thead>

  <tbody>
       @php $i=0; @endphp
       @foreach($all_notice as $notice)
        @php $i++; @endphp

        <tr>
          <td>{{$i}}</td>
          <td>{{$notice->school_name}}</td>
          <td>{{$notice->name}}</td>
          <td>{{$notice->notification_title}}</td>
          <td>

            <?php
              $messag=$notice->notification_body;
              $string = strip_tags($messag);
              if (strlen($string) >50) {
                  $stringCut = substr($string, 0, 50);
                  $endPoint = strrpos($stringCut, ' ');
                  $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                  echo $string.'......';
              }
              else {
                 echo $string;
              }
            ?>
            <input type="hidden" name="details" id="details_message_{{$notice->id}}" data-id="{{$notice->id}}" value="{{$notice->notification_body}}">
            <a href="javascript:void(0)" onclick="showDetails({{$notice->id}})">Read whole notice</a>
          </td>
        </tr>
       @endforeach
  </tbody>
</table>


 <div class="modal fade" id="DetailsNotice" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Details Notice</h4>

          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <div class="form-group">
                <textarea class="form-control" name="details" id="notice_indetails" rows="10" cols="20" readonly="">

                </textarea>
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






<div class="d-flex">
  <div class="mx-auto">
    {{$all_notice->links("pagination::bootstrap-4")}}
  </div>
</div>

</div>




  <aside class="control-sidebar control-sidebar-dark">

  </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
  function showDetails(id) {
    $('#DetailsNotice').modal('show');
    var dynamic_id = "details_message_"+id
    var details_message = document.getElementById(dynamic_id).value;
    document.getElementById("notice_indetails").innerText =details_message;
  }

</script>

@endsection
