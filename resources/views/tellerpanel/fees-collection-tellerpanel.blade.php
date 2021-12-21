@extends('master')

@section('title','Cash Collections')

@section('page_specific_css')
  <!-- page specific script will be here -->
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

 <table class="table table-hover table-condensed table-striped table-sm" >
  <thead >
    <tr style="background-color:#f1eeee">
      <th style="width:5%">SL</th>
      <th>School Name</th>
      <th style="width: 15%">Total Student</th>
      <th style="width: 20%">Total Amount</th>
    </tr>
  </thead>

  <tbody>

      @php $i=0;@endphp
      @foreach($datas as $d)
      <tr id="row_$d->id">
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
        <td>{{$d->total_students}}</td>
        <td>{{$d->total_amount}}</td>
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




<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">

</script>

@endsection
