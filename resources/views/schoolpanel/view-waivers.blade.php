@extends('master')

@section('title','View Waivers')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">View Waivers</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">View Waivers</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  
  <div class="card-header">
    <form method="post" action="{{route('searchWaiver')}}">
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
               <select class="form-control" name="student_id" id="student_id">
                <option value="">Select Student ID</option>
                
              </select>
             </div>
          </div>

        <div class="col-4">
         <button type="submit" class="btn btn-primary" style="background-color:#ee1b22;border-color:#ee1b22;">
          <span style="margin-left:5px">Search Waiver</span>
         </button>
        </div>

       </div>
    </form>

  </div>


 <table class="table table-hover table-condensed table-striped" >
  <thead >
    <tr style="background-color:#fff">
      <th>SL</th>
      <th>Student ID</th>
      <th>Class</th>
      <th>Year</th>
      <th>Fees Head</th>
      <th>Amount</th>
      <th>Waiver Amount</th>
    </tr>
  </thead>

  @if($hasData==1)
  <tbody>
    @php $i=0; @endphp
    @foreach($waivers as $data)
     @php $i++; @endphp
    <tr>
      <td>{{$i}}</td>
      <td>{{$data->full_student_id}}</td>
      <td>{{$data->class_name}}</td>
      <td>{{$data->year_id}}</td>
      <td>{{$data->fees_head_name}}</td>
      <td>{{$data->fees_amount}}</td>
      <td>{{$data->discount_amount}}</td>
    </tr>
    @endforeach
  </tbody>
  @endif

  @if($hasData==0)
  <tbody>
    <tr>
      <td colspan="7" style="text-align: center;font-weight: bold;color: red">Search criterion/criteria is/are not selected</td>
    </tr>
  </tbody>
  @endif
</table>

@if($hasData==1)
<div class="d-flex">
    <div class="mx-auto">
         {{$waivers->links("pagination::bootstrap-4")}}
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
