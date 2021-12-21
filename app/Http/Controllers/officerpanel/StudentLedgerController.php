<?php


namespace App\Http\Controllers\officerpanel;

use App\Http\Controllers\Controller;
use App\Models\ClassWiseFees;
use App\Models\FeesCollection;
use App\Models\FeesHead;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSection;
use App\Models\StudentAcademic;
use Illuminate\Http\Request;
use DB;
use Auth;

class StudentLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_info = DB::select(DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
        $sessions = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $shifts = DB::select(DB::raw("SELECT id,name FROM shifts"));
        $sections = DB::select(DB::raw("SELECT id,name FROM sections "));
        $student_name = "";
        $fees = "";


        return view('officerpanel/student_ledger')->with(['school_info' => $school_info,
            'sessions' => $sessions,
            'classes' => $classes,
            'shifts' => $shifts,
            'sections' => $sections,
            'fees' => $fees,
            'student_name' => $student_name,]);
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
        $student_id = $request->student_id;
        $school_id = $request->school_id;
        $class_id = $request->class_id;

        $fees = DB::select("SELECT class_wise_fees.fees_id id,fees_sub_heads.fees_subhead_name,fees_collections.class_id,fees_collections.student_id,class_wise_fees.school_id,fees_collections.payable_amount,SUM(fees_collections.received_amount) as received_amount
                                            FROM fees_sub_heads
                                            INNER JOIN class_wise_fees ON class_wise_fees.fees_id = fees_sub_heads.id
                                            INNER JOIN fees_collections ON fees_collections.fees_id = fees_sub_heads.id
                                            WHERE class_wise_fees.class_id = $class_id AND class_wise_fees.school_id = $school_id
                                            GROUP BY class_wise_fees.fees_id,fees_sub_heads.fees_subhead_name,fees_collections.class_id,fees_collections.student_id,class_wise_fees.school_id,fees_collections.payable_amount
                                           ");

        return view('officerpanel/payment')->with(['fees' => $fees, 'student_id' => $student_id, 'school_id' => $school_id, 'class_id' => $class_id]);
    }

    public function search(Request $request)
    {
        $student_id_box = $request->student_id_box;
        $school_id = $request->school_name;
        $class_id = $request->class_name;
        $shift_id = $request->shift_name;
        $section_id = $request->section_name;
        $session_id = $request->session_name;
        $roll = $request->roll;

        $school_info = DB::select(DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
        $sessions = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $shifts = DB::select(DB::raw("SELECT id,name FROM shifts"));
        $sections = DB::select(DB::raw("SELECT id,name FROM sections "));



        if (!empty($school_id) && !empty($class_id) && !empty($shift_id) && !empty($section_id) && !empty($session_id)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.class_id,
                   students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,
                   class_infos.name cname,shifts.name shname,sessions.name sename,students.status,fees_sub_heads.id,fees_sub_heads.fees_subhead_name
                   ,fees_collections.fees_id,fees_collections.student_id,fees_collections.invoice_no,fees_collections.payable_amount,fees_collections.received_amount
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.student_id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN fees_collections ON fees_collections.student_id = student_academics.student_id
            INNER JOIN fees_sub_heads ON fees_sub_heads.id = fees_collections.fees_id
            WHERE student_academics.school_id=$school_id AND student_academics.class_id=$class_id AND student_academics.shift_id=$shift_id AND student_academics.section_id=$section_id AND student_academics.session_id=$session_id AND student_academics.std_roll=$roll"));
            if (empty($students)) {
               // print_r($students);
                session()->flash("error", " Invalid Student!");
                $students="";
            }
        } elseif (!empty($student_id_box)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.student_id id ,
                   students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,
                   class_infos.name cname,shifts.name shname,sessions.name sename,students.status,fees_sub_heads.id,fees_sub_heads.fees_subhead_name
                   ,fees_collections.fees_id,fees_collections.student_id,fees_collections.invoice_no,fees_collections.payable_amount,fees_collections.received_amount
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.student_id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN fees_collections ON fees_collections.student_id = student_academics.student_id
            INNER JOIN fees_sub_heads ON fees_sub_heads.id = fees_collections.fees_id
            WHERE student_academics.student_id=$student_id_box"));
            if (empty($students)) {
                session()->flash("error", " Invalid Student!");
                $students="";
            }


        } else {
            session()->flash("error", "Invalid Student!");
            $students="";

        }

        return view('officerpanel/student_ledger')->with(['school_info' => $school_info,
            'sessions' => $sessions,
            'classes' => $classes,
            'shifts' => $shifts,
            'sections' => $sections,
            'students' => $students,
                ]);

    }

    public function store(Request $request)
    {
        $fees_id = $request->fees_id;
        $payment_amount = $request->pay_box;
        $class_id = $request->class_id;
        $school_id = $request->school_id;;
        $student_id = $request->student_id;;
        if (!empty($fees_id) && !empty($payment_amount)) {
            for ($i = 0; $i < sizeof($fees_id); $i = $i + 1) {

                $fees_id_search = $fees_id[$i];

                $query = DB::select(DB::raw("SELECT payable_amount,received_amount
                                        FROM fees_collections
                                        WHERE fees_id = $fees_id_search
                                        AND student_id = $student_id"));
                $payable_amount = $query[0]->payable_amount;
                $received_amount = $query[0]->received_amount;

                $object = new FeesCollection;
                $object->invoice_no = rand(1000, 987456);
                $object->class_id = $class_id;
                $object->student_id = $student_id;
                $object->year_id = 2020;
                $object->payable_amount = $payable_amount;
                $object->fees_id = $fees_id[$i];
                $object->received_amount = $payment_amount[$i];
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

        } else {

            session()->flash("error", "Please try again");
        }

        //return $return;
        return redirect()->route('officerpanel');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

}
