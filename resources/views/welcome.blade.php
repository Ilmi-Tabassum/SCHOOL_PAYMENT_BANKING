<!DOCTYPE html>
<html lang="en">
  @include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('common.preloader')

  <!-- Top navigation bar start-->
    @include('common.top-navbar')
  <!-- Top navigation bar end-->

  <!-- left navigationbar start -->
    @include('common.left-navbar')
  <!-- left navigationbar end -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
           {{-- <h1 class="m-0">Dashboard</h1>--}}
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>


    <?php

    use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\DB;$user_type = auth()->user()->user_type_id;

     ?>

    @if($user_type==1)
    <!-- Main content -->
    <section class="content">
     <div class="row">

     <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fa fa-plus"></i></span>
          <div class="info-box-content">
            <?php
              $total_school = App\Models\SchoolInfo::select('id')->where('status', 1)->get()->count();
            ?>
            <span class="info-box-text">Total Affiliated Schools</span>
            <a href="{{ route('school_info', 'gen=active') }}"><span class="info-box-number">{{$total_school}}</span>
            </a>


          </div>

        </div>

      </div>


     <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">

          <?php

            $monthly_active_payment =DB::table('invoice')
                    ->select('school_id')
                    ->where('month', '=', date('m'))
                    ->where('status', '!=', 2)
                    ->groupBy('school_id')
                    ->get()
                    ->count();
            //var_dump($monthly_active_payment);
          ?>

          <span class="info-box-text">Monthly Active Payments</span>
          <a href="{{url('/monthly-active-payments')}}"><span class="info-box-number">{{$monthly_active_payment}}</span>
          </a>

        </div>
      </div>
     </div>


     <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-minus-circle"></i></span>
        <div class="info-box-content">
           <?php

            $total_school =DB::table('invoice')
                ->select('school_id')
                ->where('month', '=', date('m'))
                ->where('status', '=', 0)
                ->groupBy('school_id')
                ->get()
                ->count();
            ?>
          <span class="info-box-text">Payment dues</span>
          <a href="{{url('/monthly-due-payments')}}" ><span class="info-box-number">{{$total_school}}</span></a>

        </div>
      </div>
     </div>

     <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
         <span class="info-box-icon" style="background-color: #ff3333"><i class="fa fa-flag" style="color: #fff"></i></span>
        <div class="info-box-content">
          <?php
             $new_onboard = App\Models\SchoolInfo::whereMonth('created_at', '=', date('m'))->where('status','=',1)->get()->count();
          ?>
          <span class="info-box-text">Newly Onboarded Schools</span>
          <a href="{{url('/newly_onboard')}}" ><span class="info-box-number">{{$new_onboard}}</span></a>

        </div>
      </div>
     </div>

    </div>

<div></div>

     <div class="row">

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <?php
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->whereMonth('transaction_lists.trn_date', '=', date('m'))
                    ->where('invoice.status','!=',2)
                    ->where('invoice.status','!=',0)
                    ->get();
                if (empty($data[0]->paid_total_amount)) {
                    $total_monthly_collection=0;
                }
                if (!empty($data[0]->paid_total_amount)) {
                    $total_monthly_collection=$data[0]->paid_total_amount;
                }
                ?>
              <div class="inner"><h3>{{$total_monthly_collection}}</h3><p>Monthly Collections</p></div>
              <div class="icon"><i class="fa fa-shopping-cart"></i></div>
              <a href="{{url('/monthly-collection')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
              <?php
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->whereDay('transaction_lists.trn_date', '=', date('Y-m-d'))
                    ->where('invoice.status','!=',2)
                    ->where('invoice.status','!=',0)
                    ->get();
                if (empty($data[0]->paid_total_amount)) {
                    $todays_collection=0;
                }
                if (!empty($data[0]->paid_total_amount)) {
                    $todays_collection=$data[0]->paid_total_amount;
                }
                ?>
              <div class="inner"><h3>{{$todays_collection}}</h3><p>Todays Collection</p></div>
              <div class="icon"><i class="fa fa-shopping-cart"></i></div>
              <a href="{{url('/today-collection')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
              <?php
                /*
                 *Week start date : SUNDAY
                 *Week end date : SATURDAY
                */
                $now = Carbon\Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d');
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->whereBetween('transaction_lists.trn_date',[$weekStartDate, $weekEndDate])
                    ->where('invoice.status','!=',2)
                    ->where('invoice.status','!=',0)
                    ->get();

                if (empty($data[0]->paid_total_amount)) {
                    $weekly_collection_amount=0;
                }
                if (!empty($data[0]->paid_total_amount)) {
                    $weekly_collection_amount=$data[0]->paid_total_amount;
                }              ?>
              <div class="inner"><h3>{{$weekly_collection_amount}}</h3><p>This Week Collection</p></div>
              <div class="icon"><i class="fa fa-shopping-cart"></i></div>
              <a href="{{url('/weekly-collection')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
              <?php
                $data =DB::table('invoice')
                    ->select(DB::raw('SUM(invoice.total_amount) AS total_amount'))
                    ->where('invoice.status','=',0)
                    ->get();

                if (empty($data[0]->total_amount)) {
                    $total_dues=0;
                }
                if (!empty($data[0]->total_amount)) {
                    $total_dues=$data[0]->total_amount;
                }
              ?>



              <div class="inner"><h3>{{$total_dues}}</h3><p>Total Dues</p></div>
              <div class="icon"><i class="fa fa-shopping-cart"></i></div>
              <a href="{{url('/total-dues')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

    </div>

    </section>
    @endif









    @if($user_type==2 || $user_type==10)

     <?php
        $school_id=auth::user()->school_id;
     ?>

    <section class="content">
     <div class="row">

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <?php

             $school_id=auth::user()->school_id;

              $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                ->whereMonth('transaction_lists.trn_date', '=', date('m'))
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','!=',2)
                ->where('invoice.status','!=',0)
                ->get();

             if (empty($data[0]->paid_total_amount)) {
               $total_monthly_collection=0;
             }
              if (!empty($data[0]->paid_total_amount)) {
                $total_monthly_collection=$data[0]->paid_total_amount;
             }
          ?>
          <div class="inner"><h3>{{$total_monthly_collection}}</h3><p>Monthly Collections</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
          <a href="{{url('/monthly-collection-sa')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>


      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <?php
            $school_id=auth::user()->school_id;

              $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                ->whereDay('transaction_lists.trn_date', '=', date('Y-m-d'))
                ->where('invoice.school_id','=',$school_id)
                  ->where('invoice.status','!=',2)
                  ->where('invoice.status','!=',0)
                  ->get();

             if (empty($data[0]->paid_total_amount)) {
               $todays_collection=0;
             }
              if (!empty($data[0]->paid_total_amount)) {
                $todays_collection=$data[0]->paid_total_amount;
             }
          ?>
          <div class="inner"><h3>{{$todays_collection}}</h3><p>Todays Collections</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
          <a href="{{url('/today-collection-sa')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>


      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <?php
            /*
             *Week start date : SUNDAY
             *Week end date : SATURDAY
            */
            $now = Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d');

             $school_id=auth::user()->school_id;

              $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('SUM(transaction_lists.amount) AS paid_total_amount'))
                ->whereBetween('transaction_lists.trn_date',[$weekStartDate, $weekEndDate])
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','!=',2)
                ->where('invoice.status','!=',0)
                ->get();

             if (empty($data[0]->paid_total_amount)) {
               $weekly_collection_amount=0;
             }
              if (!empty($data[0]->paid_total_amount)) {
                $weekly_collection_amount=$data[0]->paid_total_amount;
             }

          ?>

          <div class="inner"><h3>{{$weekly_collection_amount}}</h3><p>This Week Collections</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
          <a href="{{url('/weekly-collection-sa')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-blue">
           <?php

             $school_id=auth::user()->school_id;

              $data =DB::table('invoice')
                ->select(DB::raw('SUM(invoice.total_amount) AS total_amount'))
                ->where('invoice.month', '=', date('m'))
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',0)
                ->get();

             if (empty($data[0]->total_amount)) {
               $total_monthly_dues=0;
             }
              if (!empty($data[0]->total_amount)) {
                $total_monthly_dues=$data[0]->total_amount;
             }
          ?>
          <div class="inner"><h3>{{$total_monthly_dues}}</h3><p>Monthly Total Dues</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
          <a href="{{url('/total-dues-sa')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

    </div>

    </section>
    @endif




     @if($user_type==4)

     <?php
        $teller_user_id=auth::user()->id;
     ?>

    <!-- Main content -->
    <section class="content">
     <div class="row">

       <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-yellow">
          <?php
             $today_collection = App\Models\TransactionList::whereDay('trn_date', '=', date('d'))->where('user_id','=',$teller_user_id)->sum('amount');
          ?>

          <div class="inner"><h3>{{$today_collection}}</h3><p>Todays Collection</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
{{--
          <a href="{{url('/today-collection-tp')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
--}}
        </div>
      </div>



      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-red">
          <?php
            /*
             *Week start date : SUNDAY
             *Week end date : SATURDAY
            */
            $now = Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
            $weekly_amount = App\Models\TransactionList::whereBetween('trn_date', [$weekStartDate, $weekEndDate])->where('user_id','=',$teller_user_id)->sum('amount');
          ?>
          <div class="inner"><h3>{{$weekly_amount}}</h3><p>This Week Collection</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
{{--
          <a href="{{url('/weekly-collection-tp')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
--}}
        </div>
      </div>


      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-green">
          <?php
             $monthly_collection = App\Models\TransactionList::whereMonth('trn_date', '=', date('m'))->where('user_id','=',$teller_user_id)->sum('amount');
          ?>
          <div class="inner"><h3>{{$monthly_collection}}</h3><p>Monthly Collections</p></div>
          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
{{--
          <a href="{{url('/monthly-collection-tp')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
--}}
        </div>
      </div>


    </div>

    </section>
    @endif

  @if($user_type==3)

      <?php
      $teller_user_id=auth::user()->id;
      ?>

      <!-- Main content -->
          <section class="content">
              <div class="row">

                  <div class="col-lg-4 col-xs-6">
                      <div class="small-box bg-blue">
                            <?php

                          $guardian_mobile_number = Auth::user()->mobile_number;
                          $sibling_students = DB::select(DB::raw("SELECT student_id FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
                          $student=[];
                          for($i=0;$i<sizeof($sibling_students);$i++)
                              {
                                  if($sibling_students[$i]->student_id)
                                      {
                                          array_push($student,$sibling_students[$i]->student_id);
                                      }
                              }
                         // var_dump($student);
                          $count_student = count($sibling_students);
                            ?>
                          <div class="inner"><h3>{{ $count_student }}</h3> <p>  Students</p></div>
                          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                          <a href="{{url('/student-list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>



                  <div class="col-lg-4 col-xs-6">
                      <div class="small-box bg-red">
                          <?php
                          $paid = App\Models\Invoice::whereIN('student_id',$student)->where('status','!=',0)->where('status','!=',2)->sum('total_amount');
                          $due = App\Models\Invoice::whereIN('student_id',$student)->where('status','!=',0)->where('status','!=',2)->sum('due');
                          $paid=$paid-$due;

                          ?>
                          <div class="inner"><h3>{{ $paid }}</h3> <p>  Payments</p></div>
                          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                          <a href="{{url('/payment-list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>


                  <div class="col-lg-4 col-xs-6">
                      <div class="small-box bg-green">
                          <?php
                          $due = App\Models\Invoice::whereIN('student_id',$student)->where('status','!=',1)->where('status','!=',2)->sum('due');
                          ?>
                          <div class="inner"><h3>{{$due}}</h3> <p>  Dues</p></div>
                          <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                          <a href="{{url('/dues-list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                  </div>


              </div>

          </section>
  @endif







  </div>


   @include('common.footer')
  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>

 @include('common.page-script')
 @yield('custom-script')
</body>
</html>
