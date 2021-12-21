@extends('master')

@section('title','Payment Online')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Payment Online </h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Payment Online</li>
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
          <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:5px">Payment Online</span>
         </button>
        &nbsp;&nbsp;&nbsp;
         <a href="{{route('agent')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Payment Online</span>
        </a>
        </div>
  </div>

<table class="table table-hover table-condensed table-striped table-sm" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>Student ID</th>
      <th>School Name</th>
      <th>Amount</th>
      <th>Trxn ID</th>
      <th>Status</th>
      <th>Method</th>
      <th>Date</th>
    </tr>
  </thead>

  @if($hasData==1)
  <tbody>
    
    @php $i=0; @endphp
    @foreach($transactions as $value)
    @php $i++; @endphp
      <tr>
        <td>{{$i}}</td>
        <td>{{$value->s_id}}</td>
        <td>
         <?php 
          $school_name = DB::select(DB::raw("SELECT student_academics.school_id,school_infos.school_name FROM student_academics
                        INNER JOIN school_infos
                        ON student_academics.student_id = school_infos.id 
                        WHERE student_academics.student_id=$value->student_id"));
          if (count($school_name)>0) {
            echo $school_name[0]->school_name;
          }
          else{
            echo " ";
          }

         ?>
        </td>
        <td>{{$value->amount}}</td>
        <td>{{$value->trx_id}}</td>
        <td>{{$value->status}}</td>
        <td>{{$value->method}}</td>
        <td>{{$value->created_at}}</td>
      </tr>
    @endforeach
   
  </tbody>
  @endif


  @if($hasData==0)
  <tbody>
    <tr>
      <td colspan="8" style="text-align: center;font-weight: bold">No data available</td>
    </tr>
  </tbody>
  @endif
</table>


<div class="d-flex">
  @if($hasData==1)
    <div class="mx-auto">
         {{$transactions->links("pagination::bootstrap-4")}}
    </div>
  @endif
</div>


</div>

  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="feeshead_title">Payment Online</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('FeesHeadWisePayment') }}">
              @csrf
                <div class="card-body">

                  <div class="form-group">
                    <label for="school_id">School Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="school_id" id="school_id" required oninvalid="this.setCustomValidity('Select a school name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select school name</option>
                          @foreach($schools as $data)
                            <option value="{{$data->id}}">{{$data->school_name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>

                   <div class="form-group">
                    <label for="class_name">Class Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="class_name" id="class_name" required oninvalid="this.setCustomValidity('Select a class name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select class name</option>
                          @foreach($class_names as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                   <div class="form-group">
                    <label for="session_name">Session <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="session_name" id="session_name" required oninvalid="this.setCustomValidity('Select a session name in the list')" oninput="setCustomValidity('')">
                          <option value="">Select session name</option>
                          @foreach($sessions as $data)
                            <option value="{{$data->name}}">{{$data->name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>

                  <div class="form-group">
                    <label for="student_id">Student ID<span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                          <option value="">Select Student ID</option>
                      </select>
                     </div>
                  </div>

                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Next</button>
               </div>
              </form>
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

    $("#school_id").change(function(){
      var school_id = document.getElementById("school_id").value;
      if(school_id !== ""){
        var url = globalURL + "get-school-wise-student/"+school_id;

          $('#student_id').empty();
          $('#student_id').append('<option value="">Fetching Student ID...</option>');

          $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                  if (response.status==1) {
                     $('#student_id').empty();
                     $('#student_id').append('<option value="">Select Student ID</option>');

                     response.students.forEach(row =>{
                        $('#student_id').append('<option value="'+row.id+'">'+row.student_id+'</option>');
                     });
                   }
                   else{
                     $('#student_id').empty();
                     $('#student_id').append('<option value="">No Student ID Available</option>');
                   }
                },

            });
      }

    });


</script>

@endsection
