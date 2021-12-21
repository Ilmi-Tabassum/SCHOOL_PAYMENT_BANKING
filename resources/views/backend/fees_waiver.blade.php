@extends('master')

@section('title','Fees waiver')

@section('page_specific_css')
  .alert-message {
    color: red;
  }
@endsection

@section('content')


<div id="page-content" style="margin-top: 0px;margin-left: 20px">
     <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Fees-Waiver</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Fees-Waiver</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <form method="post" action="{{route('store_fees_waiver')}}">
        {{csrf_field()}}


      <div class="row">

        <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
           <select class="form-control select2" name="year" required="" id="year_fw">
            <option value="">Select Year</option>
            @foreach($years as $value)
            <option value="{{$value->name}}">{{$value->name}}</option>
           @endforeach
          </select>
           </div>
        </div>

        <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
              <select class="form-control select2" name="class" required="" id="class_fw2">
              <option value="">Select Class</option>
               @foreach($classes as $value)
                <option value="{{$value->id}}">{{$value->name}}</option>
               @endforeach
          </select>
           </div>
        </div>

         <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
            <select class="form-control select2" name="student_id" required="" id="student_data_fw" onchange="hideShowDiv()">
              <option value="">Select Student ID</option>
          </select>
           </div>
        </div>
   </div>


<div style="clear:both; height:10px;"></div>


  <div class="card" style="margin-right:1%;margin-left:1%;display: none" id="student_information_div">
        <div class="card-header">
          <h3 class="card-title">Student Information</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-sm">
            <tbody>
              <tr>
                <td><b>Student Name</b></td>
                <td id="studen_namee"></td>
                <td><b>School Name</b></td>
                <td id="student_school_name"></td>
              </tr>

              <tr>
                <td><b>Class</b></td>
                <td id="student_class_name"></td>
                <td><b>Shift</b></td>
                <td id="student_shift_name"></td>
              </tr>


              <tr>
                <td><b>Section</b></td>
                <td id="student_section_name"></td>
                <td><b>Session</b></td>
                <td id="student_session_name"></td>
              </tr>
            </tbody>
          </table>
        </div>

    </div>





<div class="card" style="margin-right:1%;margin-left:1%;display: none" id="hideshowMee" >
  <div class="card-body table-responsive p-0" style="height:350px;">
      <table class="table table-hover table-condensed table-striped table-head-fixed text-nowrap table-bordered table-sm">
        <thead style="background-color:#f1eeee">
          <tr >
            <th>SL</th>
            <th>Fees Head Name</th>
            <th>Fees Amount</th>
            <th>Paid/Waiver</th>
            <th>Waiver Amount</th>
          </tr>
        </thead>

        <tbody id="FeesWaiverTbl">

        </tbody>
      </table>
    </div>
  </div>

  <div style="text-align: center;margin-bottom: 10px;display: none" id="hideSubmitBtn_fw">
    <input type="submit" class="btn btn-primary" name="" style="background-color: red;border-color: red">
  </div>

</form>
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


     $("#class_fw2").change(function(){
        var class_id = $(this).val();
        var year_id = document.getElementById("year_fw").value;
        if(year_id !== "" && class_id !== ""){
            var url = globalURL + "fetch-student-id/"+year_id+ "/"+ class_id;
            $('#student_data_fw').empty();
            $('#student_data_fw').append('<option value="">Fetching Student ID...</option>');

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                  if(response.hasStudent==1){
                    $('#student_data_fw').empty();
                    $('#student_data_fw').append('<option value="">Select Student ID</option>');

                    response.students.forEach(row =>{
                        //alert(row.id);
                        $('#student_data_fw').append('<option value="'+row.id+'">'+row.student_id+'</option>');
                    });
                  }
                  else{
                    $('#student_data_fw').empty();
                    $('#student_data_fw').append('<option value="">No Student ID Available</option>');
                  }

                },

            });
        } //end if

        if(year_id==="") {
          $("#year_fw").change(function(){
          var year_id = $(this).val();
          var class_id = document.getElementById("class_fw2").value;
          if(year_id !== "" && class_id !== ""){
              var url = globalURL + "fetch-student-id/"+year_id+ "/"+ class_id;
              $('#student_data_fw').empty();
              $('#student_data_fw').append('<option value="">Fetching Student ID...</option>');

              $.ajax({
                  type: "GET",
                  url: url,
                  dataType: 'json',
                  success: function(response){
                    if(response.hasStudent==1){
                      $('#student_data_fw').empty();
                      $('#student_data_fw').append('<option value="">Select Student ID</option>');

                      response.students.forEach(row =>{
                          $('#student_data_fw').append('<option value="'+row.id+'">'+row.student_id+'</option>');
                      });
                    }

                    else{
                      $('#student_data_fw').empty();
                      $('#student_data_fw').append('<option value="">No Student ID Available</option>');
                    }

                  },

              });
             }
           });
        } //end else if

    });


     function hideShowDiv() {
      var class_id = document.getElementById("class_fw2").value;
      var year_id = document.getElementById("year_fw").value;
      var student_id = document.getElementById("student_data_fw").value;

       if(year_id !== "" && class_id !== "" && student_id !== ""){
          var url = globalURL + "get-waiver-info";
           $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {'year_id' : year_id,'class_id' : class_id,'student_id' : student_id},
                success: function(response){
                  console.log(response);

                  document.getElementById("studen_namee").innerText = response.student_details[0].student_name;
                  document.getElementById("student_school_name").innerText = response.student_details[0].school_name;
                  document.getElementById("student_class_name").innerText = response.student_details[0].class_name;
                  document.getElementById("student_shift_name").innerText = response.student_details[0].shift_name;
                  document.getElementById("student_section_name").innerText = response.student_details[0].section_name;
                  document.getElementById("student_session_name").innerText = response.student_details[0].session_id;
                  $("#student_information_div").show();



                  var size = response.data.length;
                  if (size>0) {
                     $("#FeesWaiverTbl").empty();
                     var serial_no=1;
                      response.data.forEach(row =>{
                        $('#FeesWaiverTbl').append('<tr><td>'+serial_no+'</td><td>'+row.fees_head_name+'</td><td style=text-align: center;"><input type="hidden" name="fees_id['+row.id+']" value="'+row.id+'"><input type="text" name="fees_amount_'+row.id+'" id="fees_amount'+serial_no+'" value="'+row.amount+'"  style="border:none;pointer-events:none;" readonly></td><td style="text-align: center;"><input type="text" name="paid_waiver_amount_'+row.id+'" id="paid_waiver_amount'+row.id+'" value="" style="border:none;pointer-events:none;" readonly></td><td style="text-align: center;"><input type="text" name="discount_amount_'+row.id+'" id="discount_amount'+serial_no+'" value="" style="width:80%" maxlength="10"></td></tr>');

                         serial_no++;
                     });
                  }
                  else{
                     $("#FeesWaiverTbl").empty();
                     $('#FeesWaiverTbl').append('<tr><td colspan="5" style="text-align:center">No Data Available</td></tr>');
                  }

                },

            });




        $("#hideshowMee").show();
        $("#hideSubmitBtn_fw").show();



       }



      if (year_id === "") {
        alert("Please select a year from dropdown list");
      }



     }

</script>

@endsection
