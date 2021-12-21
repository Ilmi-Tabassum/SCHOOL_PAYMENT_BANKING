<?php

namespace App\Http\Controllers\tellerpanel;

use App\Http\Controllers\Controller;
use App\Models\ClassWiseFees;
use App\Models\FeesCollection;
use App\Models\FeesHead;
use App\Models\Invoice;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSection;
use App\Models\StudentAcademic;
use App\Models\TransactionList;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use shurjopay\ShurjopayLaravelPackage\Http\Controllers\ShurjopayController;

class TellerPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_info = DB::select( DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
        $sessions = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $shifts = DB::select(DB::raw("SELECT id,name FROM shifts"));
        $sections = DB::select(DB::raw("SELECT id,name FROM sections "));
        $student_name="";
        $fees="";
        $students="";

        return view('tellerpanel/tellerpanel')->with(['school_info' => $school_info,
                                                            'sessions'=>$sessions,
                                                            'classes'=>$classes,
                                                            'shifts'=>$shifts,
                                                            'sections'=>$sections,
                                                            'students'=>$students,
                                                            'student_name'=>$student_name,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param $element
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        $student_id=$request->student_id;
        $school_id=$request->school_id;
        $class_id=$request->class_id;
        $subheads= DB::select("SELECT fees_sub_heads.fees_subhead_name, class_wise_fees.fees_id id,class_wise_fees.amount
                        FROM fees_sub_heads
                          INNER JOIN class_wise_fees ON class_wise_fees.fees_id = fees_sub_heads.id
                          WHERE class_wise_fees.class_id = $class_id AND class_wise_fees.school_id = $school_id ");
        $received_amount=[];
foreach ($subheads as $subhead)
{
    $received = DB::select("SELECT SUM(received_amount) as received_amount
                                            FROM fees_collections
                                            WHERE class_id = $class_id AND student_id=$student_id AND fees_id=$subhead->id
                                           ");

    if(!empty($received))
    {
        array_push($received_amount,$received);
    }
    else
    {
        array_push($received_amount,0);

    }
}


      return view('tellerpanel/payment')->with(['subheads' => $subheads,'received_amount' => $received_amount,'student_id' => $student_id,'school_id' => $school_id,'class_id' => $class_id]);
    }
    public function search(Request $request)
    {
        $student_id_box=$request->student_id_box;
        $invoice_id_box=$request->invoice_id_box;

     /*   $school_id = $request->school_name;
        $class_id = $request->class_name;
        $shift_id = $request->shift_name;
        $section_id = $request->section_name;
        $session_id = $request->session_name;
        $roll = $request->roll;
        $fees="";

        $school_info = DB::select(DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
        $sessions = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $shifts = DB::select(DB::raw("SELECT id,name FROM shifts"));
        $sections = DB::select(DB::raw("SELECT id,name FROM sections "));

        $student_id = "";
        $student_name = "";*/

        if(!empty($invoice_id_box))
        {
            $students = DB::select( DB::raw("
            SELECT invoice.*,invoice.month month,students.id,student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.student_id id ,students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.name
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN invoice ON invoice.student_id = students.id
            WHERE invoice.invoice_no=$invoice_id_box AND invoice.status !=2 AND invoice.status !=1"));
            if(empty($students))
            {
                session()->flash("error", "Invalid Invoice ! ");
            }
        }
        elseif (!empty($student_id_box))
        {
            $students = DB::select( DB::raw("
            SELECT invoice.*,invoice.month month,students.id,student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.student_id id ,students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.name
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN invoice ON invoice.student_id = students.id
            WHERE students.student_id=$student_id_box AND invoice.status !=2 AND invoice.status !=1"));
            if(empty($students))
            {
                session()->flash("error", "Invalid Student! ");

            }


        }
        else {

            $students = "";

            }

        return view('tellerpanel/tellerpanel')->with(['students'=>$students,]);

        }


    public function store(Request $request){
        $fees_id=$request->fees_id;
        $payment_amount=$request->pay_box;
        $class_id=$request->class_id;
        $school_id=$request->school_id;;
        $student_id=$request->student_id;;

        if(!empty($fees_id) && !empty($payment_amount))
        {
            for($i=0;$i<sizeof($fees_id) ; $i=$i+1) {

                $fees_id_search=$fees_id[$i];

                $query= DB::select(DB::raw("SELECT amount
                                        FROM class_wise_fees
                                        WHERE fees_id = $fees_id_search
                                        AND class_id = $class_id"));

                $payable_amount=$query[0]->amount;

                $object = new FeesCollection;
                $object->invoice_no = rand(1000,987456);
                $object->class_id = $class_id;
                $object->student_id = $student_id;
                $object->year_id = date('Y');
                $object->payable_amount = $payable_amount;
                $object->fees_id= $fees_id[$i];
                $object->school_id= $school_id;
                $index="";
                if(!empty($payment_amount[$i]))
                {
                    $object->received_amount = $payment_amount[$i];

                }
                else{

                    for($j=$i;$j<sizeof($payment_amount);$j=$j+1)
                    {
                        if(!empty($payment_amount[$j]))
                        {
                            $index=$j;
                            break;
                        }
                        else{
                            continue;
                        }

                    }
                    $object->received_amount = $payment_amount[$index];

                }
                try {
                    $object->save();

                    session()->flash("success", "Payment  updated successfully!");
                } catch (\Illuminate\Database\QueryException $e) {
                    $errorCode = $e->errorInfo[1];
                    if ($errorCode == '1062') {
                        session()->flash("error", "We are sorry.duplicate entry.");
                    }

                }
            }

        }
        else{

            session()->flash("error", "Please try again");
        }

        return redirect('tellerpanel');

    }



    public function payment_done(Request $request){


                $inv = $request->hidden_invoice_no;
                $bank_tx_id = $request->bTrxiD;
        $inv_details=Invoice::where('invoice_no',$inv)->get();
        $student_id= $inv_details[0]->student_id;
        $amount =$inv_details[0]->due;



                $transactions = new TransactionList;
                $transactions->invoice_no = $inv;
                $transactions->student_id = $student_id;
                $transactions->amount = $amount;
                $transactions->trx_id = $inv;
                $transactions->bank_trx_id = $bank_tx_id;
                $transactions->method = "OTC";
                $transactions->status = "Success";
                $transactions->trn_date = now();

                $transactions->user_id = Auth::User()->id;;

                $transactions->save();

                $sql = "update invoice set status = 1 where invoice_no = '$inv'";
                DB::statement($sql);
                $sql = "update invoice set due = 0 where invoice_no = '$inv'";
                DB::statement($sql);
                //invmail($invoice_no)
                $school_id = Auth::User()->school_id;
        session()->flash("success", "Payment  successfull!");

                return back();
    }


     public function todays_collection_tp()
    {
        $teller_panel_user_id=Auth::User()->id;
        $title = "Todays Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereDay('created_at', '=', date('d'))
                ->where('created_by','=',$teller_panel_user_id)
                ->paginate(11);
        return view('tellerpanel/fees-collection-tellerpanel')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

     public function weekly_collection_tp()
    {
         $teller_panel_user_id=Auth::User()->id;
         $title = "Weekly Collections";
         $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        /*
         *Week start date : SUNDAY
         *Week end date : SATURDAY
        */
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');


        $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereBetween('fees_collections.created_at', [$weekStartDate, $weekEndDate])
                ->where('created_by','=',$teller_panel_user_id)
                ->paginate(11);
         return view('tellerpanel/fees-collection-tellerpanel')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

     public function monthly_collection_tp()
    {
        $teller_panel_user_id=Auth::User()->id;
        $title = "Monthly Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

     $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereMonth('created_at', '=', date('m'))
                ->where('created_by','=',$teller_panel_user_id)
                ->paginate(11);
        return view('tellerpanel/fees-collection-tellerpanel')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }




}
