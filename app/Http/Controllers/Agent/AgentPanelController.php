<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\agentpanel\AgentPanel;
use App\Models\FeesCollection;
use Illuminate\Http\Request;
use smasif\ShurjopayLaravelPackage\ShurjopayService;
use DB;
use Auth;

use App\Models\TransactionList;

class AgentPanelController extends Controller
{
   
    public function index()
    {
          $agentID = Auth::user()->id;
          $schools = DB::select(DB::raw("SELECT DISTINCT id,school_name  FROM school_infos ORDER BY school_name ASC"));
          $class_names = DB::select(DB::raw("SELECT id,name  FROM class_infos"));
          $sessions = DB::select(DB::raw("SELECT id,name  FROM sessions"));

          $transactions= DB::table('transaction_lists')
            ->join('students', 'transaction_lists.student_id', '=', 'students.id')
            ->select('transaction_lists.*', 'students.student_id as s_id')
            ->where('transaction_lists.user_id',$agentID)
            ->paginate(50);
        
        if(count($transactions)>0) {
          $hasData = 1;
          return view('backend/agent_panel/payments')->with(['schools' => $schools,'transactions'=> $transactions,'class_names'=>$class_names,'hasData'=>$hasData,'sessions'=>$sessions]); 
        }

        else{
          $hasData = 0;
          return view('backend/agent_panel/payments')->with(['schools' => $schools,'class_names'=>$class_names,'hasData'=>$hasData,'sessions'=>$sessions]); 
        }
       
    }



    public function getSchoolWiseStudents($school_id){
        $students = DB::select(DB::raw("
            SELECT sa.id, sa.student_id, sa.school_id, students.student_id
            FROM student_academics as sa 
            INNER JOIN students
            ON students.id = sa.student_id
            WHERE school_id = $school_id"));

        if (count($students)>0) {
          return response()->json(['status' => '1', 'students' => $students]);
        }
        else{
          return response()->json(['status' => '0', 'message' => "No Students Available"]);
        }
    }



    public function FeesHeadWisePayment(Request $request)
    {
        $class_id = $request->class_name;
        $school_id = $request->school_id ;
        $student_id = $request->student_id ;
        $year_id = $request->session_name;

        $subheads= DB::select("SELECT fees_sub_heads.fees_subhead_name, class_wise_fees.fees_id as id,class_wise_fees.amount
                          FROM fees_sub_heads
                          INNER JOIN class_wise_fees ON class_wise_fees.fees_id = fees_sub_heads.id
                          WHERE class_wise_fees.class_id = $class_id AND class_wise_fees.school_id = $school_id");
       
       if (count($subheads)>0) {
            $hasData=1;
            return view('backend/agent_panel/payment_page_agent')->with(['class_id' => $class_id,'student_id'=>$student_id,'school_id'=> $school_id,'year_id'=>$year_id,'subheads'=>$subheads,'hasData'=>$hasData]); 
       }

       
       else{
            $hasData=0;
            $message = "Class wise fees head is not assigned yet of that school and class";
            return view('backend/agent_panel/payment_page_agent')->with(['class_id' => $class_id,'student_id'=>$student_id,'school_id'=> $school_id,'year_id'=>$year_id,'message'=>$message,'hasData'=>$hasData]); 
        }

    }



    public function store_payment_agent(Request $request){
       
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
          $transaction_tbl->user_id = Auth::user()->id;
          $transaction_tbl->save();

          //$success_route = route('payment_response'); 
          //$shurjopay_service->sendPayment($total_amount, $success_route);

         $shurjopay_service->sendPayment($total_amount);
        }

    }



public function payment_response(Request $request)
{
   return view('backend/agent_panel/payment-response'); 
}


    public function initiate_payment(Request $request)
    {
       
       $r_amount = $request->amount;
       $r_school_id = $request->school_id;
       $r_student_id = $request->student_id;

       $amount_as_str = $r_amount;
       $remove_leading_zero = ltrim($amount_as_str, "0");
       $amount =(int)$remove_leading_zero;
       if($amount>=1 && !empty($r_school_id) && !empty($r_student_id)){
           $shurjopay_service = new ShurjopayService();
           //dd($shurjopay_service);exit;

           $tx_id = $shurjopay_service->generateTxId();
           $data = new AgentPanel;
           $data->school_id = $r_school_id;
           $data->student_id = $r_student_id;
           $data->amount = $amount;
           $data->trx_id = $tx_id;
           $data->save(); 
           $shurjopay_service->sendPayment($amount);
       }

       else{
          session()->flash("error", "Student ID missing");
          return back();
       }
    }


    public function goStudentLedger()
    {

        $schools= DB::select( DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
         $sl = DB::table('fees_collections')
                      ->join('fees_heads', 'fees_collections.fees_id', '=', 'fees_heads.id')
                      ->join('students', 'fees_collections.student_id', '=', 'students.id')
                      ->select('fees_collections.*', 'fees_heads.fees_head_name', 'students.student_id')
                      ->whereBetween('payment_date', ['0000-00-00', '0000-00-00'])
                      ->where('fees_collections.student_id',0)
                      ->paginate(11); 
       // dd($sl);
                    
       $schools= DB::select( DB::raw("SELECT DISTINCT id,school_name FROM  school_infos")); 
       return view('backend/agent_panel/student_ledger')->with(['schools' => $schools,'sl'=>$sl]);
    }

    public function searchStudentLedger(Request $request)
    {
      $student_id=$request->student_id;
      $date_range = $request->sdate;
      

      if (!empty($student_id) && !empty($date_range)) {
      $part= explode("to", $date_range);
      $start_date = trim($part[0]," ");
      $end_date = trim($part[1]," ");

      $sl = DB::table('fees_collections')
                      ->join('fees_heads', 'fees_collections.fees_id', '=', 'fees_heads.id')
                      ->join('students', 'fees_collections.student_id', '=', 'students.id')
                      ->select('fees_collections.*', 'fees_heads.fees_head_name', 'students.student_id')
                      ->whereBetween('payment_date', [$start_date, $end_date])
                      ->where('fees_collections.student_id',$student_id)
                      ->paginate(11);

       $schools= DB::select( DB::raw("SELECT DISTINCT id,school_name FROM  school_infos")); 
       return view('backend/agent_panel/student_ledger')->with(['schools' => $schools,'sl'=>$sl]);
     }
     
    
    }

    public function goTodaysCollection()
    {
      $today = Date('Y-m-d');
      $agent_id =Auth::user()->id;

      $todaysCollection = DB::table('fees_collections')
                      ->join('fees_heads', 'fees_collections.fees_id', '=', 'fees_heads.id')
                      ->join('students', 'fees_collections.student_id', '=', 'students.id')
                      ->join('class_infos', 'fees_collections.class_id', '=', 'class_infos.id')
                      ->join('sessions', 'fees_collections.year_id', '=', 'sessions.id')
                      ->select('fees_collections.*', 'fees_heads.fees_head_name', 'students.student_id','class_infos.name','sessions.year')
                      ->where('fees_collections.payment_date',$today)
                      ->where('fees_collections.created_by',$agent_id)
                      ->paginate(11);
      
      
       return view('backend/agent_panel/todays_collection')->with(['todaysCollection' => $todaysCollection]);

    }


     public function goCollectionSummery()
    {
        $data = FeesCollection::where('created_by',0)
                            ->whereBetween('payment_date', ['0000-00-00', '0000-00-00'])
                            ->select([DB::raw("SUM(received_amount) as day_sum"), DB::raw("payment_date")])
                            ->groupBy('payment_date')
                            ->paginate(10);
      return view('backend/agent_panel/collection_summery')->with(['data'=>$data]);
    }

    public function searchCollectionSummery(Request $request)
    {
      $date_range = $request->sdate;
      $agent_id =Auth::user()->id;
      if (!empty($date_range)) {
      $part= explode("to", $date_range);
      $start_date = trim($part[0]," ");
      $end_date = trim($part[1]," ");

      $data = FeesCollection::where('created_by',$agent_id)
                            ->whereBetween('payment_date', [$start_date, $end_date])
                            ->select([DB::raw("SUM(received_amount) as day_sum"), DB::raw("payment_date")])
                            ->groupBy('payment_date')
                            ->paginate(10);
      return view('backend/agent_panel/collection_summery')->with(['data'=>$data]);
     }
       
    }


    
}
