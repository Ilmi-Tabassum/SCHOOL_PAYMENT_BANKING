@extends('master')

@section('title','Monthly Total Dues')

@section('page_specific_css')

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
   <div class="card-header">
   <form method="POST" action="{{route('TotalDuesSearchSA')}}">
        @csrf
        <div class="row">
           <div class="col-3">
            <div>
               <select class="form-control select2" name="month" id="month" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
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

          <div class="col-3">
            <div>
               <select class="form-control select2" name="year" id="year" oninvalid="this.setCustomValidity('Please select a year in the list')" oninput="setCustomValidity('')">
                <option value="">Select Year</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
              </select>
            </div>
          </div>
            <div class="col-sm">
             <button type="submit" class="btn btn-danger" style="margin-right: 5px">Search</button><a class="btn btn-success" href="{{ route('exportMonthlyDue') }}">Exel Download</a>
            </div>

        </div>
      </form>
  </div>






 <table class="table table-hover table-condensed table-striped">

  <thead >
    <tr style="background-color:#fff">
      <th style="width:5%">SL</th>
      <th>Class Name</th>
      <th>Total Student</th>
      <th>Unpaid Student</th>
      <th>Due Amount</th>
    </tr>
  </thead>

  <tbody>
   @php $i=1; @endphp
   @foreach($data as $d)

    <tr>
      <td>{{$i}}</td>
      <td>
        <?php
          $class_id = $d->class_id;
          $class = DB::select(DB::raw("SELECT name FROM class_infos WHERE id = $class_id"));
          //echo $class[0]->name;
           echo '<a href="#" class="Show_Details" onclick="DetailsInfo('.$d->class_id.')">'.$class[0]->name.'</a>';
        ?>
      </td>
      <td>
        <?php
          $class_id = $d->class_id;
          $school_id = Auth::user()->school_id;
          $intotal_student = DB::select(DB::raw("SELECT COUNT(student_id)AS total_student FROM student_academics WHERE school_id=$school_id AND class_id=$class_id"));
          echo $intotal_student[0]->total_student;
        ?>
      </td>
      <td>{{$d->paid_total_students}}</td>
      <td>{{$d->paid_total_amount}}</td>
    </tr>

    @php $i++; @endphp
   @endforeach

  </tbody>

</table>


<div class="d-flex">
    <div class="mx-auto">
        {{$data->links("pagination::bootstrap-4")}}
    </div>
</div>

</div>


 <div class="modal fade" id="DetailsModal" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">

          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color">Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="card">

              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">

                  <thead>
                    <tr>
                      <th>SI</th>
                      <th>Name</th>
                      <th>Student ID</th>
                      <th>Invoice No</th>
                      <th>Amount</th>
                      <th>Invoice Creation Date</th>
                    </tr>
                  </thead>

                  <tbody id="DynamicDataSet">

                    <!-- <tr>
                      <td>1</td>
                      <td>Mominur Rahman</td>
                      <td>1209023</td>
                      <td>062021112090</td>
                      <td>500</td>
                      <td>2021-06-01</td>
                    </tr> -->

                  </tbody>

                </table>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
    </div>
  </div>
  </div>



<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
  var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var port = window.location.port;
    var pathname = window.location.pathname;
    pathname = pathname.split("/");
    var domainName = pathname[1];

    if(port){
      var globalURL = protocol + "//" + hostname + ":" + port + "/";
    }else{
      var globalURL = protocol + "//" + hostname + "/";
    }


  function DetailsInfo(class_id) {

     if(class_id !==""){

        var url = globalURL + "due-students-details/"+class_id;
         $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response){
                  console.log(response);

                  var size = response.data.length;
                  if (size>0) {
                     $("#DynamicDataSet").empty();
                     var serial_no=1;
                      response.data.forEach(row =>{
                        $('#DynamicDataSet').append('<tr><td>'+serial_no+'</td><td>'+row.name+'</td><td>'+row.student_id+'</td><td>'+row.invoice_no+'</td><td>'+row.total_amount+'</td><td>'+row.created_at+'</td></tr>');
                         serial_no++;
                     });

                  }

                },

            });

          $('#DetailsModal').modal('show');

       }
  }


</script>

@endsection
