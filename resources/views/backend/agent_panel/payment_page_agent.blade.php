@extends('master')

@section('title','Payment')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Payment</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Payment</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

<div class="row">

    <div class="col-md-12">
        <form method="post" id="PostForm" action="{{ route('store_payment_agent') }} " enctype="multipart/form-data" autocomplete="on">
            @csrf
            <input type="hidden" name="class_id" value="{{$class_id}}">
            <input type="hidden" name="school_id" value="{{$school_id}}">
            <input type="hidden" name="student_id" value="{{$student_id}}">
             <input type="hidden" name="year_id" value="{{$year_id}}">

            <div class="card-body">

                <div class="form-group">
                    <label for="assign_class">Payment </label>
                    <table class="table table-hover table-condensed table-striped table-sm" >
                        <thead>
                        <tr style="background-color:#f1eeee">
                          <th style="width:8%">Select</th>
                          <th>Fees Subhead Name</th>
                          <th>Amount</th>
                          <th>Pay Now</th>
                        </tr>
                      </thead>

                        @if($hasData==1)
                        <tbody>
                        @php $i=0; @endphp
                        @foreach($subheads as $data)
                          <tr>
                            <td>
                              <input type="hidden" name="fees_id[{{$data->id}}]" value="{{$data->id}}">
                              <input type="checkbox" name="fees_id_{{$data->id}}" value="{{$data->id}}" class="enabletext"  id="{{$data->id}}">
                            </td>

                            <td>{{ $data->fees_subhead_name}}</td>
                            <td>{{ $data->amount}}</td>

                            <td>
                              <input type="text" class="form-control given_amount" placeholder="" name="given_amount_{{$data->id}}" id="given_amount{{$data->id}}" value='' autocomplete="off" onkeyup="check_given_amount()" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" disabled=""></td>
                            </tr>
                             @php $i++; @endphp
                        @endforeach

                          <tr>
                            <td colspan="3" style="font-weight: bolder;text-align: right;">Total Amount</td>

                              <input type="hidden" name="total_payamount" style="text-align: center;font-weight: bolder;" id="total_amount" value="">

                            <td style="text-align: center;font-weight: bolder;" id="total_amount_display"></td>
                          </tr>

                        </tbody>
                        @endif

                        @if($hasData==0)
                        <tbody>
                          <tr>
                            <td colspan="4" style="text-align: center;font-weight: bolder;">{{$message}}</td>
                          </tr>
                        </tbody>
                        @endif



                    </table>

            </div>
                @if($hasData==1)
                <div class="form-group" style="text-align: right">
                  <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" disabled id="btnSubmit">Pay Now</button>
                </div>
                @endif

            </div>


        </form>
    </div>
</div>



</div>

  <aside class="control-sidebar control-sidebar-dark">

  </aside>
@endsection


@section('page_specific_script')

<script type="text/javascript">

function check_given_amount(){
    var i=0;
    var given_amount=[];
    var sum =0;
    $('.given_amount').each(function() {
        given_amount[i]=Number($(this).val());
        sum = sum+given_amount[i];
        document.getElementById("total_amount").value = sum;
        document.getElementById("total_amount_display").innerText = sum;
        if (sum>0) {
          $('#btnSubmit').attr("disabled", false);
        }
        i++;
    });

  }

 $(document).on('change','.enabletext',function(event)
    {
      var textid = "given_amount"+this.id;
      var valuee = document.getElementById(textid).value;
      console.log(valuee);
      $("#"+textid).prop("disabled",  !this.checked);
      $("#"+textid).val(valuee,  !this.checked);
    });


</script>

@endsection
