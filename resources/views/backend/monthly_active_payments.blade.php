@extends('master')

@section('title','Collections')

@section('page_specific_css')

@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">{{$title}}</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
   <form method="POST" action="{{route('serachMonthlyActivePayments')}}">
        @csrf
        <div class="row">
           <div class="col-3">
            <div>
               <select class="form-control select2" name="month"  id="month" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
                <option value="">Select Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
             </div>
          </div>

            <div class="col-2">
             <button type="submit" class="btn btn-danger">Search</button>
            </div>
        </div>
      </form>
  </div>


 <table class="table table-hover table-condensed table-striped">

  <thead >
    <tr style="background-color:#fff">
      <th style="width:5%">SL</th>
      <th>School Name</th>
      <th> Amount</th>
    </tr>
  </thead>

  <tbody>
   @php $i=1; @endphp
   @foreach($data as $d)

    <tr>
      <td>{{$i}}</td>
      <td>{{$d->names}}</td>
      <td>{{$d->paid_total_amount}}</td>
    </tr>

    @php $i++; @endphp
   @endforeach

  </tbody>

</table>


<div class="d-flex">
    <div class="mx-auto">
        {{$data->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>


 <div class="modal fade" id="DetailsModal" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">

          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="card">

              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">

                  <thead>
                    <tr>
                      <th>SI</th>
                      <th>Name</th>
                      <th>Student ID</th>
                      <th>Invoice No</th>
                      <th>Amount</th>
                      <th>Paid Date</th>
                    </tr>
                  </thead>

                  <tbody id="DynamicDataSet">

                    <!-- <tr>
                      <td>1</td>
                      <td>Mominur Rahman</td>
                      <td>1209023</td>
                      <td>062021112090</td>
                      <td>500</td>
                      <td>2021-06-01</td>
                    </tr> -->

                  </tbody>

                </table>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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


  function DetailsInfo(class_id) {

     if(class_id !==""){

        var url = globalURL + "paid-students-details/"+class_id;
         $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                  var size = response.data.length;
                  if (size>0) {
                     $("#DynamicDataSet").empty();
                     var serial_no=1;
                      response.data.forEach(row =>{
                        $('#DynamicDataSet').append('<tr><td>'+serial_no+'</td><td>'+row.name+'</td><td>'+row.student_id+'</td><td>'+row.invoice_no+'</td><td>'+row.total_amount+'</td><td>'+row.trn_date+'</td></tr>');
                         serial_no++;
                     });

                  }

                },

            });

          $('#DetailsModal').modal('show');

       }
  }


   $("#class_info").change(function(){
       //alert('hello');
       //$("#section").disabled=false;
       document.getElementById("section").disabled=false;
       document.getElementById("section").required=true;
      /* if(id !== "" ){
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
       }*/
   });


</script>

@endsection
