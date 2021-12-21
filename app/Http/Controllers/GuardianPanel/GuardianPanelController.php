<?php

namespace App\Http\Controllers\GuardianPanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\agentpanel\AgentPanel;
use App\Models\User;
use App\Models\UserProfile;
use DB;
use Auth;
use shurjopay\ShurjopayLaravelPackage\Http\Controllers\ShurjopayController;
use smasif\ShurjopayLaravelPackage\ShurjopayService;
use Intervention\Image\Facades\Image;
use App\Models\FeesCollection;
use App\Models\Invoice;
use App\Models\TransactionList;
use Mail;
use PDF;


class GuardianPanelController extends Controller
{

     public function index_student_list()
    {

      $school_names = DB::select(DB::raw("SELECT id,school_name  FROM school_infos ORDER BY school_name ASC"));
      $class_names = DB::select(DB::raw("SELECT id,name  FROM class_infos"));

      $guardian_mobile_number = Auth::user()->mobile_number;
      //echo $guardian_mobile_number;exit;

        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
      //dd($sibling_students);

      $count_student = count($sibling_students);
      //echo $count_student;exit;

        if ($count_student>0) {

          /*insert every student_id in student_ids array*/
          //echo $sibling_students[0]->student_id;exit;

          $student_ids = array();
          for ($i=0; $i <$count_student ; $i++) {
            $id = (int)($sibling_students[$i]->student_id);
            array_push($student_ids,$id);
          }

          /*retrieving student information based on student ids*/
          $students = DB::table('student_academics')
            ->join('students', 'student_academics.student_id', '=', 'students.id')
            ->join('school_infos', 'student_academics.school_id', '=', 'school_infos.id')
            ->join('class_infos', 'student_academics.class_id', '=', 'class_infos.id')
            ->join('shifts', 'student_academics.shift_id', '=', 'shifts.id')
            ->join('sections', 'student_academics.section_id', '=', 'sections.id')
            ->join('groups', 'student_academics.group_id', '=', 'groups.id')
            ->select('student_academics.student_id','student_academics.session_id','students.student_id','students.name', 'school_infos.school_name','class_infos.name as c_name','shifts.name as shift_name','sections.name as section_name','groups.name as group_name')
            ->whereIn('student_academics.student_id', $student_ids)
            ->paginate(30);

           //dd($students);

           $hasStudent = 1;
           return view('backend/gurdian_panel/student_list')->with(['students' => $students,'school_names'=>$school_names,'class_names'=>$class_names,'hasStudent'=>$hasStudent]);
        }


        /*if the total student number is Zero then return nothing...*/
        else{
           $hasStudent = 0;
           return view('backend/gurdian_panel/student_list')->with(['school_names'=>$school_names,'class_names'=>$class_names,'hasStudent'=>$hasStudent]);
        }

    }


     public function dues_list()
    {
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

        if ($count_student>0) {

          /*insert every student id in student_ids array*/
          $student_ids = array();
          for ($i=0; $i <$count_student ; $i++) {
            $id = (int)($sibling_students[$i]->student_id);
            array_push($student_ids,$id);
          }

          /*retrieving data*/
          $students = DB::table('students')
                   ->select('id','student_id')
                   ->whereIn('id', $student_ids)
                   ->get();

          $dues_list = DB::table("invoice")
                                ->join('school_infos','invoice.school_id','school_infos.id')
                                ->join('students','invoice.student_id','students.id')
                                ->select('invoice.*','school_infos.school_name','students.student_id as full_student_id')
                                ->whereIn('invoice.student_id',$student_ids)
                                ->where('invoice.status','!=',1)->where('invoice.status','!=',2)
                                ->paginate(30);
          $hasData=1;
          //dd($dues_list);
            return view('backend/gurdian_panel/accounts/dues_list')->with(['dues_list' => $dues_list,'students'=>$students,'hasData'=>$hasData]);
          }


          else{
            $students = DB::table('students')
                    ->select('id','student_id')
                    ->where('id', 0)
                    ->get();
            $hasData=0;
            return view('backend/gurdian_panel/accounts/dues_list')->with(['students'=>$students,'hasData'=>$hasData]);
          }

    }


    public function get_dues_list(Request $request)
    {
    	  $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

        if ($count_student>0) {

        	/*insert every student id in student_ids array*/
        	$student_ids = array();
        	for ($i=0; $i <$count_student ; $i++) {
        		$id = (int)($sibling_students[$i]->student_id);
        		array_push($student_ids,$id);
        	}

        	/*retrieving data*/
        	$students = DB::table('students')
           				 ->select('id','student_id')
           				 ->whereIn('id', $student_ids)
            			 ->get();

          $search_student_id = $request->student_id;

          $dues_list = DB::table("invoice")
                                ->join('school_infos','invoice.school_id','school_infos.id')
                                ->join('students','invoice.student_id','students.id')
                                ->select('invoice.*','school_infos.school_name','students.student_id as full_student_id')
                                ->where('invoice.student_id',$search_student_id)
                                ->where('invoice.status',0)
                                ->paginate(30);
            $hasData=1;
            //dd($dues_list);
            return view('backend/gurdian_panel/accounts/dues_list')->with(['dues_list' => $dues_list,'students'=>$students,'hasData'=>$hasData]);


          }

          else{
            $students = DB::table('students')
                    ->select('id','student_id')
                    ->where('id', 0)
                    ->get();
            $hasData=0;
            return view('backend/gurdian_panel/accounts/dues_list')->with(['students'=>$students,'hasData'=>$hasData]);
          }

    }

    public function get_studentwise_list(Request $request)
    {
        $current_user_id = Auth::user()->id;
$std=$request->student_id;
        $hasData=1;
        $sql = "select a.*, b.*, a.student_id as stdid
                from students as a
                inner join invoice as b
                inner join siblings as c
                on
                a.id = b.student_id and
                a.id = c.student_id and
                c.user_id = $current_user_id and
                b.status != 2";
        $invoices = DB::select(DB::raw($sql));
        //dd($invoices);
        return view("backend/gurdian_panel/accounts/payonline")->with(["invoices" => $invoices, 'hasData'=>$hasData]);


    }


    public function goPaymentList()
    {
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);


         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();

            $size = count($students);
            $student_full_id = array();
            for ($j=0; $j <$size; $j++) {
              $id =(int)$students[$j]->id;
              array_push($student_full_id,$id);
            }

//print_r($student_full_id);

            //echo $student_full_id[1];exit;

             $payment_list = DB::table("invoice")
                 ->join('school_infos','invoice.school_id','school_infos.id')
                 ->join('students','invoice.student_id','students.id')
                 ->select('invoice.*','school_infos.school_name','students.student_id as full_student_id')
                 ->whereIn('invoice.student_id',$student_ids)
                 ->where('invoice.status','!=',0)
                 ->where('invoice.status','!=',2)
                 ->paginate(30);
            //dd($payment_list);
           $hasData = 1;
           return view('backend/gurdian_panel/accounts/payment_list')->with(['payment_list' => $payment_list,'students'=>$students,'hasData'=>$hasData]);
        }
        else{
            $hasData = 0;
            $students = array();
            return view('backend/gurdian_panel/accounts/payment_list')->with(['students'=>$students,'hasData'=>$hasData]);
        }
    }



    public function paymentList(Request $request)
    {
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);


         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();
            $search_student_id = $request->student_id;
             $payment_list = DB::table('transaction_lists')
                          ->join('invoice', 'transaction_lists.invoice_no', '=', 'invoice.invoice_no')
                 ->join('students', 'students.id', '=', 'transaction_lists.student_id')
                 ->select('transaction_lists.*','students.student_id as student_id')
                          ->where('invoice.status',1)
                          ->where('students.student_id', $search_student_id)
                          ->paginate(30);
            //dd($payment_list);
           $hasData = 1;
           return view('backend/gurdian_panel/accounts/payment_list')->with(['payment_list' => $payment_list,'students'=>$students,'hasData'=>$hasData]);
        }
    }

    public function goPayOnline()
    {
        $current_user_id = Auth::user()->id;

        $guardian_mobile_number = Auth::user()->mobile_number;
        $students = DB::select(DB::raw("SELECT sgi.id,sgi.student_id,s.student_id as full_student_id
                                        FROM student_guardian_infos as sgi
                                        INNER JOIN students as s
                                        ON sgi.student_id = s.id
                                        WHERE sgi.guardian_contact_no=$guardian_mobile_number"));
        //dd($guardian_students);
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

        if ($count_student>0) {

            /*insert every student id in student_ids array*/
            $student_ids = array();
            for ($i = 0; $i < $count_student; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids, $id);
            }

            /*retrieving data*/
            $studentss = DB::table('students')
                ->select('id', 'student_id')
                ->whereIn('id', $student_ids)
                ->get();

            $invoices = DB::table("invoice")
                ->join('school_infos', 'invoice.school_id', 'school_infos.id')
                ->join('students', 'invoice.student_id', 'students.id')
                ->select('invoice.*', 'school_infos.school_name', 'students.student_id as full_student_id','students.name')
                ->whereIn('invoice.student_id', $student_ids)
                ->where('invoice.status', '!=', 2)
                ->paginate(30);
        }
        $hasData=1;
        $search=0;

        //dd($invoices);
        return view("backend/gurdian_panel/accounts/payonline")->with(["invoices" => $invoices, 'hasData'=>$hasData,'students'=>$students,'search'=>$search]);
    }

    public function paySearch(Request $request)
    {
        $current_user_id = Auth::user()->id;

        $guardian_mobile_number = Auth::user()->mobile_number;
        $students = DB::select(DB::raw("SELECT sgi.id,sgi.student_id,s.student_id as full_student_id
                                        FROM student_guardian_infos as sgi
                                        INNER JOIN students as s
                                        ON sgi.student_id = s.id
                                        WHERE sgi.guardian_contact_no=$guardian_mobile_number"));
        $student_id=$request->student_id;
        $hasData=1;
        $sql = "select a.*, b.*, a.student_id as full_student_id
                from students as a
                inner join invoice as b
                on
                a.id = b.student_id and
                b.status != 2 and a.student_id=$student_id";
        $invoices = DB::select(DB::raw($sql));
        $search=1;
        //dd($invoices);
        return view("backend/gurdian_panel/accounts/payonline")->with(["invoices" => $invoices, 'hasData'=>$hasData ,'students'=>$students,'search'=>$search]);
    }
    public function gofeessubhead(Request $request)
    {
        $class_id = $request->class_name;
        $school_id = $request->school_id ;
        $student_id = $request->student_id ;
        $year_id = $request->session_name;

        $subheads= DB::select("SELECT fees_sub_heads.fees_subhead_name, class_wise_fees.fees_id as id,class_wise_fees.amount
                          FROM fees_sub_heads
                          INNER JOIN class_wise_fees ON class_wise_fees.fees_id = fees_sub_heads.id
                          WHERE class_wise_fees.class_id = $class_id AND class_wise_fees.school_id = $school_id ");

       if (count($subheads)>0) {
            $hasData=1;
            return view('backend/gurdian_panel/payment_page')->with(['class_id' => $class_id,'student_id'=>$student_id,'school_id'=> $school_id,'year_id'=>$year_id,'subheads'=>$subheads,'hasData'=>$hasData]);
       }


       else{
            $hasData=0;
            $message = "Class wise fees head is not assigned yet of that school and class";
            return view('backend/gurdian_panel/payment_page')->with(['class_id' => $class_id,'student_id'=>$student_id,'school_id'=> $school_id,'year_id'=>$year_id,'message'=>$message,'hasData'=>$hasData]);
        }

    }


    public function store_payment(Request $request)
    {

        $total_amount = $request->total_payamount;

         /*Create invoice number*/
         $studentIDData = DB::select(DB::raw("SELECT id,student_id FROM students WHERE id=$request->student_id"));
         $s_id =$studentIDData[0]->student_id;
         $month = date('m');
         $year = date('y');
         $randomNumber = mt_rand(1111,9999);
         $invoice_no = "_".$s_id."_".$month.$year."_".$randomNumber;

         $shurjopay_service = new ShurjopayService();
         $tx_id = $shurjopay_service->generateTxId($invoice_no);


         /*insert data into fees_collection table*/
        if (!empty( $total_amount)) {
             foreach ($request->fees_id as $id) {
             $data=array();
             $amount = $request['given_amount_'.$id];
             $arr['student_id'] = $request->student_id;
             $arr['class_id'] = $request->class_id;
             $arr['year_id'] = $request->year_id;
             $arr['school_id'] = $request->school_id;
             $arr['fees_id']=$id;
             $arr['received_amount']=$amount;
             $arr['invoice_no']=$tx_id;
             $arr['payment_date'] = date("Y-m-d");
             $arr['created_by'] = Auth::user()->id;
             if (!empty($amount)) {
               FeesCollection::create($arr);
             }
         }

          $transaction_tbl = new TransactionList;
          $transaction_tbl->invoice_no = $tx_id ;
          $transaction_tbl->student_id = $request->student_id;
          $transaction_tbl->amount = $total_amount;
          $transaction_tbl->trn_date = date("Y-m-d");
          $transaction_tbl->save();

          // $success_route = route('');
          // $shurjopay_service->sendPayment(2, $success_route);
         //echo $tx_id;exit;
          $shurjopay_service->multiplePayment($total_amount);
        }

    }



    /*
    * Payment From Guardian Panel for Single Invoice
    * ----------------------------------------------------
    * @Method Name : pay-now
    * @Arguments   : invoice Object
    * @Return      :  Shurjopay Success/ Failed Response
    * @since       : 20 may 2021
    * @code        : salam@shurjomukhi.com.bd
    */

    public function payNow(Request $request)
    {
        $school_id = Auth::User()->school_id;

        $invoice_no = $request->hiddenInvoiceNo;
        $student_id = $request->hiddenStudentID;

        /*        $invoices = DB::table("invoice")
                    ->select('invoice.*')
                    ->where('invoice.student_id', $student_id)
                    ->where('invoice.invoice_no', $invoice_no);*/

        $invoices =DB::select(DB::raw("
            SELECT *
            FROM invoice
            WHERE student_id = $student_id
            AND invoice_no = $invoice_no"));

        //$amount = $request->hiddenPayableAmount;
/*        print_r($invoices[0]->total_amount);
        exit;*/
        $school_id=$invoices[0]->school_id;
        $gateway=DB::select(DB::raw("
            SELECT *
            FROM payment_gateways
            WHERE school_id = $school_id"));
 /*       $user=$gateway[0]->user;
        $pass=$gateway[0]->pass;
        $s_code=$gateway[0]->s_code;*/
        $amount = $invoices[0]->total_amount;

/*        $payInfo=array(
            'username'=>$user,
            'pass'=>$pass,
            's_code'=>$s_code,
            'amount'=>$amount,
            'invoice_no'=>$invoice_no,
            'student_id'=>$student_id
        );
/*        array_push($payInfo,$user);
        array_push($payInfo,$pass);
        array_push($payInfo,$s_code);
        array_push($payInfo,$amount);*/

/*        $shurjopay_service = new ShurjopayService();
       // $tx_id = $shurjopay_service->generateTxId();
        $shurjopay_service->singlePayment($payInfo);*/

        $info = array(
            'prefix' => "spay",
            'currency' => "BDT",
            'return_url' => "http://".$_SERVER['HTTP_HOST']."/paynow-response/".$invoice_no,
            'cancel_url' => "http://".$_SERVER['HTTP_HOST']."/paynow-response/".$invoice_no,
            'amount' => $amount,
            'order_id' => $invoice_no."_".$student_id,
            'discsount_amount' => 0,
            'disc_percent' => 0,
            'client_ip' => "http://127.0.0.1:8000",
            'customer_name' => "customer_name",
            'customer_phone' => "01427527",
            'email' => "email",
            'customer_address' => "customer_address",
            'customer_city' => "customer_city",
            'customer_state' => "customer_state",
            'customer_postcode' => "customer_postcode",
            'customer_country' => "customer_country",
        );
/*        MERCHANT_USERNAME =spaytest
MERCHANT_PASSWORD =JehPNXF58rXs*/
        $shurjopay_service = new ShurjopayController();
        return $shurjopay_service->checkout($info,$school_id);
    }

    public function payNowPartial(Request $request)
    {

        $invoice_no = $request->hidden_invoice_no;
        /*        $invoices = DB::table("invoice")
                    ->select('invoice.*')
                    ->where('invoice.student_id', $student_id)
                    ->where('invoice.invoice_no', $invoice_no);*/

        $invoices =DB::select(DB::raw("
            SELECT *
            FROM invoice
            WHERE invoice_no = $invoice_no"));
        $student_id=$invoices[0]->student_id;
        $school_id=$invoices[0]->school_id;
/*        $gateway=DB::select(DB::raw("
            SELECT *
            FROM payment_gateways
            WHERE school_id = $school_id"));
        $user=$gateway[0]->user;
        $pass=$gateway[0]->pass;
        $s_code=$gateway[0]->s_code;*/
        $amount=$request->amountPaid;
/*        $payInfo=array(
            'username'=>$user,
            'pass'=>$pass,
            's_code'=>$s_code,
            'amount'=>$amount,
            'invoice_no'=>$invoice_no,
            'student_id'=>$student_id
        );
        //$amount = $request->hiddenPayableAmount;
        /*  print_r($invoices[0]->total_amount);
          exit;*/
        //$amount = $invoices[0]->total_amount;
    /*    $shurjopay_service = new ShurjopayService();
        $tx_id = $shurjopay_service->generateTxId();
        $shurjopay_service->singlePayment($payInfo);*/

        $info = array(
            'prefix' => "spay",
            'currency' => "BDT",
            'return_url' => "http://".$_SERVER['HTTP_HOST']."/paynow-response/".$invoice_no."_p",
            'cancel_url' =>"http://".$_SERVER['HTTP_HOST']."/paynow-response/".$invoice_no."_p",
            'amount' => $amount,
            'order_id' => $invoice_no."_".$student_id,
            'discsount_amount' => 0,
            'disc_percent' => 0,
            'client_ip' => "http://127.0.0.1:8000",
            'customer_name' => "customer_name",
            'customer_phone' => "01427527",
            'email' => "email",
            'customer_address' => "customer_address",
            'customer_city' => "customer_city",
            'customer_state' => "customer_state",
            'customer_postcode' => "customer_postcode",
            'customer_country' => "customer_country",
        );
        /*        MERCHANT_USERNAME =spaytest
        MERCHANT_PASSWORD =JehPNXF58rXs*/
        $shurjopay_service = new ShurjopayController();
        return $shurjopay_service->checkout($info,$school_id);

    }


    /*
    * Payment From Guardian Panel for Multiple Invoice
    * ----------------------------------------------------
    * @Method Name : pay-now-in-total
    * @Arguments   : invoice Object
    * @Return      :  Shurjopay Success/ Failed Response
    * @since       : 20 may 2021
    * @code        : salam@shurjomukhi.com.bd
    */

    public function payNowInTotal(Request $request)
    {
        $school_id = Auth::User()->school_id;
        //$totalAmount = $request->hiddenTotalPayableAmount;
        $amounts = $request->hiddenTotalPayableAmounts;
        $invoice_nos = $request->hiddenTotalInvoiceNos;
        $student_ids = $request->hiddenTotalStudentIDs;

        $student_idsff=rtrim($student_ids, ',');
        $invoice_nosff=rtrim($invoice_nos, ',');
       $student_idsf=explode(",",$student_idsff);
        $invoice_nosf=explode(",",$invoice_nosff);
        $amountsf = rtrim($amounts, ',');

        /*print_r($student_ids);
                print_r($invoice_nos);
                exit();*/
 /*       $invoices =DB::select(DB::raw("
            SELECT SUM(total_amount) as total_amounts
            FROM invoice
            WHERE invoice_no IN $invoice_nos"));*/
/*        $invoices = DB::table("invoice")
            ->select('SUM(total_amount) as total_amounts')
            ->whereIN('invoice.student_id', $student_ids)
            ->whereIN('invoice.invoice_no', $invoice_nos);*/
/*        print_r($student_idsf);
        exit();*/
       $invoices =DB::select(DB::raw("
            SELECT *
            FROM invoice
            WHERE student_id = $student_idsf[0]"));
        $totalAmount = Invoice::whereIN('student_id',$student_idsf)->whereIn('invoice_no',$invoice_nosf)->sum('due');

        $student_id=$invoices[0]->student_id;
/*        $school_id=$invoices[0]->school_id;
        $gateway=DB::select(DB::raw("
            SELECT *
            FROM payment_gateways
            WHERE school_id = $school_id"));
        $user=$gateway[0]->user;
        $pass=$gateway[0]->pass;
        $s_code=$gateway[0]->s_code;
        $amount = $invoices[0]->total_amount;*/
/*        $payInfo=array(
            'username'=>$user,
            'pass'=>$pass,
            's_code'=>$s_code,
            'totalamount'=>$totalAmount,
            'amount'=>$amountsf,

            'invoice_no'=>$invoice_nosff,
            'student_id'=>$student_idsff
        );
/*        print_r($payInfo);
        exit();*/

        //$totalAmount = $invoices[0]->total_amounts;;
        /*$shurjopay_service = new ShurjopayService();
       $tx_id = $shurjopay_service->generateTxId();*/

        //$invoice=

       /* $shurjopay_service->multiplePayment($payInfo);*/


        $info = array(
            'prefix' => "spay",
            'currency' => "BDT",
            'return_url' => "http://".$_SERVER['HTTP_HOST']."/paynowResponse4Multiple/".$invoice_nosff,
            'cancel_url' => "http://".$_SERVER['HTTP_HOST']."/paynowResponse4Multiple/".$invoice_nosff,
            'amount' => $totalAmount,
            'order_id' => $invoice_nosff."_".$student_id,
            'discsount_amount' => 0,
            'disc_percent' => 0,
            'client_ip' => "http://127.0.0.1:8000",
            'customer_name' => "customer_name",
            'customer_phone' => "01427527",
            'email' => "email",
            'customer_address' => "customer_address",
            'customer_city' => "customer_city",
            'customer_state' => "customer_state",
            'customer_postcode' => "customer_postcode",
            'customer_country' => "customer_country",
        );
        /*        MERCHANT_USERNAME =spaytest
        MERCHANT_PASSWORD =JehPNXF58rXs*/
        $shurjopay_service = new ShurjopayController();
        return $shurjopay_service->checkout($info,$school_id);
    }


    public function response4Single($inv=null, Request $request)
    {
        $school_id = Auth::User()->school_id;
        $order_id = $request->order_id;

        $partial = 0;
        $shurjopay_service = new ShurjopayController();

         $response = $shurjopay_service->return($order_id,$school_id);
        $arr = json_decode($response);

        if(!empty($arr[0]->sp_code))
        {
            $arr = $arr[0];
            $status = $arr->sp_code;
            $order_id = $arr->customer_order_id;
        }else{
            $status=0;
        }


        if ($status == 1000) {
       /*     echo 'hello';
            exit();*/
            $stdid = explode('_', $order_id);
            $stdid = $stdid[1];
            $invoice_no = explode('_', $inv);
            $partial = 0;

            if (count($invoice_no) > 1) {
                $partial = 1;
                $invoice_no = $invoice_no[0];
            }
            else{
                $invoice_no = $inv;
            }

            if ($partial == 1) {
                $trxid = $arr->invoice_no;
                $bank_tx_id = $arr->bank_trx_id;
                $amount = $arr->amount;
                $Tdue = DB::select(DB::raw("
                    SELECT *
                    FROM invoice
                    WHERE invoice_no= $invoice_no"));
                $totalDue = $Tdue[0]->due;
                $due = $totalDue - $amount;

                if ($due < 0) {
                    session()->flash("error", "Please enter amount equal or less than due");


                }
                else {
                    $transactions = new TransactionList;
                    $transactions->invoice_no = $invoice_no;
                    $transactions->student_id = $stdid;
                    $transactions->amount = $amount;
                    $transactions->trx_id = $trxid;
                    $transactions->bank_trx_id = $bank_tx_id;
                    $transactions->trn_date = now();

                    $transactions->save();

                    if ($due == 0) {
                        $sql = "update invoice set status = 1 where invoice_no = '$invoice_no'";
                        DB::statement($sql);
                    } else {
                        $sql = "update invoice set status = 3 where invoice_no = '$invoice_no'";
                        DB::statement($sql);
                    }

                    $sql = "update invoice set due = $due where invoice_no = '$invoice_no'";
                    DB::statement($sql);
                    //invmail($invoice_no)
                    $school_id = Auth::User()->school_id;

                    $data = $students = DB::select(DB::raw("
                        SELECT i.invoice_no invoice,i.due due,i.student_id as studentid, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                               i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                               s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
                        FROM invoice as i
                        INNER JOIN students as s ON s.id = i.student_id
                        INNER JOIN student_academics as sa ON sa.student_id = s.id
                        INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
                        INNER JOIN class_infos as c ON c.id = cw.class_id
                        INNER JOIN school_infos as si ON si.id = cw.school_id
                        INNER JOIN sections  as se  ON sa.section_id = se.id
                        WHERE i.invoice_no= $invoice_no AND cw.school_id = $school_id"));

                    $pay = $data[0]->payid;
                    $feesheeads = DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
                                                FROM class_wise_fees c
                                                INNER JOIN fees_heads f ON f.id = c.fees_id
                                                INNER JOIN invoice i ON  i.payment_id = c.payment_id
                                                WHERE c.payment_id=$pay"));
                    $studentid = $data[0]->studentid;
                    $email = DB::select(DB::raw("SELECT DISTINCT email_address
                                                FROM students
                                                WHERE id=$studentid"));
                    $email = $email[0]->email_address;

                    $data = array('data' => $data, 'feesheeads' => $feesheeads, 'trx_info' => $transactions);
                    $pdf = PDF::loadView('backend/invoice/pdf', $data);

  /*                  Mail::send('backend/invoice/mail', $data, function ($message) use ($data, $pdf, $email) {
                        $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                        $message->from('no-reply@abbankems.com', 'Tution Fees');
                    });*/
       /*             print_r($data);
                    exit();*/
                    return redirect("invoicer_view/" . $invoice_no);
                }

            } else {

                $trxidf = $arr->invoice_no;
                $bank_tx_idf = $arr->bank_trx_id;
                $amountf = $arr->amount;


                $transactions = new TransactionList;
                $transactions->invoice_no = $invoice_no;
                $transactions->student_id = $stdid;
                $transactions->amount = $amountf;
                $transactions->trx_id = $trxidf;
                $transactions->bank_trx_id = $bank_tx_idf;
                $transactions->trn_date = now();

                $transactions->save();




                $sql = "update invoice set status = 1 where invoice_no = '$invoice_no'";
                DB::statement($sql);
                $sql = "update invoice set due = 0 where invoice_no = '$invoice_no'";
                DB::statement($sql);
                //invmail($invoice_no)
                $school_id = Auth::User()->school_id;

                $data = $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id as studentid, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
           INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id

            WHERE i.invoice_no= $invoice_no AND cw.school_id = $school_id"));
                $pay = $data[0]->payid;
                $feesheeads = DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));
                $studentid = $data[0]->studentid;
                $email = DB::select(DB::raw("SELECT DISTINCT email_address
        FROM students
        WHERE id=$studentid"));
                $email = $email[0]->email_address;

                $data = array('data' => $data, 'feesheeads' => $feesheeads, 'trx_info' => $transactions);
                $pdf = PDF::loadView('backend/invoice/pdf', $data);
/*                Mail::send('backend/invoice/mail', $data, function ($message) use ($data, $pdf, $email) {
                    $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                    $message->from('no-reply@abbankems.com', 'Tution Fees');
                });*/

                return redirect("invoicer_view/" . $invoice_no);
            }
        }
        else{
            echo 'payment cancelled';
        }
    }









     public function response4Multiple($inv=null, Request $request)
     {
         $order_id = $request->order_id;
         $school_id = Auth::User()->school_id;
        $invoice_no = $inv;
         $shurjopay_service = new ShurjopayController();

         $response = $shurjopay_service->return($order_id,$school_id);
         $arr = json_decode($response);

         if(!empty($arr[0]->sp_code))
         {
             $arr = $arr[0];
             $status = $arr->sp_code;
             $order_id = $arr->customer_order_id;
             $stdid = explode('_', $order_id);
             $stdid = $stdid[1];
             $trxid = $arr->invoice_no;
             $bank_tx_id = $arr->bank_trx_id;
             /*      $trxid = $request->trxid;
                   $bank_tx_id = $request->bank_tx_id;
                   $amount = $request->amount;*/
         }else{
             $status=0;
         }


            if($status==1000)
            {
                // invoice
                $invoice_no = explode(",", $invoice_no);

                /*     $amount = explode(",", $amount);
                    $stdid = explode(",", $stdid);*/

                // Length
                $length = count($invoice_no);

                for($i = 0; $i < $length; $i++){
                    $invoice = Invoice::where('invoice_no',$invoice_no[$i])->get();
                    $amount=$invoice[0]->due;
                    /*            print_r($amount);
                                print_r($stdid);
                                exit();*/

                    $transactions= new TransactionList;
                    $transactions->invoice_no = $invoice_no[$i];
                    $transactions->student_id = $stdid;
                    $transactions->amount = $amount;
                    $transactions->trx_id = $trxid;
                    $transactions->bank_trx_id = $bank_tx_id;
                    $transactions->trn_date = now();

                    $transactions->save();
                    $sql = "update invoice set status = 1 where invoice_no = '$invoice_no[$i]'";
                    DB::statement($sql);
                    $sql = "update invoice set due = 0 where invoice_no = '$invoice_no[$i]'";
                    DB::statement($sql);
                    /*            $school_id = Auth::User()->school_id;

                                $data= $students = DB::select(DB::raw("
                                SELECT i.invoice_no invoice,i.due due,i.student_id as studentid , i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                                       i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                                       s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
                                FROM invoice as i
                                INNER JOIN students as s ON s.id = i.student_id
                                INNER JOIN student_academics as sa ON sa.student_id = s.id
                               INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
                                INNER JOIN class_infos as c ON c.id = cw.class_id
                                INNER JOIN school_infos as si ON si.id = cw.school_id
                                INNER JOIN sections  as se  ON sa.section_id = se.id

                                WHERE i.invoice_no= $invoice_no[$i] AND cw.school_id = $school_id"));

                                $pay=$data[0]->payid;
                                $studentid=$data[0]->studentid;
                                $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
                            FROM class_wise_fees c
                            INNER JOIN fees_heads f ON f.id = c.fees_id
                            INNER JOIN invoice i ON  i.payment_id = c.payment_id
                            WHERE c.payment_id=$pay"));
                                $email=DB::select(DB::raw("SELECT DISTINCT email_address
                            FROM students
                            WHERE id=$studentid"));
                                $email=$email[0]->email_address;

                            $data=array('data'=>$data,'feesheeads' => $feesheeads,'trx_info'=>$transactions);
                                $pdf = PDF::loadView('backend/invoice/pdf', $data);
                                Mail::send('backend/invoice/mail', $data, function($message)use($data, $pdf, $email) {
                                    $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                                    $message->from('no-reply@abbankems.com','Tution Fees');
                                });*/
                }

                /*        for($i = 0; $i < $length; $i++){
                          $sql = "update invoice set status = 1 where invoice_no = '$invoice_no[$i]'";
                          DB::statement($sql);
                        }*/
                $msg="Transaction is successfully done";

            }
            else{
                $msg="Transaction is failed";
            }


         print_r($msg) ;

       return redirect("pay-online");
     }


     public function response4MultiplePayment(Request $request, $stdid)
     {
        $invoice_no = $request->inv;
        $trxid = $request->trxid;
        $bank_tx_id = $request->bank_tx_id;
        $amount = $request->amount;

        // $sql = "insert into transaction_lists(invoice_no, student_id, amount, order_id, trx_id, bank_trx_id,
        //         return_code, status, method, trn_date)
        //         values('$invoice_no', '$stdid', '', '', '$trxid', '$bank_tx_id', '', '1', '', now())";

        $transactions= new TransactionList;
        $transactions->invoice_no = $invoice_no;
        $transactions->student_id = $stdid;
        $transactions->amount = $amount;
        $transactions->trx_id = $trxid;
        $transactions->bank_trx_id = $bank_tx_id;
        $transactions->trn_date = now();

        $transactions->save();


        $sql = "update invoice set status = 1 where invoice_no = '$invoice_no'";
        DB::statement($sql);


       return redirect("invoicer_view/" . $invoice_no);
     }


    // End pay-now-in-total();




    public function initiate_payonline(Request $request)
    {
       $r_amount = $request->amount;
       $r_school_id = $request->school_id;
       $r_student_id = $request->student_id;

       $amount_as_str = $r_amount;
       $remove_leading_zero = ltrim($amount_as_str, "0");
       $amount =(int)$remove_leading_zero;
       if($amount>=1 && !empty($r_school_id) && !empty($r_student_id)){
           $shurjopay_service = new ShurjopayService();
           $tx_id = $shurjopay_service->generateTxId();
           $data = new AgentPanel;
           $data->school_id = $r_school_id;
           $data->student_id = $r_student_id;
           $data->amount = $amount;
           $data->trx_id = $tx_id;
           $data->save();
           //$success_route = route('payonline');
           //$shurjopay_service->sendPayment($amount,$success_route);
           $shurjopay_service->sendPayment($amount);
       }

       else{
          session()->flash("error", "Student ID missing");
          return back();
       }
    }


    public function studentLedgerGurdian()
    {
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();

            $student_ledger_data = DB::table("invoice")
                                ->join('school_infos','invoice.school_id','school_infos.id')
                                ->join('students','invoice.student_id','students.id')
                                ->select('invoice.*','school_infos.school_name','students.student_id as full_student_id')
                                ->whereIn('invoice.student_id',$student_ids)
                                ->where('invoice.status',1)
                                ->paginate(30);

            //dd($student_ledger_data);
           $hasData=1;
           return view('backend/gurdian_panel/reports/student_ledger')->with(['students'=>$students,'student_ledger_data'=>$student_ledger_data,'hasData'=>$hasData]);
        }
        else{
           $hasData=0;
           $students=array();
           return view('backend/gurdian_panel/reports/student_ledger')->with(['students'=>$students,'hasData'=>$hasData]);
        }

    }


    public function searchStudentledger(Request $request)
    {
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();


            $search_student_id = $request->student_id;

            $student_ledger_data = DB::table("invoice")
                                ->join('school_infos','invoice.school_id','school_infos.id')
                                ->join('students','invoice.student_id','students.id')
                                ->select('invoice.*','school_infos.school_name','students.student_id as full_student_id')
                                ->where('invoice.student_id',$search_student_id)
                                ->where('invoice.status',1)
                                ->paginate(30);
            //dd($student_ledger_data);
            $hasData=1;
           return view('backend/gurdian_panel/reports/student_ledger')->with(['students'=>$students,'student_ledger_data'=>$student_ledger_data,'hasData'=>$hasData]);
        }
         }



     public function goWaiverReport()
    {
      $guardian_mobile_number = Auth::user()->mobile_number;
      $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
      $count_student = count($sibling_students);

         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();


           $waiverReport = DB::table('fees_waivers')
            ->join('sessions', 'fees_waivers.year_id', '=', 'sessions.name')
            ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
            ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
            ->join('students', 'fees_waivers.student_id', '=', 'students.id')
            ->select('fees_waivers.*', 'sessions.name as year_name', 'class_infos.name as class_name','fees_heads.fees_head_name','students.name as student_name','students.student_id as full_student_id')
            ->whereIn('fees_waivers.student_id', $student_ids)
            ->paginate(10);

           //dd($waiverReport);
           $hasData=1;
           return view('backend/gurdian_panel/reports/waiver_report')->with(['students'=>$students,'waiverReport'=>$waiverReport,'hasData'=>$hasData]);
        }

        else{
            $students=array();
            $hasData=0;
            return view('backend/gurdian_panel/reports/waiver_report')->with(['students'=>$students,'hasData'=>$hasData]);
        }
    }


    public function generateWaiverReport(Request $request)
    {
        $search_student_id = $request->student_id;
        $guardian_mobile_number = Auth::user()->mobile_number;
        $sibling_students = DB::select(DB::raw("SELECT * FROM student_guardian_infos WHERE guardian_contact_no=$guardian_mobile_number"));
        $count_student = count($sibling_students);

         if ($count_student>0) {

            /*insert every student_id in student_ids array*/
            $student_ids = array();
            for ($i=0; $i <$count_student ; $i++) {
                $id = (int)($sibling_students[$i]->student_id);
                array_push($student_ids,$id);
            }

            /*retrieving data*/
            $students = DB::table('students')
                         ->select('id','student_id')
                         ->whereIn('id', $student_ids)
                         ->get();


           $waiverReport = DB::table('fees_waivers')
            ->join('sessions', 'fees_waivers.year_id', '=', 'sessions.name')
            ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
            ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
            ->join('students', 'fees_waivers.student_id', '=', 'students.id')
            ->select('fees_waivers.*', 'sessions.name as year_name', 'class_infos.name as class_name','fees_heads.fees_head_name','students.name as student_name','students.student_id as full_student_id')
            ->where('fees_waivers.student_id', $search_student_id)
            ->paginate(30);

           //dd($waiverReport);
           $hasData=1;
           return view('backend/gurdian_panel/reports/waiver_report')->with(['students'=>$students,'waiverReport'=>$waiverReport,'hasData'=>$hasData]);
        }

        else{
            $students=array();
            $hasData=0;
            return view('backend/gurdian_panel/reports/waiver_report')->with(['students'=>$students,'hasData'=>$hasData]);
        }
    }



    public function manageProfile(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $name = $request->name;

        $user->name = $name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->save();

        $up = UserProfile::select('user_id','profile_img')->where('user_id',$user_id)->first();

        if($up==null){
            $user_profile = new UserProfile;
            $user_profile->user_id = $user_id;
            $user_profile->full_name = $name;
            $user_profile->created_by = $user_id;
            if($request->profile_img != null){
                $img_name = $this->upload($request->profile_img);
                $user_profile->profile_img = $img_name;
            }
            $user_profile->save();
        }

        if($up!= null){
            $image = $up->profile_img;

            if($image!=null && $request->profile_img!= null){
                $img_name = $this->upload($request->profile_img);
                $update_up = DB::select(DB::raw("UPDATE user_profiles SET full_name='$name',profile_img='$img_name',updated_by=$user_id where user_id=$user_id"));
            }
            if($image==null && $request->profile_img!= null){
                $img_name = $this->upload($request->profile_img);
                $update_up = DB::select(DB::raw("UPDATE user_profiles SET full_name='$name',profile_img='$img_name',updated_by=$user_id where user_id=$user_id"));
            }
            else{
                $update_up = DB::select(DB::raw("UPDATE user_profiles SET full_name='$name', updated_by=$user_id where user_id=$user_id"));
            }
        }

        return back();
    }



    public function getSchoolWiseStudents($id)
    {
        $students = DB::table('student_academics')
            ->join('students', 'student_academics.student_id', '=', 'students.id')
            ->select('student_academics.*', 'students.student_id as real_id')
            ->where('student_academics.school_id','=',$id)
            ->get();
        if (count($students)>0) {
            return response()->json(['hasData' =>'1', 'studentData' => $students]);
        }
        else{
            return response()->json(['hasData' =>'2']);
        }
        //return $students;
    }



    private function upload($file)
    {
        if($file){
            $photo = $file;
            $image = Image::make($photo);
            $image->fit(80,80);
            $thumbnail_filename = time()."_". rand(100000, 999999).".".$photo->getClientOriginalExtension();
            $image->save('storage/profile_img/'. $thumbnail_filename);
            return $thumbnail_filename;
        }
    }









}
