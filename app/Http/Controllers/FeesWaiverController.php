<?php

namespace App\Http\Controllers;

use App\Models\FeesWaiver;
use Illuminate\Http\Request;
use DB;
use Auth;

class FeesWaiverController extends Controller
{

    public function index()
    {
        
        $scid=Auth::user()->school_id;
       // $years = DB::select(DB::raw("SELECT id,name,year FROM sessions"));

         $years = DB::select(DB::raw("SELECT DISTINCT assign_sessions.session_id,sessions.name,sessions.id FROM assign_sessions INNER JOIN sessions ON assign_sessions.session_id = sessions.id WHERE assign_sessions.school_id=$scid"));
         //dd($years);

        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        return view('backend/fees_waiver')->with(['classes'=>$classes,'years'=>$years]);
    }


    public function getWaiverInfo(Request $request)
    {
         $student_id = $request->student_id;
         $class_id = $request->class_id;
         $year_id = $request->year_id;


         $school_id = Auth::user()->school_id;


         
         $student_details = DB::table("student_academics")
                ->join('students', 'student_academics.student_id', '=', 'students.id')
                ->join('class_infos', 'student_academics.class_id', '=', 'class_infos.id')
                ->join('school_infos', 'student_academics.school_id', '=', 'school_infos.id')
                ->join('shifts', 'student_academics.shift_id', '=', 'shifts.id')
                ->join('sections', 'student_academics.section_id', '=', 'sections.id')
                ->select('students.name as student_name', 'class_infos.name as class_name', 'school_infos.school_name','shifts.name as shift_name','sections.name as section_name','student_academics.session_id')
                ->where('student_academics.student_id', $student_id)
                ->get();


          // $data = DB::table("fees_heads")
          //       ->join('class_wise_fees', 'fees_heads.id', '=', 'class_wise_fees.fees_id')
          //       ->select('fees_heads.id', 'fees_heads.fees_head_name', 'class_wise_fees.amount')
          //       ->where('class_wise_fees.school_id',$school_id)
          //       ->where('class_wise_fees.class_id',$class_id)
          //       ->where('class_wise_fees.year_id',$year_id)
          //       ->orderBy('fees_heads.fees_head_name', 'ASC')
          //       ->get();

          $data = DB::table("fees_heads")
                ->join('assign_particulars', 'fees_heads.id', '=', 'assign_particulars.fees_head_id')
                ->select('fees_heads.id', 'fees_heads.fees_head_name', 'assign_particulars.amount')
                ->where('assign_particulars.school_id',$school_id)
                ->where('assign_particulars.class_id',$class_id)
                ->where('assign_particulars.year_id',$year_id)
                ->orderBy('fees_heads.fees_head_name', 'ASC')
                ->get();



          // dd($data);

               // var_dump($data);
        // $waivers = DB::select(DB::raw("SELECT * FROM fees_waivers WHERE class_id = $class_id AND student_id=$student_id AND year_id=$year_id"));

       return response()->json(['data' => $data,'student_details'=>$student_details]);
    }



    public function store(Request $request)
    {
         foreach($request->fees_id as $id) {

            $check = FeesWaiver::where('class_id',$request->class)
                      ->where('year_id',$request->year)
                      ->where('student_id',$request->student_id)
                      ->where('fees_id',$id)
                      ->first();

            if(!empty($check)){
                $total=0;
                $data=array();

                $fees_amount=$request['fees_amount_'.$id];
                $paid_waiver_amount=$request['paid_waiver_amount_'.$id];
                $discount_amount=$request['discount_amount_'.$id];

                if(!empty($discount_amount) ){
                    $data['fees_amount']=$fees_amount;
                    $total+=$data['fees_amount'];

                    $data['paid_waiver_amount']=$paid_waiver_amount;
                    $total+=$data['paid_waiver_amount'];

                    $data['discount_amount']= $discount_amount;
                    $total+=$data['discount_amount'];

                    $data['updated_by'] = Auth::user()->id;

                    $check->update($data);
                }
            }
            else{
                $data=array();

                $fees_amount=$request['fees_amount_'.$id];
                $paid_waiver_amount=$request['paid_waiver_amount_'.$id];
                $discount_amount=$request['discount_amount_'.$id];
                 if(!empty($discount_amount) ){
                    $arr['year_id'] = $request->year;
                    $arr['class_id'] = $request->class;
                    $arr['student_id'] = $request->student_id;
                    $arr['fees_amount']=$fees_amount;
                    $arr['paid_waiver_amount']=$paid_waiver_amount;
                    $arr['discount_amount']=$discount_amount;
                    $arr['fees_id']=$id;
                    $arr['created_by'] = Auth::user()->id;
                    FeesWaiver::create($arr);
                 }

            }


        }
        session()->flash("success", "Operation successful");
        return back();
    }


    public function fetch_fees_waivers($year_id,$class_id,$student_id)
    {
        $data = DB::table('fees_waivers')
            ->select('id','fees_id','paid_waiver_amount','discount_amount')
            ->where('student_id', '=',$student_id)
            ->where('class_id', '=',$class_id)
            ->where('year_id', '=',$year_id)
            ->get();

        return $data;
    }
}
