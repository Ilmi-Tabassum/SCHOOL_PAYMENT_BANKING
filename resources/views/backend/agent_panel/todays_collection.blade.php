@extends('master')

@section('title','Todays Collection')

@section('page_specific_css')

@endsection


@section('content')

   <section class="content-header" style="margin-right: 1%;height: 50px">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Todays Collection</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active">Todays Collection</li>
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
      <th>SL</th>
      <th>Student ID</th>
      <th>Class</th>
      <th>Year</th>
      <th>Fees Head</th>
      <th>Amount</th>
      <th>Date</th>
    </tr>

  </thead>

  <tbody>
     <?php

      $table_option = "";
      $serial_no = 1;
      foreach ($todaysCollection as $key => $value) {
        $table_option .= "<tr id='row_$value->id'>";
        $table_option .= "<td>" . $serial_no++ . "</td>";
        $table_option .= "<td>$value->student_id</td>";
        $table_option .= "<td>$value->name</td>";
        $table_option .= "<td>$value->year</td>";
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
        {{$todaysCollection->links("pagination::bootstrap-4")}}
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
