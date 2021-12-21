@extends('master')

@section('title','Dues Report')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Dues Report</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Dues Report</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  
  <div class="card-header">
    <form method="post" action="{{route('SearchDuesReport')}}">
      @csrf
       <div class="row">
        
          <div class="col-3">
            <div>
               <select class="form-control" name="class_id" id="class_id">
                <option value="">Select Class</option>
                @foreach ($classes as $class)
                  <option value="{{$class->id}}">{{$class->name}}</option>
                @endforeach
              </select>
             </div>
          </div>

           <div class="col-3">
            <div>
               <select class="form-control" name="month_number" id="month_number">
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

        <div class="col-4">
         <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22;">
          <span style="margin-left:5px">Search Dues Report</span>
         </button>
        </div>

       </div>
    </form>

  </div>


 <table class="table table-hover table-condensed table-striped" >
  <thead >
    <tr style="background-color:#fff">
      <th>SL</th>
      <th>Invoice No</th>
      <th>Student ID</th>
      <th>Class Name</th>
      <th>Total Amount</th>
      <th>Payment ID</th>
      <th>Month</th>
      <th>Year</th>
      <th>Status</th>
    </tr>
  </thead>

  @if($hasData==1)
  <tbody>
       @php $i=0; @endphp
    @foreach($invoices as $data)
     @php $i++; @endphp
    <tr>
      <td>{{$i}}</td>
      <td>{{$data->invoice_no}}</td>
      <td>{{$data->full_student_id}}</td>
      <td>{{$data->class_name}}</td>
      <td>{{$data->total_amount}}</td>
      <td>{{$data->payment_id}}</td>
      <td>
        <?php 

          if ($data->month=='01') {
            echo "January";
          }
          elseif ($data->month=='02') {
            echo "February";
          }
          elseif ($data->month=='03') {
            echo "March";
          }
          elseif ($data->month=='04') {
            echo "April";
          }
          elseif ($data->month=='05') {
            echo "May";
          }
          elseif ($data->month=='06') {
            echo "June";
          }
          elseif ($data->month=='07') {
            echo "July";
          }
          elseif ($data->month=='08') {
            echo "August";
          }
          elseif ($data->month=='09') {
            echo "September";
          }
          elseif ($data->month=='10') {
            echo "October";
          }
          elseif ($data->month=='11') {
            echo "November";
          }
          elseif ($data->month=='12') {
            echo "December";
          }
          else{
            echo " ";
          }

        ?>
      </td>
      <td>{{$data->year}}</td>
      <td>
        <?php 
          if ($data->status==1) {
            echo "Paid";
          }
          else{
            echo "Due";
          }

        ?>
      </td>
    </tr>
    @endforeach
  </tbody>
  @endif

  @if($hasData==0)
  <tbody>
    <tr>
      <td colspan="7" style="text-align: center;font-weight: bold;color: red">Search criteria are not selected</td>
    </tr>
  </tbody>
  @endif
</table>

@if($hasData==1)
<div class="d-flex">
    <div class="mx-auto">
         {{$invoices->links("pagination::bootstrap-4")}}
    </div>
</div>
@endif

</div>

  


<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
  
  $("#class_id").change(function(){
        
      var class_id = $(this).val();
      if(class_id !== ""){
          var url = globalURL + "studentid/"+class_id;
          $('#student_id').empty();
          $('#student_id').append('<option value="">Fetching Student ID...</option>');

          $.ajax({
              type: "GET",
              url: url,
              dataType: 'json',
              success: function(response){
                  if(response.hasStudent==1){
                     $('#student_id').empty();
                     $('#student_id').append('<option value="">Select Student ID</option>');
                     response.students.forEach(row =>{
                      $('#student_id').append('<option value="'+row.id+'">'+row.s_id_full+'</option>');
                     });
                  }
                  else{
                    $('#student_id').empty();
                    $('#student_id').append('<option value="">No Student Available</option>');
                  }
              }

          });
      }
        
    });
</script>

@endsection
