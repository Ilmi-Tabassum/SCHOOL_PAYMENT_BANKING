<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassWiseFees;
use App\Models\FeesSubHead;
use App\Models\FeesWaiver;
use App\Models\Student;
use DB;
use Auth;

class GenerateInvoiceController extends Controller
{
     public function index()
    {
        $scid=Auth::user()->school_id;
        $years = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
/*        $particulars = DB::select( DB::raw("SELECT DISTINCT fees_heads.id,fees_head_name
FROM fees_heads
JOIN assign_particulars ON  assign_particulars.fees_head_id=fees_heads.id
WHERE fees_heads.status = 1 AND assign_particulars.school_id = $scid"));*/

        $particulars = DB::select(DB::raw("
                        SELECT t1.fees_head_id, t1.school_id,t1.year_id,t1.class_id,t1.amount,t2.fees_head_name,t2.id
                        FROM assign_particulars AS t1

                        INNER JOIN fees_heads AS t2
                        ON t1.fees_head_id = t2.id

                        WHERE t1.school_id = $scid
                        "));

        return view('backend/generate_invoice')->with(['particulars'=>$particulars,'years'=>$years,'classes'=>$classes]);
    }
    public function store(Request $request)
    {

        $sms = $request->input('isSendSMS');
        $email = $request->input('isSendEmail');
        $partial = $request->input('isPartial');
        if (isset($sms)) {
          $isSMS = 1;
        }
        else{
            $isSMS = 0;
        }
        if (isset($email)) {
            $isEmail = 1;
        }else{
            $isEmail = 0;
        }
        if (isset($partial)) {
            $isPartial = 1;
        }else{
            $isPartial = 0;
        }




        $school_id = Auth::user()->school_id;
        $given_head_sum= (int)$request->total_sum;
        $class_id = (int)$request->class;
        $session_id = (int)$request->year;
        $user_school_id = Auth::user()->school_id;
        $years = $request->year;
        $m_num = $request->month;


        //payment id insertion in classwisefees
            foreach($request->fees_id as $id) {

            /*    $check = ClassWiseFees::where('school_id',$school_id)
                        ->where('class_id',$request->class)
                        ->where('year_id',$request->year)
                        ->where('fees_id',$id)
                        ->first();

                if(!empty($check)){
                    $total=0;
                    $data=array();
                    $amount = $request['amount_'.$id];
                    $data['amount']=$amount;
                    $total+=$data['amount'];
                    $data['updated_by'] = Auth::user()->id;
                    if(!empty($amount)){
                        $check->update($data);
                    }
                 }

                else{*/

                    $data=array();
                    $monthno=$request->month;
                    $classno=$request->class;
                    //dd($monthno);
                    $payment_id = $school_id.date('Y').$classno.$monthno;
                    //dd($payment_id);
                    $amount = $request['amount_'.$id];
                    $arr['school_id'] = $school_id;
                    $arr['class_id'] = $request->class;
                    $arr['year_id'] = $request->year;
                    $arr['fees_id']=$id;
                    $arr['amount']=$amount;
                    $arr['payment_id']=$payment_id;
                    $arr['created_by'] = Auth::user()->id;
                    if (!empty($amount)) {
                       ClassWiseFees::create($arr);

                    }
                /*}*/
            }
        //end of payment id insertion in classwisefees

        //Save data in invoice table;



        //Finding total student of the targeted class
            $students = DB::select(DB::raw("SELECT* FROM student_academics WHERE school_id=$school_id AND class_id=$class_id AND session_id=$session_id"));
            $total_students = count($students);
        //End of Finding total student of the targeted class

        if($total_students>0){

           /* $payment_id = ClassWiseFees::where('school_id',$school_id)
                        ->where('class_id',$class_id)
                        ->where('year_id',$session_id)
                        ->first();*/
            $payment_id = $school_id.date('Y').$classno.$monthno;



//invoice generation loop
            for ($i=0; $i <$total_students ; $i++) {
//single student invoice generation

                //invoice no generation
                     $s_id =$students[$i]->student_id;
/*                print_r($s_id);
                exit();*/
                     $five_digit = Student::find($s_id);
                     $ss_id = $five_digit->student_id;
                     $lastFiveDigit = substr ($ss_id, -5);
                     $invoice_no = $request->month.Date('Y').$students[$i]->student_id.$lastFiveDigit;
                 // End of invoice no generation





                //finding fined amount
                     $fine = DB::select(DB::raw("SELECT SUM(amount) as totalFine FROM managefines WHERE school_id=$user_school_id AND student_id =$s_id AND month='$m_num'"));

                    if (!empty($fine[0]->totalFine)) {
                        $given_total_amount = $given_head_sum+$fine[0]->totalFine;
                    }
                    else{
                        $given_total_amount = $given_head_sum;
                    }

                //finding Late fined amount


                //End of finding Late fined amount
                    $late_fine = DB::select(DB::raw("SELECT amount FROM monthly_late_fees WHERE student_id = $s_id AND month=$m_num AND year= $years"));

                    if(!empty($late_fine[0]->amount)){
                        $with_late_fine_total_amount = $given_total_amount+$late_fine[0]->amount;
                    }
                    else{
                        $with_late_fine_total_amount = $given_total_amount;
                    }
                //End of finding fined amount



                //finding waiver amount
                $total_amount = DB::select(DB::raw("SELECT SUM(discount_amount) as waiver_amount FROM fees_waivers
                     WHERE class_id=$class_id AND year_id=$session_id AND student_id=$s_id"));
/*                print_r($total_amount);
                exit();*/
                if (!empty($total_amount[0]->waiver_amount)) {
                    $total_amount=$with_late_fine_total_amount -$total_amount[0]->waiver_amount;
                }
                else{
                    $total_amount=$with_late_fine_total_amount;
                }
                //End of finding waiver amount


                $payment_id = $school_id.date('Y').$classno.$monthno;
                $p_id = $payment_id;
                  $month_number = $request->month;
                  $y=Date('Y');
                  $chk_duplicate = DB::select(DB::raw("SELECT * FROM invoice
                                  WHERE student_id=$s_id AND payment_id=$p_id AND month=$month_number AND year=$y"));

                  $check_size = count($chk_duplicate);

                 /*If the check-size is 0,then insert the data*/
                  if ($check_size==0) {
                      $payment_id = $school_id.date('Y').$classno.$monthno;

                      $invoice_tbl = DB::table('invoice')->insert([
                      'invoice_no' => $invoice_no,
                      'student_id' => $students[$i]->student_id,
                      'total_amount'=>$total_amount,
                      //'payment_id' =>$payment_id->payment_id,
                      'payment_id' =>$payment_id,
                      'month'=>$request->month,
                      'year'=>date("Y"),
                      'status'=>2,
                      'school_id'=>$user_school_id,
                      'class_id'=>$class_id,
                      'is_sms'=>$isSMS,
                      'is_email'=>$isEmail,
                      'is_partial'=>$isPartial,
                      'due'=>$total_amount,
                      'created_at'=>Date('Y:m:d h:i:s')
                     ]);
                      session()->flash("success", "Invoice generated successfully!");
                  }
                  /*if the check is greater than 0 then update the data*/
                  if ($check_size>0)
                  {
                      session()->flash("error", "Invoice already exists !");
                    //DB::select(DB::raw("UPDATE invoice SET total_amount=$total_amount WHERE student_id=$s_id AND payment_id=$p_id AND month=$month_number AND year=$y"));
                  }

            }

        }


        return back();

    }




    public function retrieve_fees_amount($school_id,$class_id,$year_id)
    {
       $data = DB::table('class_wise_fees')
            ->select('amount','id','school_id','class_id','year_id','fees_id')
            ->where('school_id', '=',1)
            ->where('class_id', '=',$class_id)
            ->where('year_id', '=',$year_id)
            ->get();
       return $data;

    }


}
