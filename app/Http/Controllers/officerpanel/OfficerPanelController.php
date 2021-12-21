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

class OfficerPanelController extends Controller
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



        return view('officerpanel/payment_collection')->with(['school_info' => $school_info,
                                                            'sessions'=>$sessions,
                                                            'classes'=>$classes,
                                                            'shifts'=>$shifts,
                                                            'sections'=>$sections,
                                                            'fees'=>$fees,
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


        return view('officerpanel/payment')->with(['subheads' => $subheads,'received_amount' => $received_amount,'student_id' => $student_id,'school_id' => $school_id,'class_id' => $class_id]);
    }
    public function search(Request $request)
    {
        $student_id_box=$request->student_id_box;
        $school_id = $request->school_name;
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
        $student_name = "";

        if(!empty($school_id) && !empty($class_id) && !empty($shift_id) && !empty($section_id) && !empty($session_id))
        {
            $school_name = DB::select(DB::raw("SELECT DISTINCT school_name FROM  school_infos  WHERE id=$school_id"));
            $class_name = DB::select(DB::raw("SELECT DISTINCT name FROM  class_infos  WHERE id=$class_id"));
            $shift_name = DB::select(DB::raw("SELECT DISTINCT name FROM  shifts  WHERE id=$shift_id"));
            $section_name = DB::select(DB::raw("SELECT DISTINCT name FROM  sections  WHERE id=$section_id"));
            $session_name = DB::select(DB::raw("SELECT DISTINCT name FROM  sessions  WHERE id=$session_id"));
            $school_name = $school_name[0]->school_name;
            $class_name = $class_name[0]->name;
            $shift_name = $shift_name[0]->name;
            $section_name = $section_name[0]->name;
            $session_name = $session_name[0]->name;

            $student = DB::select(DB::raw("
            SELECT student_id
            FROM student_academics
            WHERE school_id=$school_id AND class_id=$class_id AND shift_id=$shift_id AND section_id=$section_id AND session_id=$session_id AND std_roll=$request->roll"));



            if (!empty($student)) {
                $student_id = $student[0]->student_id;
                $student_name = DB::select(DB::raw("SELECT name FROM students WHERE student_id=$student_id"));

                $student_name = $student_name[0]->name;


            }
            else{
                session()->flash("error", "Invalid Student!");

                $school_name = "";
                $class_name = "";
                $shift_name = "";
                $section_name = "";
                $session_name = "";
                $student_id = "";
                $roll = "";
                $student_name = "";

            }
        }
        elseif (!empty($student_id_box))
        {
            $students = DB::select( DB::raw("
            SELECT students.id,student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.student_id id ,students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE students.student_id=$student_id_box"));
            if(!empty($students))
            {

                $school_name = $students[0]->schname;
                $class_name = $students[0]->cname;
                $shift_name = $students[0]->shname;
                $section_name = $students[0]->sname;
                $session_name = $students[0]->sename;
                $student_id = $students[0]->student_id;
                $roll = $students[0]->roll;
                $student_name = $students[0]->stu_name;
                $school_id = $students[0]->schoolid;
                $class_id = $students[0]->classid;

            } else {
                session()->flash("error", "Invalid Student! 1");

                $school_name = "";
                $class_name = "";
                $shift_name = "";
                $section_name = "";
                $session_name = "";
                $student_id = "";
                $roll = "";
                $student_name = "";
            }



        }
        else {
            session()->flash("error", "Invalid Student! 2");

            $school_name = "";
            $class_name = "";
            $shift_name = "";
            $section_name = "";
            $session_name = "";
            $student_id = "";
            $roll = "";
            $student_name = "";

        }


        return view('officerpanel/payment_collection')->with(['school_info' => $school_info,
            'sessions' => $sessions,
            'student_id' => $student_id,
            'classes' => $classes,
            'shifts' => $shifts,
            'sections' => $sections,
            'student_name' => $student_name,
            'school_name' => $school_name,
            'class_name' => $class_name,
            'shift_name' => $shift_name,
            'section_name' => $section_name,
            'session_name' => $session_name,
            'school_id' => $school_id,
            'class_id' => $class_id,
            'roll' => $roll,
            'fees'=>$fees,]);


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

        return redirect()->route('officerpanel');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

}
