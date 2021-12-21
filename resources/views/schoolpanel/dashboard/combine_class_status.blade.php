@extends('master')

@section('title','Collections')

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
   <form method="POST" action="{{route('serachCollectionSA')}}">
        @csrf
        <div class="row">
           <div class="col-3">
            <div>
               <select class="form-control select2" name="month"  id="month" oninvalid="this.setCustomValidity('Please select a month in the list')" oninput="setCustomValidity('')">
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

          <div class="col-2">
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

          <div class="col-3">
            <div>
               <select class="form-control select2" name="class_info" id="class_info" oninvalid="this.setCustomValidity('Please select a class in the list')" oninput="setCustomValidity('')">
                <option value="">Select Class</option>
                <option value="all">All Classes</option>
                @foreach($classes_info as $class)
                  <option value="{{$class->class_id}}">{{$class->class_name}}</option>
                @endforeach
              </select>
            </div>
          </div>

           <div class="col-2">
            <div>
               <select class="form-control select2" name="statuss" id="statuss" oninvalid="this.setCustomValidity('Please select a status in the list')" oninput="setCustomValidity('')">
                <option value="">Select Status</option>
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="due">Due</option>
              </select>
            </div>
          </div>



            <div class="col-2">
             <button type="submit" class="btn btn-danger">Search</button>
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
      <th>Due Student</th>
      <th>Due Amount</th>
      <th>Paid Student</th>
      <th>Paid Amount</th>
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
          echo $class[0]->name;
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

  

 
<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">
  
</script>

@endsection