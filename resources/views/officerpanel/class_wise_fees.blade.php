@extends('master')

@section('title','Class wise fees')

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
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Class Wise Fees</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Officerpanel/Class Wise Fees</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

    <form method="post" action="{{route('officerpanel_store_class_wise_fees')}}">
        {{csrf_field()}}

    <div class="form-group">
        <div class="col-md-12">
          <select class="form-control" name="school_id" required="" id="school_id233">
              <option value="">Select School</option>
              @foreach($school_info as $value)
                <option value="{{$value->id}}">{{$value->school_name}}</option>
              @endforeach
          </select>
         </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
          <select class="form-control" name="year" required="" id="year233">
              <option value="">Select Year</option>
                @foreach($years as $value)
                  <option value="{{$value->id}}">{{$value->year}}</option>
                 @endforeach
          </select>
         </div>
    </div>

     <div class="form-group">
        <div class="col-md-12">
          <select class="form-control" name="class" required="" id="class233" onchange="hideShowDiv()">
              <option value="">Select Class</option>
               @foreach($classes as $value)
                  <option value="{{$value->id}}">{{$value->name}}</option>
                 @endforeach
          </select>
         </div>
     </div>



<div style="clear:both; height:10px;"></div>

<div class="card" style="margin-right:1%;margin-left:1%;display: none" id="hideshowMe" >
  <div class="card-body table-responsive p-0" style="height:350px;">
      <table class="table table-hover table-condensed table-striped table-head-fixed text-nowrap table-bordered table-sm">
        <thead style="background-color:#f1eeee">
          <tr >
            <th style="text-align: center">SL</th>
            <th style="text-align: center">Check</th>
            <th>Fees Head</th>
            <th>Fees Amount</th>
          </tr>
        </thead>

        <tbody>
           @php $i=0; @endphp
            @foreach($sub_head as $value)
              @php $i++; @endphp

           <tr>
             <td style="text-align: center">{{$i}}</td>
              <td style="text-align: center">
              <input type="checkbox" name="check[]" id="{{$i}}" class="enabletext" value="{{$i}}" style="width: 10%">
             </td>
             <td>{{$value->fees_subhead_name}}</td>
             <td style="text-align: center;">
              <input type="hidden" name="fees_id[{{$value->id}}]" value="{{$value->id}}">
              <input type="text" name="amount_{{$value->id}}" disabled="" id="amount{{$i}}" value="" class="" style="width:30%"
              maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
              >
            </td>

           </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div style="text-align: center;margin-bottom: 10px;display: none" id="hideSubmitBtn">
    <input type="submit" class="btn btn-primary" name="" style="background-color: red;border-color: red">
  </div>

</form>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection

@section('page_specific_script')

<script type="text/javascript">
   $(document).on('change','.enabletext',function(event)
      {
        var textid = "amount"+this.id;
        var valuee = document.getElementById(textid).value;
        $("#"+textid).prop("disabled",  !this.checked);
        $("#"+textid).val(valuee,  !this.checked);
      });


     function hideShowDiv() {
      var class_id = document.getElementById("class233").value;
      var year_id = document.getElementById("year233").value;
      if (year_id !== "") {
        $("#hideshowMe").show();
        $("#hideSubmitBtn").show();
      }
      if (year_id === "") {

        alert("Please select a year from dropdown list");
      }

     }
</script>

@endsection
