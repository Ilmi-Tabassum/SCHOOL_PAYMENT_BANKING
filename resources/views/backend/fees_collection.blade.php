@extends('master')

@section('title','Fees collection')

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
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Fees Collection</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Fees Collection</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


  <div style="background-color:#faeeee">
  <form method="post" action="{{route('store_fees')}}">
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
            <select class="form-control select2" name="class" required="" id="class_fw">
                <option value="">Select Class</option>
                 @foreach($classes as $value)
                  <option value="{{$value->id}}">{{$value->name}}</option>
                 @endforeach
            </select>
           </div>
        </div>

         <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
            <select class="form-control dtd_fees_collection" name="student_id" required="" id="student_data_fw">
               <option>Select Student ID</option>
                 <!-- Dynamically fetch student ID based on year and class -->
            </select>
           </div>
        </div>

   </div>
</div>

<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%;margin-left:1%;display: none" id="hideshowMe" >
 <table class="table table-hover table-condensed table-striped">
  <thead>
    <tr style="background-color:#eceff1">
      <th>SL</th>
      <th>Class Name</th>
      <th>Invoice No</th>
      <th>Total Amount</th>
      <th>Month</th>
      <th>Year</th>
      <th>Action</th>
    </tr>
 </thead>
<tbody id="invoiceTbl">
  
</tbody>

</table>

</form>

</div>

  <div class="modal fade" id="feesCollectionModal" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Fees Collection</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{route('store_fc')}}">
              @csrf
                <div class="card-body">
                  <table class="table table-hover table-condensed table-striped">
                    <thead>
                      <tr style="background-color:#eceff1">
                        <th>SL</th>
                        <th>Fees Head </th>
                        <th>Fees Amount</th>
                        <!-- <th>Waiver</th>
                        <th>Due Amount</th> -->
                        <th>Given Amount</th>
                      </tr>
                   </thead>
                   <tbody id="feesDetailsTbl">

                  
                  
                   </tbody>

                  </table>

                 
                </div> 

               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="payNowBtn" disabled>Pay Now</button>
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

  
     $("#student_data_fw").change(function(){
        $("#hideshowMe").show();
        var year_id = document.getElementById("year_fw").value;
        var class_id = document.getElementById("class_fw").value;
        var student_id = $(this).val();
      
         if(year_id !== "" && class_id !== "" && student_id !== ""){
            var url = globalURL + "student-dues-invoice";

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {'year_id' : year_id,'class_id' : class_id,'student_id' : student_id},
                success: function(response){
                  var size = response.length;
                  if (size>0) {
                     $("#invoiceTbl").empty();
                     var serial_no=1;
                      response.forEach(row =>{
                        $('#invoiceTbl').append('<tr><td>'+serial_no+'</td><td>'+row.class_name+'</td><td id="invoice_'+row.id+'">'+row.invoice_no+'</td><td>'+row.  total_amount+'</td><td>'+row.month+'</td><td>'+row.year+'</td><td><a class="btn btn-info" onclick=InvoiceDetails('+row.id+')>Collect</a></td></tr>');

                         serial_no++;
                     });

                      $("#invoiceTbl").append('<tr><td><input type="hidden" id="stu_id" value="'+student_id+'"/></td></tr>')
                  }
                  else{
                     $("#invoiceTbl").empty();
                     $('#invoiceTbl').append('<tr><td colspan="7" style="text-align:center">No Data Available</td></tr>');
                  }
                 
                },

            });
         }

         else{
          alert("Something Went Wrong...");
         }

     });

     function InvoiceDetails(id) {
       if(id !==""){
        var invoice_number = document.getElementById("invoice_"+id).innerText;
        var student_id = document.getElementById("stu_id").value;

        var url = globalURL + "invoice-details/"+id;
         $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                  var size = response.length;
                  if (size>0) {
                     $("#feesDetailsTbl").empty();
                     var serial_no=1;
                      response.forEach(row =>{
                        $('#feesDetailsTbl').append('<tr><td>'+serial_no+'</td><td>'+row.fees_head_name+'</td><td>'+row.amount+'</td><td><input type="text" name="given_amount" onkeyup="check_given_amount()" class="given_amount" /></td</tr>');
                         serial_no++;
                     });

                      $('#feesDetailsTbl').append(' <tr><td><input type="hidden" value="'+invoice_number+'" name="inv_no"/></td><td><input type="hidden" name="total_amt" value="" id="total_amt"/><input type="hidden" name="student_id" value="'+student_id+'"/></td><td colspan="1" style="text-align: right;font-weight: bolder;">Total Amount</td><td style="text-align: left;font-weight: bolder;" id="total_amount"></td></tr>');

                  }
                 
                },

            });

         $('#feesCollectionModal').modal('show');

       }
       
     }

    function check_given_amount(){
          var given_amount=[];
          i=0;
          var sum =0;
          $('.given_amount').each(function() {
               given_amount[i]=Number($(this).val());
                sum = sum+given_amount[i];
                document.getElementById("total_amount").innerText = sum;
                document.getElementById("total_amt").value = sum;
                if (sum>0) {
                  $("#payNowBtn").attr("disabled", false);
                }
              i++;
          });

      }


       $("#class_fw").change(function(){
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
          var class_id = document.getElementById("class_fw").value;
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



</script>

@endsection
