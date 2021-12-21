@extends('master')

@section('title','Waiver Report')

@section('page_specific_css')
 <!-- page specific css -->
@endsection


@section('content')
     
 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Waiver Report</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Waiver Report</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

            
<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
     <form method="post" action="{{route('generateWaiverReport')}}">
      @csrf
         <div class="row">
            <div class="col-md-4 col-sm-6">
                <div>
                  <select class="form-control" name="student_id" id="student_id" required oninvalid="this.setCustomValidity('Select a student ID in the list')" oninput="setCustomValidity('')">
                      <option value="">Select Student ID</option>
                      @foreach($students as $data)
                        <option value="{{$data->id}}">{{$data->student_id}}</option>
                      @endforeach
                  </select>
                 </div>
              
            </div>

            <div class="col-md-8 col-sm-6">
             <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22;">
              <span style="margin-left:5px">Search Student Wise Waiver</span>
             </button>
            </div>

         </div>
      </form>

     </div>
 <table class="table table-hover table-condensed table-striped" >
  <thead>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>Student Name</th>
      <th>Student ID</th>
      <th>Class</th>
      <th>Year</th>
      <th>Fee Head Name</th>
      <th>Waiver Amount</th>
    </tr>
  </thead>
@if($hasData==1)
  <tbody>
     @php $i=0;@endphp
      @foreach($waiverReport as $d)
        @php $i++;@endphp
        <tr>
          <td>{{$i}}</td>
          <td>{{$d->student_name}}</td>
          <td>{{$d->full_student_id}}</td>
          <td>{{$d->class_name}}</td>
          <td>{{$d->year_name}}</td>
          <td>{{$d->fees_head_name}}</td>
          <td>{{$d->discount_amount}}</td>
        </tr>
      @endforeach    
  </tbody>
@endif

@if($hasData==0)
  <tbody>
    <tr>
      <td colspan="5" style="text-align: center;font-weight: bold;color: red">No Data Available</td>
    </tr> 
  </tbody>
@endif      

</table>

<div class="d-flex">
  @if($hasData==1)
    <div class="mx-auto">
        {{$waiverReport->links("pagination::bootstrap-4")}}
    </div>
  @endif
</div>  

</div>   

<aside class="control-sidebar control-sidebar-dark"> </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
 /*Page specific script*/
</script>

@endsection