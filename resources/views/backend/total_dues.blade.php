@extends('master')

@section('title','Total Dues')

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
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Total Dues</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Total Dues</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">


       <form method="POST" action="{{route('searchTotalDuesSchoolWise')}}">
        @csrf
        <div class="row">
            <div class="col-6">
               <a href="{{route('totalDues')}}" class="btn medium hover-purple bg-red">
          <i class="fa fa-eye" aria-hidden="true"></i> <span style="margin-left: 5px">View Total Dues</span>
        </a>
            </div>

            <div class="col-4">
              <select class="form-control" name="school_id" id="school_id" required oninvalid="this.setCustomValidity('Please select a school name in the list')" oninput="setCustomValidity('')">
                  <option value="">Select School Name</option>
                  @foreach($school_info as $data)
                    <option value="{{$data->id}}">{{$data->school_name}}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-2">
             <button type="submit" class="btn btn-danger btn-block">Search Total Dues</button>
            </div>
        </div>
      </form>
  </div>


 <table class="table table-hover table-condensed table-striped table-bordered" >
  <thead >
    <tr >
      <th style="width:10%">SL</th>
      <th>School Name</th>
      <th style="width: 20%">Total Dues Amount</th>
    </tr>
  </thead>

  <tbody>
     @php $i=0;@endphp
      @foreach($datas as $d)
      <tr>
        @php $i++;@endphp
        <td>{{$i}}</td>
        <td>
        <?php
           $info = DB::table('school_infos')
                        ->where('id','=',$d->school_id)
                        ->first();
           echo $info->school_name;
         ?>

        </td>
        <td>{{$d->due_total_amount}}
{{--          <?php

               $count_school_student = DB::table('student_academics')
                     ->where('school_id',$d->school_id)
                     ->distinct('student_id')
                     ->count('student_id');
                $pabeIntotal = $count_school_student*$d->total_amount;


                 $peyeceTotal = DB::table('fees_collections')
                     ->where('school_id',$d->school_id)
                     ->whereYear('created_at', '=', date('Y'))
                     ->sum('received_amount');


                $school_total__dues_amount = $pabeIntotal - $peyeceTotal;
                echo  $school_total__dues_amount;

          ?>--}}

        </td>
      </tr>
      @endforeach


  </tbody>
</table>


<div class="d-flex">
    <div class="mx-auto">
       {{$datas->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>




  <aside class="control-sidebar control-sidebar-dark">

  </aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">


</script>

@endsection
