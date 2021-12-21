<?php


namespace App\Http\Controllers\tellerpanel;

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
        if(isset(Auth::user()->school_id))
        {
            $school_info="";
        }
        else
        {
            $school_info = DB::select(DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));

        }
        $sessions = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $shifts = DB::select(DB::raw("SELECT id,name FROM shifts"));
        $sections = DB::select(DB::raw("SELECT id,name FROM sections "));
        $student_name = "";
        $fees = "";


        return view('tellerpanel/student_ledger')->with(['school_info' => $school_info,
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


    public function search(Request $request)
    {
        $student_id_box = $request->student_id_box;
        if(isset(Auth::user()->school_id))
        {
            $school_id = Auth::user()->school_id;

        }
        else
        {
            $school_id = $request->school_name;

        }
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



        if (!empty($school_id) && !empty($class_id) && !empty($shift_id) && !empty($section_id) && !empty($session_id) && !empty($roll))  {
            $students = DB::select(DB::raw("
                SELECT DISTINCT students.id,students.name student_name,students.student_id id
                FROM student_academics
                INNER JOIN students ON student_academics.student_id = students.id
                WHERE student_academics.school_id=$school_id AND student_academics.class_id=$class_id AND student_academics.shift_id=$shift_id AND student_academics.section_id=$section_id AND student_academics.session_id=$session_id AND student_academics.std_roll=$roll"));

            if(!empty($students))
            {
                $student_id=$students[0]->id;
                $paid = DB::select(DB::raw("
                SELECT invoice.invoice_no,invoice.total_amount, DATE(transaction_lists.trn_date) payment_date
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                INNER JOIN transaction_lists ON transaction_lists.invoice_no = invoice.invoice_no
                WHERE students.student_id=$student_id AND invoice.status=1"));
                $paid_total=DB::select(DB::raw("
                SELECT sum(invoice.total_amount) grand_total
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id AND invoice.status=1"));

                $unpaid = DB::select(DB::raw("
                SELECT invoice.invoice_no,invoice.total_amount,DATE(invoice.created_at) created_at
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id AND invoice.status=0"));
                $unpaid_total=DB::select(DB::raw("
                SELECT sum(invoice.total_amount) grand_utotal
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id AND invoice.status=0"));
            }

/*            $students = DB::select(DB::raw("
            SELECT students.id,student_academics.student_id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.class_id,
                   students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,
                   class_infos.name cname,shifts.name shname,sessions.name sename,students.status,fees_sub_heads.id,fees_sub_heads.fees_subhead_name
                   ,fees_collections.fees_id,fees_collections.student_id,fees_collections.invoice_no,fees_collections.payable_amount,fees_collections.received_amount
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN fees_collections ON fees_collections.student_id = student_academics.student_id
            INNER JOIN fees_sub_heads ON fees_sub_heads.id = fees_collections.fees_id
            WHERE student_academics.school_id=$school_id AND student_academics.class_id=$class_id AND student_academics.shift_id=$shift_id AND student_academics.section_id=$section_id AND student_academics.session_id=$session_id AND student_academics.std_roll=$roll"));*/
            if (empty($students)) {
               // print_r($students);
                session()->flash("error", " Invalid Student!");
                $students="";
                $paid="";
                $paid_total="";
                $unpaid="";
                $unpaid_total="";
            }
        } elseif (!empty($student_id_box)) {
            $students = DB::select(DB::raw("
                SELECT DISTINCT students.id,students.name student_name,students.student_id id
                FROM students
                WHERE students.student_id=$student_id_box"));
            $paid = DB::select(DB::raw("
                SELECT invoice.invoice_no,invoice.total_amount, DATE(transaction_lists.trn_date) payment_date
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                INNER JOIN transaction_lists ON transaction_lists.invoice_no = invoice.invoice_no
                WHERE students.student_id=$student_id_box AND invoice.status=1"));
            $paid_total=DB::select(DB::raw("
                SELECT sum(invoice.total_amount) grand_total
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id_box AND invoice.status=1"));

            $unpaid = DB::select(DB::raw("
                SELECT invoice.invoice_no,invoice.total_amount,DATE(invoice.created_at) created_at
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id_box AND invoice.status=0"));
            $unpaid_total=DB::select(DB::raw("
                SELECT sum(invoice.total_amount) grand_utotal
                FROM invoice
                INNER JOIN students ON invoice.student_id = students.id
                WHERE students.student_id=$student_id_box AND invoice.status=0"));

     //print_r($students);
            /*print_r($paid);
            print_r($paid_total);*/

            //print_r($unpaid_total);


           /* $students = DB::select(DB::raw("
            SELECT students.id,student_academics.student_id,students.id,student_academics.school_id schoolid,student_academics.class_id classid,student_academics.student_id id ,
                   students.name stu_name, student_academics.std_roll roll,sections.name sname,school_infos.school_name schname,
                   class_infos.name cname,shifts.name shname,sessions.name sename,students.status,fees_sub_heads.id,fees_sub_heads.fees_subhead_name
                   ,fees_collections.fees_id,fees_collections.student_id,fees_collections.invoice_no,fees_collections.payable_amount,fees_collections.received_amount
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            INNER JOIN fees_collections ON fees_collections.student_id = student_academics.student_id
            INNER JOIN fees_sub_heads ON fees_sub_heads.id = fees_collections.fees_id
            WHERE students.student_id=$student_id_box"));*/
            if (empty($students)) {
                session()->flash("error", " Invalid Student!");
                $students="";
                $paid="";
                $paid_total="";
                $unpaid="";
                $unpaid_total="";

            }


        } else {
            session()->flash("error", "Invalid Student!");
            $students="";
            $paid="";
            $paid_total="";
            $unpaid="";
            $unpaid_total="";

        }

        return view('tellerpanel/student_ledger')->with(['school_info' => $school_info,
            'sessions' => $sessions,
            'classes' => $classes,
            'shifts' => $shifts,
            'sections' => $sections,
            'students' => $students,
            'paid' => $paid,
            'paid_total' => $paid_total,
            'unpaid' => $unpaid,
            'unpaid_total' => $unpaid_total,

                ]);

    }

    public function store(Request $request)
    {
       //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

}
