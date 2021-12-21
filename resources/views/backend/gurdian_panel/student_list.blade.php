@extends('master')

@section('title','Student List')

@section('page_specific_css')
 <!-- distinct page css will be here -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Student List</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Student List</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

 <table class="table table-hover table-condensed table-striped" >
 <!--  <div class="card-header">
        <div class="input-group input-group-sm">
         <button type="button" class="btn btn-primary" onclick="AddYourStudent()" style="background-color:#ee1b22;border-color:#ee1b22 ">
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Add Student</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('studentList')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Student</span>
        </a>


        </div>
  </div> -->

  <thead >
    <tr style="background-color:#fff">
      <th>SL</th>
      <th>Student ID</th>
      <th>Student Name</th>
      <th>School Name</th>
      <th>Class</th>
      <th>Shift</th>
      <th>Section</th>
      <th>Session</th>
      <th>Group</th>
    </tr>
  </thead>

  @if($hasStudent==1)
  <tbody>
   @php $i=0; @endphp
    @foreach($students as $data)
    @php $i++; @endphp
        <tr>
         <td>{{$i}}</td>
         <td>{{$data->student_id}}</td>
         <td>{{$data->name}}</td>
         <td>{{$data->school_name}}</td>
         <td>{{$data->c_name}}</td>
         <td>{{$data->shift_name}}</td>
         <td>{{$data->section_name}}</td>
         <td>{{$data->session_id}}</td>
         <td>{{$data->group_name}}</td>
        </tr>

    @endforeach
  </tbody>
  @endif


  @if($hasStudent==0)
    <tbody>
      <tr>
        <td colspan="9" style="text-align:center;font-weight:bold;color:red">No Data Available</td>
      </tr>
    </tbody>
  @endif
  
</table>

<div class="d-flex">
    @if($hasStudent==1)
    <div class="mx-auto">
         {{$students->links("pagination::bootstrap-4")}}
    </div>
    @endif
</div>


<div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="student_title"> Add Student</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{route('storeSibling')}}">
              @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="schoolID">School Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="school_id" id="schoolID" required oninvalid="this.setCustomValidity('Select a School Name')" oninput="setCustomValidity('')">
                          <option value="">Select School Name</option>
                          @foreach($school_names as $data)
                          <option value="{{$data->id}}">{{$data->school_name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>

                   <div class="form-group">
                    <label for="classID">Class Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="class_id" id="classID" required oninvalid="this.setCustomValidity('Select a Class Name')" oninput="setCustomValidity('')">
                          <option value="">Select School Name</option>
                          @foreach($class_names as $data)
                          <option value="{{$data->id}}">{{$data->name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                  <div class="form-group">
                    <label for="studentIDSelect">Student ID <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="student_id" id="studentIDSelect" required oninvalid="this.setCustomValidity('Select a Student ID')" oninput="setCustomValidity('')">
                      </select>
                     </div>
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

<!-- OTP Confirm  Modal-->
<div class="modal fade" id="ConfirmOTP" data-backdrop="static" >
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 300px">
      <div class="modal-header ab_bank_modal_background_color">
        <h4 class="modal-title white-color" id="">Confirm OTP</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">

        <div class="col-md-12">
            <form method="POST" action="{{route('confirm_otp')}}">
            @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="otp_confirm">OTP <span style="color:red;">*</span></label>
                   <input type="text" class="form-control" id="otp_confirm" name="otp_confirm" autocomplete="off" placeholder="e.g 123456" required oninvalid="this.setCustomValidity('Enter OTP')" oninput="setCustomValidity('')">
                </div>

              </div>

             <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Confirm</button>
             </div>
            </form>
        </div>

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

 function AddYourStudent() {
    $('#modal-default').modal('show');
 }

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


    /*Fetch corresponding school student ID*/
    $("#schoolID").change(function(){
        var id = $(this).val();
          if(id !== "" ){
            var url = globalURL + "school-wise-students/"+id;
              $('#studentIDSelect').empty();
              $('#studentIDSelect').append('<option value="">Fetching Student ID...</option>');

              $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function(response){
                      if (response.hasData==1) {
                         $('#studentIDSelect').empty();
                         $('#studentIDSelect').append('<option value="">Select Student ID</option>');

                         response.studentData.forEach(row =>{
                            $('#studentIDSelect').append('<option value="'+row.id+'">'+row.real_id+'</option>');
                         });

                      }
                      else{
                        $('#studentIDSelect').empty();
                        $('#studentIDSelect').append('<option value="">No Students Available</option>');
                      }

                    },

                });
          }
    });


 $("#studentIDSelect").change(function(){
        var id = $(this).val();
        $('#ConfirmOTP').modal('show');
    });


</script>

@endsection
