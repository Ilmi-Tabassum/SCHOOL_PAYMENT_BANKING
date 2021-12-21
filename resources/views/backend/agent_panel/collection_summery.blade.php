@extends('master')

@section('title','Collection Summery')

@section('page_specific_css')

@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Collection Summery</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Collection Summery</li>
            </ol>
          </div>
        </div>
      </div>
    </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
   <div class="card-header">
     <form method="post" action="{{route('searchCollectionSummery')}}">
      @csrf
     <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="col-align-self-end">
            <h4><b></b></h4>
          </div>
          <div class="col-sm align-self-start">
              <input class="form-control" id="sdate" name="sdate" placeholder="Start Date" type="text"/ required="">
          </div>
        </div>

        <div class="col-md-8 col-sm-6">
         <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" style="background-color:#ee1b22;border-color:#ee1b22;margin-top:8px">
          <span style="margin-left:5px">Search Collection Summery</span>
         </button>
        </div>

     </div>
      </form>


  </div>

<table class="table table-hover table-condensed table-striped table-sm" >
    <thead >
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>Date</th>
      <th>Total Amount</th>
    </tr>

  </thead>

  <tbody>
     <?php

        $table_option = "";
        $serial_no = 1;
        foreach ($data as $key => $value) {
          $table_option .= "<tr id='row_$value->id'>";
          $table_option .= "<td>" . $serial_no++ . "</td>";
          $table_option .= "<td>$value->payment_date</td>";
          $table_option .= "<td>$value->day_sum</td>";
          $table_option .= "</tr>";
        }

        echo $table_option;
    ?>
  </tbody>


</table>

<div class="d-flex">
    <div class="mx-auto">
       {{$data->links("pagination::bootstrap-4")}}
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
