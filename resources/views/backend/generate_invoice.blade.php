@extends('master')

@section('title','Generate Invoice')

@section('page_specific_css')

@endsection

@section('content')
  <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Generate Invoice</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Generate Invoice</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

    <div >
  <form method="post" action="{{route('storeGeneratedInvoices')}}">
      {{csrf_field()}}
     <div class="row">

        <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
            <select class="form-control select2" name="year" required="" id="year233" onchange="hideShowDiv()">
              <option value="">Select a year</option>
                @foreach($years as $value)
                  <option value="{{$value->name}}">{{$value->name}}</option>
                 @endforeach
            </select>
           </div>
        </div>

        <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
            <select class="form-control select2" name="class" required="" id="class233" onchange="hideShowDiv()" >
              <option value="">Select a class</option>
               @foreach($classes as $value)
                  <option value="{{$value->id}}">{{$value->name}}</option>
                 @endforeach
          </select>
           </div>
        </div>

         <div class="form-group col-4" style="padding-top: 10px">
          <div class="col-md-12">
            <select class="form-control select2" name="month" required="" id="month" onchange="hideShowDiv()">
              <option value="">Select a month</option>
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

   </div>

</div>


<div style="clear:both; height:10px;"></div>

<div class="card" style="margin-right:1%;margin-left:1%;display: none" id="hideshowMe" >
  <div class="card-body table-responsive p-0" style="height:350px;">
      <table class="table table-hover table-condensed table-striped table-head-fixed text-nowrap table-bordered table-sm">
        <thead style="background-color:#f1eeee">
          <tr>
            <th style="text-align: center;width:8%">SL</th>
            <th style="text-align: center;width:15%">Check</th>
            <th>Particulars</th>
            <th style="text-align: center;width:25%">Amount</th>
          </tr>
        </thead>

        <tbody>
           @php $i=0; @endphp
            @foreach($particulars as $value)
              @php $i++; @endphp

           <tr>
             <td style="text-align: center">{{$i}}</td>
              <td style="text-align: center">
              <input type="checkbox" name="check[]" id="{{$i}}" class="enabletext" value="{{$i}}" style="width:18px;height: 18px;margin-top: 3px;">
             </td>
             <td>{{$value->fees_head_name}}</td>
             <td style="text-align: center;">
              <input type="hidden" name="fees_id[{{$value->id}}]" value="{{$value->id}}">
              <input type="text" name="amount_{{$value->id}}" disabled="" id="amount{{$i}}" value="{{$value->amount}}" class="given_amount"maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
              onkeyup="calculate_intotal()" >
            </td>

           </tr>
        @endforeach

            <input type="hidden" name="total_sum" id="total_sum">

        </tbody>

      </table>
    </div>
  </div>

  <div class="row" id="hideSubmitBtn" style="display: none;margin-left:1%">
    <div class="col-sm-2">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="sendSMS" name="isSendSMS" value="1" style="width:18px;height: 18px;margin-top: 3px;" disabled>
        <label class="form-check-label" for="sendSMS" style="margin-left: 7px">
          Send SMS
        </label>
      </div>
    </div>

     <div class="col-sm-2">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="sendEmail" name="isSendEmail" value="1" style="width:18px;height: 18px;margin-top: 3px;" disabled>
        <label class="form-check-label" for="sendEmail" style="margin-left: 7px">
          Send Email
        </label>
      </div>
    </div>

      <div class="col-sm-2">
          <div class="form-check">
              <input class="form-check-input" type="checkbox" id="partial" name="isPartial" value="1" style="width:18px;height: 18px;margin-top: 3px;" disabled>
              <label class="form-check-label" for="Partial" style="margin-left: 7px">
                  Partial
              </label>
          </div>
      </div>

    <div class="col-sm-8" style="text-align: right;margin-bottom: 10px;">
       <input type="submit" class="btn btn-primary" style="background-color: red;border-color: red;margin-right: 4%" value="Generate Invoices" disabled id="generateInvoiceBtn">
    </div>

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
        var month = document.getElementById("month").value;


        if (class_id !== "" && year_id !=="" && month !=="") {
          $("#hideshowMe").show();
          $("#hideSubmitBtn").show();
        }
     }


     function calculate_intotal() {
        var i=0;
        var given_amount=[];
        var sum =0;
        $('.given_amount').each(function() {
             given_amount[i]=Number($(this).val());
              sum = sum+given_amount[i];
              if (sum>0) {
                $("#generateInvoiceBtn").attr("disabled", false);
                $("#sendSMS").attr("disabled", false);
                $("#sendEmail").attr("disabled", false);
                $("#partial").attr("disabled", false);
              }
              document.getElementById("total_sum").value = sum;
            i++;
        });


     }
</script>

@endsection
