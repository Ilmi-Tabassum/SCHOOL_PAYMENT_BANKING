@extends('master')

@section('title','Student Ledger')

@section('page_specific_css')

@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Student Ledger</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Student Ledger</li>
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
          <i class="fa fa-search" aria-hidden="true"></i> <span style="margin-left:5px">Student Ledger</span>
         </button>
        </div>
  </div>

<table class="table table-hover table-condensed table-striped table-sm" >
    <thead >
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>Student ID</th>
      <th>Fees Head</th>
      <th>Amount</th>
      <th>Date</th>
    </tr>

  </thead>

  <tbody>
       <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($sl as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->student_id</td>";
          $table_option .= "<td>$value->fees_head_name</td>";
          $table_option .= "<td>$value->received_amount</td>";
          $table_option .= "<td>$value->payment_date</td>";
          $table_option .= "</tr>";
        }

        echo $table_option;

    ?>

    </tbody>


</table>

<div class="d-flex">
    <div class="mx-auto">
        {{$sl->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>


  <div class="modal fade" id="modal-default" data-backdrop="static" >
      <div class="modal-dialog ">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="feeshead_title">Student Ledger</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{ route('searchStudentLedger') }}">
              @csrf
                <div class="card-body">

                  <div class="form-group">
                    <label for="school_id">School Name <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="school_id" id="school_id" required>
                          <option value="">Select school Name</option>
                          @foreach($schools as $data)
                            <option value="{{$data->id}}">{{$data->school_name}}</option>
                          @endforeach
                      </select>
                     </div>
                  </div>


                  <div class="form-group">
                    <label for="student_id">Student ID <span style="color:red;">*</span></label>
                    <div>
                      <select class="form-control" name="student_id" id="student_id" required>
                          <option value="">Select Student ID</option>
                      </select>
                     </div>
                  </div>

                   <div class="form-group">
                    <label for="amount">Date Range <span style="color:red;">*</span></label>
                    <div class="col-align-self-end">
                      <h4><b></b></h4>
                    </div>

                    <div class="col-sm align-self-start">
                        <input class="form-control" id="sdate" name="sdate" placeholder="Start Date" type="text"/>
                    </div>
                   </div>




                </div>

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Search</button>
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


    $("#school_id").change(function(){
          var school_id = document.getElementById("school_id").value;
          console.log(school_id);

          if(school_id !== ""){
            var url = globalURL + "get-school-wise-student/"+school_id;
              $('#student_id').empty();
              $('#student_id').append('<option value="0" disabled selected>Fetching Data...</option>');

              $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function(response){
                         $('#student_id').empty();
                         $('#student_id').append('<option value="0" disabled selected>Select Student ID</option>');

                         response.forEach(row =>{
                            $('#student_id').append('<option value="'+row.id+'">'+row.student_id+'</option>');
                         });
                    },

                });
          }


    });




</script>

@endsection
