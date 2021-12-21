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
              <h3 style="font-size: 25px;font-weight: bolder;margin-left: -8px">Payment Gateway</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Payment Gateway</li>
              </ol>
            </div>
          </div>
        </div>
      </section>



<div class="card"  style="margin-right:1%">
    <div class="card-header">
        <div class="input-group input-group-sm">
            <a href="{{ route('payment_gateway') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add
            </a>
            &nbsp;&nbsp;&nbsp;
            <a href="{{ route('list') }}" id="" class="btn medium hover-purple bg-red" role="presentation" href="" title="" data-original-title="Add New Section">
                <i class="fa fa-eye" aria-hidden="true"></i> View
            </a>

            &nbsp;&nbsp;&nbsp;
            <a href="{{ route('list', 'gen=trash') }}" id="" class="btn medium hover-purple bg-black" role="presentation" href="" title="" data-original-title="Add New Section">
                <i class="fa fa-trash" aria-hidden="true"></i> Trash
            </a>
        </div>
    </div>

<!-- <div style="clear:both; height:10px;"></div> -->
 <table class="table table-hover table-condensed table-striped table-bordered " >
  <tbody>
    <tr style="background-color:#f1eeee">
      <th>SL</th>
      <th>School Name</th>
      <th>User Name</th>
      <th>Short code</th>
      <th>IPN</th>
      <th>Success Url</th>
      <th>Fail Url</th>
      <th>Cancel Url</th>
    </tr>




       <?php
        $table_option = "";
        $serial_no = 1;
        foreach ($list as $key => $value) {
          $table_option .= "<tr>";
          $table_option .= "<td>" . $serial_no++ . "</td>";

          if(Auth::user()->school_id){
             //
          }else{
              $obj=DB::select(DB::raw("SELECT * FROM school_infos WHERE id=$value->school_id"));
              $name=$obj[0]->school_name;
              $table_option .= "<td>$name</td>";
          }


          $table_option .= "<td>$value->user</td>";
          $table_option .= "<td>$value->s_code</td>";
          $table_option .= "<td>$value->ipn</td>";
          $table_option .= "<td>$value->success</td>";
          $table_option .= "<td>$value->fail</td>";
          $table_option .= "<td>$value->cancel</td>";



            $table_option .= "</tr>";
        }
        echo $table_option;

    ?>

      </tbody>
</table>


{{-- <div class="d-flex">
    <div class="mx-auto">
        {{$students->links("pagination::bootstrap-4")}}
    </div>
</div>--}}

</div>
  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>
   @include('common.page-script')
   @yield('custom-script')
</body>
</html>
