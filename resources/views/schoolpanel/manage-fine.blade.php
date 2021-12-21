<!DOCTYPE html>
<html lang="en">
  @include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">
  @include('common.preloader')

  <!-- Top navigation bar start-->
    @include('common.top-navbar')
  <!-- Top navigation bar end-->

  <!-- left navigationbar start -->
    @include('common.left-navbar')
  <!-- left navigationbar end -->

  <div class="content-wrapper">

  <div id="page-content" style="margin-top: 20px;margin-left: 20px">

       <!-- Alert part -->
      <div id="page-content" style="margin-top: 20px;margin-left: 20px">
          @if(Session::has('success'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-success collapse">
                       <i class="nav-icon fas fa-info-circle"></i> {{ Session::get('success') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-times"></i></a>
                  </div>
              </div>
          </div>
          @endif



          @if(Session::has('error'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-danger collapse">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>  {{ Session::get('error') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-trash-alt"></i></a>
                  </div>
              </div>
          </div>
          @endif
      </div>
      <!-- ./ alert part -->

   <div id="page-content" style="margin-top: 20px;margin-left: 20px">
       <section class="content-header" style="margin-right: 1%;height: 50px">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
                       <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Manage Fine</h3>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                           <li class="breadcrumb-item active">Manage Fine</li>
                       </ol>
                   </div>
               </div>
           </div>
       </section>
       <div style="clear:both; height:10px;"></div>

    <button type="button" class="btn btn-primary" onclick="OpenFineModal()" style="background-color:#ee1b22;border-color:#ee1b22 ">
       <i class="fa fa-plus" aria-hidden="true"></i> Add Fine
    </button>

    <a href="{{route('maneFine')}}"  class="btn medium hover-purple bg-red" role="presentation" href="" title="">
      <i class="fa fa-eye" aria-hidden="true"></i> View All Fine
    </a>

<div style="clear:both; height:10px;"></div>
 <table class="table table-hover table-condensed table-striped">
  <thead>
    <tr style="background-color:#fff">
      <th>SL</th>
      <th>Class Name</th>
      <th>Student ID</th>
      <th>Student Name</th>
      <th>Fine Head</th>
      <th>Amount</th>
      <th>Year</th>
      <th>Month</th>
      <th>Reason</th>
      <th colspan="2">Actions</th>
    </tr>
    </thead>

    <tbody>
      @php $i=0; @endphp
      @foreach($fines as $data)
      @php $i++; @endphp
        <tr>
          <td>{{$i}}</td>
          <td>{{$data->class_name}}</td>
          <td>{{$data->full_student_id}}</td>
          <td>{{$data->name}}</td>

          <td>{{$data->fees_head_name}}</td>
          <td>{{$data->amount}}</td>
          <td>{{$data->year}}</td>
          <td>

            <?php 
              if ($data->month=='01') {
                echo "January";
              }
              elseif ($data->month=='02') {
                echo "February";
              }
              elseif ($data->month=='03') {
                echo "March";
              }
              elseif ($data->month=='04') {
                echo "April";
              }
              elseif ($data->month=='05') {
                echo "May";
              }
              elseif ($data->month=='06') {
                echo "June";
              }
              elseif ($data->month=='07') {
                echo "July";
              }
              elseif ($data->month=='08') {
                echo "August";
              }
              elseif ($data->month=='09') {
                echo "September";
              }
              elseif ($data->month=='10') {
                echo "October";
              }
              elseif ($data->month=='11') {
                echo "November";
              }
              elseif ($data->month=='12') {
                echo "December";
              }
              else{
                echo " ";
              }
            ?>

          </td>
          <td>{{$data->reasons}}</td>
          <td>
            <a href='#' class='tooltip-button EditFine' id='{{$data->id}}' style='padding-right: 10px' data-toggle='modal' data-target='#FineModal' title='Edit'><i class='nav-icon fas fa-edit text-warning'></i></a>
            
            <a href="{{route('deleteFine',$data->id)}}" class='tooltip-button' style='padding-right: 10px'><i class='nav-icon fas fa-trash text-danger' title='Delete'></i></a>
          </td>

        </tr>
      @endforeach
    </tbody>
</table>


 <div class="d-flex">
    <div class="mx-auto">
        {{$fines->links("pagination::bootstrap-4")}}
    </div>
</div>



  <div class="modal fade" id="FineModal" data-backdrop="static" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color" id="fine_title">Add Fine Details </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
              <form method="POST" action="{{route('StoreFine')}}" autocomplete="off">
              @csrf
                <div class="card-body">
                  <input type="hidden" name="updateFine" id="updateFine" value="">

                    <div class="form-group">
                      <label for="class_namee">Class Name <span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control select2" name="class_namee" id="class_namee" required oninvalid="this.setCustomValidity('Select a Class Name')" oninput="setCustomValidity('')">
                            <option value="">Select Class Name</option>
                            @foreach($classes as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                       </div>
                    </div>

                    <div class="form-group">
                      <label for="student_idd">Student ID <span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control select2" name="student_idd" id="student_idd" required oninvalid="this.setCustomValidity('Select a Student ID')" oninput="setCustomValidity('')">
                            <option value="">Select Student ID</option>
                        </select>
                       </div>
                    </div>

                    <div class="form-group">
                      <label for="month_numberr">Month Name <span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control select2" name="month" id="month_numberr" required oninvalid="this.setCustomValidity('Select a Month')" oninput="setCustomValidity('')">
                            <option value="">Select Month Name</option>
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

                    <div class="form-group">
                      <label for="head_id">Fine Head Name <span style="color:red;">*</span></label>
                      <div>
                        <select class="form-control select2" name="head_id" id="head_id" required oninvalid="this.setCustomValidity('Select a Fine Head Name')" oninput="setCustomValidity('')">
                            <option value="">Select Fine Head Name</option>
                            @foreach($heads as $data)
                            <option value="{{$data->id}}">{{$data->fees_head_name}}</option>
                            @endforeach
                        </select>
                       </div>
                    </div>


                    <div class="form-group">
                      <label for="fineAmount">Amount<span style="color:red;">*</span></label>
                      <div>
                        <input type="text" name="amount" id="fineAmount" class="form-control" maxlength="10" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="e.g 50">
                       </div>
                    </div>

                    <div class="form-group">
                      <label for="reasons">Reasons</label>
                      <div>
                        <textarea rows="4" cols="55" name="reasons" id="reasons" class="form-control"></textarea>
                       </div>
                    </div>

                </div>
               
               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" id="fine_btnText">Save</button>
               </div>
              </form>
            
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>


<aside class="control-sidebar control-sidebar-dark"></aside>

</div>
   @include('common.page-script')
   @yield('custom-script')
</body>
</html>
