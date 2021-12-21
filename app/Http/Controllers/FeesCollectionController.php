<?php

namespace App\Http\Controllers;

use App\Models\FeesCollection;
use App\Models\SchoolDistrict;
use App\Models\SchoolDivision;
use App\Models\SchoolInfo;
use App\Models\SchoolPost;
use App\Models\TransactionList;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;


class FeesCollectionController extends Controller
{
    public function index()
    {
        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $years = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        return view('backend/fees_collection')->with(['classes'=>$classes,'years'=>$years]);
    }

    public function StudentsDuesInvoices(Request $request)
    {
        $student_id = $request->student_id;
        $school_id = Auth::user()->school_id;
        $class_id = $request->class_id;
        $year = $request->year_id;

        $invoices = DB::table("invoice")
                    ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                    ->select('invoice.*', 'class_infos.name as class_name')
                    ->where('invoice.school_id',$school_id)
                    ->where('invoice.class_id',$class_id)
                    ->where('invoice.student_id',$student_id)
                    ->where('invoice.year',$year)
                    ->where('invoice.status',0)
                    ->get();

        return $invoices;
    }

    public function InvoiceDetails($id)
    {
        $invoice = DB::table("invoice")
                ->select('invoice.*')
                ->where('invoice.id',$id)
                ->get();
        //dd($invoice);

        $school_id = $invoice[0]->school_id;
        $class_id = $invoice[0]->class_id;
        $year = $invoice[0]->year;

        $feesDetails = DB::table("class_wise_fees")
                    ->join('fees_heads', 'class_wise_fees.fees_id', '=', 'fees_heads.id')
                    ->select('class_wise_fees.*', 'fees_heads.fees_head_name')
                    ->where('class_wise_fees.school_id',$school_id)
                    ->where('class_wise_fees.class_id',$class_id)
                    ->where('class_wise_fees.year_id',$year)
                    ->get();

        return $feesDetails;


    }

    public function store_fc(Request $request)
    {
        $invoice_no = $request->inv_no;
        $given_amount = $request->total_amt;
        $trn_date = date('Y-m-d H:i:s');
        $student_id = $request->student_id;
        $student_info = DB::select(DB::raw("SELECT * FROM students WHERE id = $student_id"));
        $student_full_id = $student_info[0]->student_id;
        $user_id = Auth::user()->id;


        $invoice = DB::select(DB::raw("SELECT * FROM invoice WHERE invoice_no = $invoice_no"));
        $total_tk = $invoice[0]->total_amount;


        $data = new TransactionList;
        $data->invoice_no = $invoice_no;
        $data->amount = $given_amount;
        $data->trn_date = $trn_date;
        $data->student_id = $student_full_id;
        $data->user_id = $user_id;
        $data->save();

        if ($total_tk == $given_amount ) {
            $school_id= Auth::user()->school_id;
            $data = DB::select(DB::raw("UPDATE invoice SET status=1 WHERE invoice_no = '$invoice_no' AND student_id='$student_id' AND school_id='$school_id'"));
        }

        return back();
    }

    public function data_table_data(){
            $data = DB::select( DB::raw("
            SELECT cwf.id,cwf.class_id,cwf.year_id,cwf.fees_id,cwf.amount, fsh.fees_subhead_name
            FROM class_wise_fees as cwf
            INNER JOIN fees_sub_heads as fsh ON cwf.fees_id = fsh.id
            WHERE cwf.class_id = 1 AND cwf.year_id = 1"));

            return response($data);

    }

    public function store(Request $request)
    {

        foreach($request->fees_id as $id) {

            $check = FeesCollection::where('year_id',$request->year)
                      ->where('class_id',$request->class)
                      ->where('fees_id',$id)
                      ->where('student_id',$request->student_id)
                      ->first();

            //var_dump($check);exit;
            if(!empty($check)){
               //echo "Will be update";exit;
                $data=array();
                $given_amount = $request['given_amount_'.$id];
                $discount_amount = $request['discount_amount_'.$id];
                $updated_amount = $given_amount+$discount_amount;
                $data['received_amount']= $updated_amount;
                $data['updated_by'] = Auth::user()->id;
                if(!empty($given_amount)){
                    $check->update($data);
                }
             }

            else{
               // echo "Will be insert";exit;
                $data=array();
                $amount = $request['given_amount_'.$id];
                $arr['student_id'] = $request->student_id;
                $arr['class_id'] = $request->class;
                $arr['year_id'] = $request->year;
                $arr['school_id'] = Auth::user()->school_id;
                $arr['fees_id']=$id;
                //$arr['payable_amount'] = $request['due_amount_'.$id];;
                $arr['received_amount']=$amount;
                $arr['payment_date'] = date("Y-m-d");
                $arr['created_by'] = Auth::user()->id;
                if (!empty($amount)) {
                   FeesCollection::create($arr);
                }
            }
        }

       return back();


    }

    public function fetch_student_id($year ,$class)
     {
         $schid= Auth::user()->school_id;
        $students = DB::select(DB::raw("
            SELECT s.id,sa.student_id,sa.session_id,sa.class_id ,s.student_id
            FROM student_academics as sa
            INNER JOIN students as s
            ON sa.student_id = s.id
            WHERE sa.session_id = $year AND sa.class_id =$class AND sa.school_id =$schid"));

        $count_student = count($students);
        if ($count_student>0) {
            return response()->json(['hasStudent' => '1', 'students' =>$students]);
        }
        else{
            return response()->json(['hasStudent' => '0']);
        }

      }

    public function fetch_fees_collection($year_id,$class_id,$student_id)
    {
        $data = DB::table('fees_collections')
            ->select('id','fees_id','payable_amount','received_amount')
            ->where('student_id', '=',$student_id)
            ->where('class_id', '=',$class_id)
            ->where('year_id', '=',$year_id)
            ->get();
        return $data;
    }

    public function monthly_collection()
    {


        $title = "Monthly Collections";

        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->whereMonth('transaction_lists.trn_date', '=', date('m'))
            ->where('invoice.status','!=',2)
            ->where('invoice.status','!=',0)
            ->paginate(30);
       // dd($data);




        //dd($classes_info);


        $title = "Monthly Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        /*
            *Problem: We need to find out the distict school_id and corresponding total student and sum
            *For a specific school do the follwing
            *school_id
            *total student paid
            *total amount
        */
        return view('backend/fees_collection_report')->with(['data'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

    //  public function monthly_collection_sa()
    // {
    //     $school_id=Auth::User()->school_id;
    //     $title = "Monthly Collections";
    //     $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

    //  $data =DB::table('fees_collections')
    //             ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
    //             ->groupBy('school_id')
    //             ->whereMonth('created_at', '=', date('m'))
    //             ->where('school_id','=',$school_id)
    //             ->paginate(11);
    //     return view('schoolpanel/dashboard/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    // }


    public function todays_collection()
    {
        $title = "Today's Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('transaction_lists.trn_date', '=', date('Y-m-d'))
            ->where('invoice.status','=',1)
            ->paginate(50);
        return view('backend/todays_collection_report')->with(['data'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

    public function serachTCollectionA(Request $request)
    {
        $sid = $request->schoolid;
        $title = "Today's Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('invoice.school_id','=',$sid)
            ->where('transaction_lists.trn_date', '=', date('Y-m-d'))
            ->where('invoice.status','=',1)
            ->paginate(50);
        return view('backend/todays_collection_report')->with(['data'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

    public function serachWCollectionA(Request $request)
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');
        $sid = $request->schoolid;
        $title = "Today's Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('invoice.school_id','=',$sid)
            ->whereBetween('transaction_lists.trn_date', [$weekStartDate, $weekEndDate])
            ->where('invoice.status','=',1)
            ->paginate(50);
        return view('backend/weekly_collection_report')->with(['data'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

    // public function todays_collection_sa()
    // {
    //     $school_id=Auth::User()->school_id;
    //     $title = "Todays Collections";
    //     $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

    //     $data =DB::table('fees_collections')
    //             ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
    //             ->groupBy('school_id')
    //             ->whereDay('created_at', '=', date('d'))
    //             ->where('school_id','=',$school_id)
    //             ->paginate(11);
    //     return view('schoolpanel/dashboard/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    // }




    public function weekly_collection()
    {
         $title = "Weekly Collections";
         $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        /*
         *Week start date : SUNDAY
         *Week end date : SATURDAY
        */

        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');

        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->whereBetween('transaction_lists.trn_date', [$weekStartDate, $weekEndDate])
            ->where('invoice.status','=',1)
            ->paginate(50);

         return view('backend/weekly_collection_report')->with(['data'=>$data,'title'=>$title,'school_info'=>$school_info]);
    }

    public function monthly_collection_sa()
    {
        $school_id=Auth::User()->school_id;
        $title = "Monthly Collections";

        $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', date('m'))
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',1)
                ->paginate(30);
        //dd($data);


        $classes_info =DB::table('assign_classes')
                ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
                ->select('assign_classes.class_id', 'class_infos.name as class_name')
                ->where('assign_classes.school_id','=',$school_id)
                ->get();

        $sections =DB::table('assign_sections')
            ->join('sections', 'assign_sections.section_id', '=', 'sections.id')
            ->select('assign_sections.section_id', 'sections.name as section_name')
            ->where('assign_sections.school_id','=',$school_id)
            ->distinct()
            ->get();

        //dd($classes_info);

        return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
    }

    public function monthly_collection_a()
    {
        $title = "Monthly Collections";
        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('invoice.month', '=', date('m'))
            ->where('invoice.status','=',1)
            ->paginate(50);
        //dd($data);
        return view('backend/fees_collection_report')->with(['data'=>$data,'title'=>$title]);
    }

    public function todays_collection_sa()
    {



        $school_id=Auth::User()->school_id;
        $classes_info =DB::table('assign_classes')
                ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
                ->select('assign_classes.class_id', 'class_infos.name as class_name')
                ->where('assign_classes.school_id','=',$school_id)
                ->get();
        $sections =DB::table('assign_sections')
            ->join('sections', 'assign_sections.section_id', '=', 'sections.id')
            ->select('assign_sections.section_id', 'sections.name as section_name')
            ->where('assign_sections.school_id','=',$school_id)
            ->distinct()
            ->get();
       // dd($classes_info);

        $title = "Todays Collections";
        $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereDay('transaction_lists.trn_date', '=', date('d'))
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',1)
                ->paginate(50);
        return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
    }

    public function weekly_collection_sa()
    {
         $school_id=Auth::User()->school_id;
         $title = "Weekly Collections";


          $classes_info =DB::table('assign_classes')
                ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
                ->select('assign_classes.class_id', 'class_infos.name as class_name')
                ->where('assign_classes.school_id','=',$school_id)
                ->get();
        $sections =DB::table('assign_sections')
            ->join('sections', 'assign_sections.section_id', '=', 'sections.id')
            ->select('assign_sections.section_id', 'sections.name as section_name')
            ->where('assign_sections.school_id','=',$school_id)
            ->distinct()
            ->get();

        /*
         *Week start date : SUNDAY
         *Week end date : SATURDAY
        */
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');


         $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereBetween('transaction_lists.trn_date',[$weekStartDate, $weekEndDate])
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',1)
                ->paginate(50);
        return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
    }

    public function total_dues_sa()
    {
        $school_id=Auth::User()->school_id;
        $title = "Monthly Total Dues";
         $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', date('m'))
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',0)
                ->paginate(50);

        return view('schoolpanel/dashboard/total_dues')->with(['data'=>$data,'title'=>$title]);
    }

    public function TotalDuesSearchSA(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $school_id=Auth::user()->school_id;
        $title = "Monthly Dues";

        if (empty($month) && empty($year)) {
           session()->flash("error", "No Search Key Added");
           return back();
        }

        if (!empty($month) && empty($year)) {

             $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', $month)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',0)
                ->paginate(50);
            return view('schoolpanel/dashboard/total_dues')->with(['data'=>$data,'title'=>$title]);
        }



         if (empty($month) && !empty($year)) {

             $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereYear('transaction_lists.trn_date', '=', $year)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',0)
                ->paginate(50);
            return view('schoolpanel/dashboard/total_dues')->with(['data'=>$data,'title'=>$title]);
        }

        if (!empty($month) && !empty($year)) {

             $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', $month)
                ->whereYear('transaction_lists.trn_date', '=', $year)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',0)
                ->paginate(50);
            return view('schoolpanel/dashboard/total_dues')->with(['data'=>$data,'title'=>$title]);
        }

    }

    public function total_dues()
    {
        $title = "Monthly Total Dues";
        $pabeTotal =DB::table('invoice')
            ->select(DB::raw('invoice.school_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(invoice.total_amount) AS due_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('invoice.status','=',0)
            ->paginate(50);

        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        return view('backend/total_dues')->with(['datas'=>$pabeTotal,'school_info'=>$school_info]);
    }



    // public function total_dues_sa()
    // {
    //     $school_id=Auth::User()->school_id;
    //     $pabeTotal = DB::table('class_wise_fees')
    //             ->select(DB::raw('school_id, SUM(amount) AS total_amount'))
    //             ->groupBy('school_id')
    //             ->whereYear('created_at', '=', date('Y'))
    //             ->where('school_id','=',$school_id)
    //             ->paginate(11);

    //     return view('schoolpanel/dashboard/total_dues')->with(['datas'=>$pabeTotal]);
    // }




    public function serachCollectionData(Request $request)
    {

        $title = "Collections";
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        $school_id = $request->school_id;
        $date_range = $request->sdate;

        if (!empty($date_range) && empty($school_id) ) {
             $part= explode("to", $date_range);
             $start_date = trim($part[0]," ");
             $end_date = trim($part[1]," ");

             // echo $start_date;
             // echo $end_date;
             // exit;
              $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereBetween('payment_date', [$start_date, $end_date])
                ->paginate(11);
             //dd($data);
             $size = count($data);

             if($size>0){
                //echo "has some data";exit;
                session()->flash("success", "Data fetched successfully");
                return view('backend/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
             }

             else{

                /*Dummy purpose...solve soon*/
                 $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereBetween('payment_date', ['0000-00-00', $end_date])
                ->paginate(11);

                session()->flash("error", "No Data found");
                return view('backend/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);


             }
        }



        if (!empty($date_range) && !empty($school_id) ){

            $part= explode("to", $date_range);
            $start_date = trim($part[0]," ");
            $end_date = trim($part[1]," ");

            $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereBetween('payment_date', [$start_date, $end_date])
                ->where('school_id','=',$school_id)
                ->paginate(11);

             $size = count($data);

             if($size>0){
                session()->flash("success", "Data fetched successfully");
                return view('backend/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
             }
             else{
                session()->flash("error", "No Data found");
                 $data =DB::table('fees_collections')
                ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
                ->groupBy('school_id')
                ->whereBetween('payment_date', ['0000-00-00', $end_date])
                ->where('school_id','=',$school_id)
                ->paginate(11);
                return view('backend/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
                //return back();
             }

        }

    }


/*    // public function serachCollectionSA(Request $request)
    // {
    //     $school_id= Auth::User()->school_id;
    //     $title = "Collections";
    //     $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
    //     $date_range = $request->sdate;

    //     if (!empty($date_range)) {
    //          $part= explode("to", $date_range);
    //          $start_date = trim($part[0]," ");
    //          $end_date = trim($part[1]," ");
    //           $data =DB::table('fees_collections')
    //             ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
    //             ->groupBy('school_id')
    //             ->where('school_id','=',$school_id)
    //             ->whereBetween('payment_date', [$start_date, $end_date])
    //             ->paginate(11);

    //          //dd($data);
    //          $size = count($data);

    //          if($size>0){
    //             session()->flash("success", "Data fetched successfully");
    //             return view('schoolpanel/dashboard/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    //          }

    //          else{

    //               $data =DB::table('fees_collections')
    //                     ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
    //                     ->groupBy('school_id')
    //                     ->where('school_id','=',$school_id)
    //                     ->whereBetween('payment_date', ['0000-00-00', $end_date])
    //                     ->paginate(11);

    //             session()->flash("error", "No Data found");
    //             return view('schoolpanel/dashboard/fees_collection_report')->with(['datas'=>$data,'title'=>$title,'school_info'=>$school_info]);
    //          }
    //     }
    // }

    //   $data =DB::table('fees_collections')
    //     ->select(DB::raw('school_id, COUNT(DISTINCT student_id) AS total_students, SUM(received_amount) AS total_amount'))
    //     ->groupBy('school_id')
    //     ->where('school_id','=',$school_id)
    //     ->whereBetween('payment_date', [$start_date, $end_date])
    //     ->paginate(11);

    //  $size = count($data);*/




    public function serachCollectionSA(Request $request)
    {

        $month = $request->month;
        $year = $request->year;
        $class = $request->class_info;
        $section = $request->section;
        $status = $request->statuss;
        if($status=='due')
        {
            $stat=0;
        }
        else{
            $stat=1;
        }

        $school_id=Auth::User()->school_id;
        $title = "Collections";


         $classes_info =DB::table('assign_classes')
                ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
                ->select('assign_classes.class_id', 'class_infos.name as class_name')
                ->where('assign_classes.school_id','=',$school_id)
                ->get();

        $sections =DB::table('assign_sections')
            ->join('sections', 'assign_sections.section_id', '=', 'sections.id')
            ->select('assign_sections.section_id', 'sections.name as section_name')
            ->where('assign_sections.school_id','=',$school_id)
            ->distinct()
            ->get();

        /*If all search field empty*/
        if (empty($month) && empty($year) && empty($class) && empty($status)) {
           //echo "All Search Field Empty";
           return back();
        }



        /*If search only Month Wise*/
        if (!empty($month) && empty($year) && empty($class) && empty($status)) {

                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',1)
                    ->paginate(50);

                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
        }
         /*If search only Year Wise*/
        if (empty($month) && !empty($year) && empty($class) && empty($status)) {
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',1)
                    ->paginate(50);

                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
        }
        /*If search only Class Wise*/
        if (!empty($class) && empty($status) && empty($month) && empty($year)) {

            // if ($class=='all') {
            //     //echo "Whole school Paid report";exit;
            //     $data =DB::table('invoice')
            //         ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            //         ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
            //         ->groupBy('invoice.class_id')
            //         ->where('invoice.school_id','=',$school_id)
            //         ->where('invoice.status','=',1)
            //         ->paginate(50);

            //     return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info]);
            // }

            // else{
            //echo "Specific Class";exit;
            $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.class_id','=',$class)
                ->where('invoice.status','=',1)
                ->paginate(50);

            return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            // }


        }

        /*If search only Class Wise*/
        if (!empty($class) && !empty($section) && empty($status) && empty($month) && empty($year)) {

            // if ($class=='all') {
            //     //echo "Whole school Paid report";exit;
            //     $data =DB::table('invoice')
            //         ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            //         ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
            //         ->groupBy('invoice.class_id')
            //         ->where('invoice.school_id','=',$school_id)
            //         ->where('invoice.status','=',1)
            //         ->paginate(50);

            //     return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info]);
            // }

            // else{
            //echo "Specific Class";exit;
            $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.class_id','=',$class)
                ->where('invoice.status','=',1)
                ->paginate(50);

            return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            // }


        }
        /*If search only Status Wise*/
        if (!empty($status) && empty($class) && empty($month) && empty($year)) {

            // if ($status=='all') {
            //     //echo "All report";exit;
            //     $data =DB::table('invoice')
            //         ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            //         ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
            //         ->groupBy('invoice.class_id')
            //         ->where('invoice.school_id','=',$school_id)
            //         ->paginate(50);
            //     //dd($data);

            //     return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info]);
            // }


            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',0)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',1)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

        }


          /*If Combine search both year and month Wise*///ab
        if (!empty($month) && !empty($year) && empty($class) && empty($status)) {
           //echo "Combine search both year and month";

                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',1)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
        }
        /*If Combine search both class and month Wise*///ac
        if (!empty($month) && empty($year) && !empty($class) && empty($status)) {
            //echo "Combine search both year and month";

            $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', $month)
                ->where('invoice.class_id','=',$class)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.status','=',1)
                ->paginate(50);
            return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
        }
        /*If Combine search both status and month Wise*///ad
        if (!empty($month) && empty($year) && empty($class) && !empty($status)) {
            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

        }
        /*If Combine search both year and class Wise*///bc
        if (empty($month) && !empty($year) && !empty($class) && empty($status)) {
            //echo "Combine search both year and month";

            $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereYear('transaction_lists.trn_date', '=', $year)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.class_id','=',$class)
                ->paginate(50);
            return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
        }
          /*If Combine search for class and status*///cd
        if (empty($month) && empty($year) && !empty($class) && !empty($status) ) {

            if ($status=='all' && $class=='all') {

                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->paginate(50);
                dd($data);
                //return view('schoolpanel/dashboard/combine_class_status')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info]);
            }


            else{
                if ($status=='paid') {
                    $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->where('invoice.status','=',1)
                    ->paginate(50);
                   return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
                }

                else{
                     $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->where('invoice.status','=',0)
                    ->paginate(50);
                   return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
                }


            }

        }
        /*If Combine search for year and status*///bd
        if (empty($month) && !empty($year) && empty($class) && !empty($status) ) {

            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

        }


        /*If Combine search for month, year and status*///abd
        if (!empty($month) && !empty($year) && empty($class) && !empty($status) ) {

            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.status','=',$stat)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

        }
        /*If Combine search for month, year and class*///abc
        if (!empty($month) && !empty($year) && !empty($class) && empty($status) ) {

            $data =DB::table('invoice')
                ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                ->groupBy('invoice.class_id')
                ->whereMonth('transaction_lists.trn_date', '=', $month)
                ->whereYear('transaction_lists.trn_date', '=', $year)
                ->where('invoice.school_id','=',$school_id)
                ->where('invoice.class_id','=',$class)
                ->paginate(50);
            return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);


        }
        /*If Combine search for month, status and class*///acd
        if (!empty($month) && empty($year) && !empty($class) && !empty($status) ) {

            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->where('invoice.status','=',$stat)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereMonth('transaction_lists.trn_date', '=', $month)
                    ->where('invoice.status','=',$stat)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

        }
        /*If Combine search for month, status and class*///bcd
        if (empty($month) && !empty($year) && !empty($class) && !empty($status) ) {

            if($status=='due'){
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.status','=',$stat)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->paginate(50);

                //dd($data)
                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }

            else{
                $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->whereYear('transaction_lists.trn_date', '=', $year)
                    ->where('invoice.status','=',$stat)
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.class_id','=',$class)
                    ->paginate(50);
                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
            }


        }




        /*If all search field provided*/
        if (!empty($month) && !empty($year) && !empty($class) && !empty($status)) {

            if($status=="due"){
                 $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.month','=',$month)
                    ->where('invoice.year','=',$year)
                    ->where('invoice.class_id','=',$class)
                    ->where('invoice.status','=',0)
                    ->paginate(50);

                return view('schoolpanel/dashboard/fees_collection_details')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
             }
             else{
                 $data =DB::table('invoice')
                    ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
                    ->select(DB::raw('invoice.class_id, COUNT(DISTINCT invoice.student_id) AS paid_total_students, SUM(transaction_lists.amount) AS paid_total_amount'))
                    ->groupBy('invoice.class_id')
                    ->where('invoice.school_id','=',$school_id)
                    ->where('invoice.month','=',$month)
                    ->where('invoice.year','=',$year)
                    ->where('invoice.class_id','=',$class)
                    ->where('invoice.status','=',1)
                    ->paginate(50);

                return view('schoolpanel/dashboard/fees_collection_report')->with(['data'=>$data,'title'=>$title,'classes_info'=>$classes_info,'sections'=>$sections]);
             }

        }






    }

    public function serachCollectionA(Request $request)
    {
        $month = $request->month;
        $year = $request->year;



        $title = "Collections";

        $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->select(DB::raw('invoice.school_id, SUM(transaction_lists.amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->where('invoice.month', '=', $month)
            ->where('invoice.year', '=', $year)
            ->where('invoice.status','=',1)
            ->paginate(50);
        return view('backend/fees_collection_report')->with(['data'=>$data,'title'=>$title]);
    }

    public function searchTotalDuesSchoolWise(Request $request)
    {
        $school_id = $request->school_id;
        if (!empty($school_id)) {
             $pabeTotal = DB::table('class_wise_fees')
                        ->select(DB::raw('school_id, SUM(amount) AS total_amount'))
                        ->groupBy('school_id')
                        ->where('school_id',$school_id)
                        ->whereYear('created_at', '=', date('Y'))
                        ->paginate(1);

            $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
            return view('backend/total_dues')->with(['datas'=>$pabeTotal,'school_info'=>$school_info]);
        }

        else{
            session()->flash("error", "Something went wrong,Please try again.");
            return back();
        }
    }

    public function PaidStudentsDetails($class_id)
   {
     //echo "Working";exit;
     $school_id = Auth::user()->school_id;

      $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->select('invoice.*','transaction_lists.trn_date','students.student_id','students.name')
            ->where('invoice.school_id', '=', $school_id)
            ->where('invoice.class_id', '=', $class_id)
            ->where('invoice.status','=',1)
            ->get();
      return response()->json(['data'=>$data]);

   }

    public function DueStudentsDetails($class_id)
   {
     //echo "Working";exit;
     $school_id = Auth::user()->school_id;

      $data =DB::table('invoice')
            ->join('transaction_lists', 'invoice.invoice_no', '=', 'transaction_lists.invoice_no')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->select('invoice.*','transaction_lists.trn_date','students.student_id','students.name')
            ->where('invoice.school_id', '=', $school_id)
            ->where('invoice.class_id', '=', $class_id)
            ->where('invoice.status','=',0)
            ->get();
      return response()->json(['data'=>$data]);

   }



    public function monthly_active_payments()
    {
        $title="Monthly active payment";
        $data =DB::table('invoice')
            ->join('school_infos', 'invoice.school_id', '=', 'school_infos.id')
            ->select(DB::raw('invoice.school_id,school_infos.school_name AS names, SUM(invoice.total_amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->groupBy('school_infos.school_name')
            ->where('invoice.month', '=', date('m'))
            ->where('invoice.status','!=',2)
            ->paginate(50);
        //dd($data);
        //return $data;
        return view('backend/monthly_active_payments')->with(['data'=>$data,'title'=>$title]);
    }

    public function monthly_active_payments_search(Request $request)
    {
        $month=$request->month;
        $title="hello";
        $data =DB::table('invoice')
            ->join('school_infos', 'invoice.school_id', '=', 'school_infos.id')
            ->select(DB::raw('invoice.school_id,school_infos.school_name AS names, SUM(invoice.total_amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->groupBy('school_infos.school_name')
            ->where('invoice.month', '=', $month)
            ->where('invoice.year', '=', date('Y'))
            ->where('invoice.status','!=',2)
            ->paginate(50);
        //dd($data);
        //return $data;
        return view('backend/monthly_active_payments')->with(['data'=>$data,'title'=>$title]);
    }


    public function monthly_payments_dues()
    {
        $title="hello";
        $data =DB::table('invoice')
            ->join('school_infos', 'invoice.school_id', '=', 'school_infos.id')
            ->select(DB::raw('invoice.school_id,school_infos.school_name AS names, SUM(invoice.total_amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->groupBy('school_infos.school_name')
            ->where('invoice.month', '=', date('m'))
            ->where('invoice.status','=',0)
            ->paginate(50);
        //dd($data);
        //return $data;
        return view('backend/monthly_due_payments')->with(['data'=>$data,'title'=>$title]);
    }

    public function monthly_payments_dues_search(Request $request)
    {
        $month=$request->month;
        $title="hello";
        $data =DB::table('invoice')
            ->join('school_infos', 'invoice.school_id', '=', 'school_infos.id')
            ->select(DB::raw('invoice.school_id,school_infos.school_name AS names, SUM(invoice.total_amount) AS paid_total_amount'))
            ->groupBy('invoice.school_id')
            ->groupBy('school_infos.school_name')
            ->where('invoice.month', '=', $month)
            ->where('invoice.year', '=', date('Y'))
            ->where('invoice.status','=',0)
            ->paginate(50);
        //dd($data);
        //return $data;
        return view('backend/monthly_due_payments')->with(['data'=>$data,'title'=>$title]);
    }

    public function new_onboard()
    {
        $school_list = SchoolInfo::orderBy("school_name","asc")->where('status','=',1)->whereMonth('created_at', '=', date('m'))->get();
        $divisions = SchoolDivision::orderBy("division_name","asc")->get();
        $districts = SchoolDistrict::orderBy("name","asc")->get();
        $school_posts = SchoolPost::orderBy("name","asc")->get();
        return view('backend/school-info/new_onboard_schools')->with(['schools'=>$school_list, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
    }

}
