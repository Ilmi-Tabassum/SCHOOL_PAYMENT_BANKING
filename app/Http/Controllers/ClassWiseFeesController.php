<?php

namespace App\Http\Controllers;

use App\Models\ClassWiseFees;
use App\Models\FeesSubHead;
use App\Models\FeesWaiver;
use App\Models\Student;
use App\Models\schoolpanel\AssignParticular;
use Illuminate\Http\Request;
use DB;
use Auth;

class ClassWiseFeesController extends Controller
{

    public function index()
    {
        $school_id = Auth::user()->school_id;
        // $years = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $years = DB::select(DB::raw("SELECT DISTINCT assign_sessions.session_id,sessions.name FROM assign_sessions INNER JOIN sessions ON assign_sessions.session_id = sessions.id WHERE assign_sessions.school_id=$school_id"));
        //dd($years);

        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id, class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $particulars = DB::select( DB::raw("SELECT id,fees_head_name FROM fees_heads WHERE status = 1"));

        //dd($particulars);

        return view('backend/class_wise_fees')->with(['particulars'=>$particulars,'years'=>$years,'classes'=>$classes]);
    }



    public function store(Request $request)
    {
        //dd($request->all());
        $school_id = Auth::user()->school_id;
        $given_head_sum= (int)$request->total_sum;


        foreach($request->fees_id as $id) {

            $check = AssignParticular::where('school_id',$school_id)
                    ->where('class_id',$request->class)
                    ->where('year_id',$request->year)
                    ->where('fees_head_id',$id)
                    ->first();

            if(!empty($check)){
                $total=0;
                $data=array();
                $amount = $request['amount_'.$id];
                $data['amount']=$amount;
                $total+=$data['amount'];
                $data['updated_by'] = Auth::user()->id;
                $arr['updated_at'] = date('Y-m-d H:i:s');
                if(!empty($amount)){
                    $check->update($data);
                }
             }

            else{
                $data=array();
                $arr['fees_head_id']=$id;
                $arr['school_id'] = $school_id;
                $arr['status'] = 1;
                $arr['year_id'] = $request->year;
                $arr['class_id'] = $request->class;
                $amount = $request['amount_'.$id];
                $arr['amount']=$amount;
                $arr['created_by'] = Auth::user()->id;
                $arr['created_at'] = date('Y-m-d H:i:s');
                if (!empty($amount)) {
                   AssignParticular::create($arr);
                }
            }
        }


        // foreach($request->fees_id as $id) {

        //     $check = ClassWiseFees::where('school_id',$school_id)
        //             ->where('class_id',$request->class)
        //             ->where('year_id',$request->year)
        //             ->where('fees_id',$id)
        //             ->first();

        //     if(!empty($check)){
        //         $total=0;
        //         $data=array();
        //         $amount = $request['amount_'.$id];
        //         $data['amount']=$amount;
        //         $total+=$data['amount'];
        //         $data['updated_by'] = Auth::user()->id;
        //         if(!empty($amount)){
        //             $check->update($data);
        //         }
        //      }

        //     else{
        //         $data=array();
        //         $payment_id = $school_id.date('Y').$request->class.$request->month;
        //         $amount = $request['amount_'.$id];
        //         $arr['school_id'] = $school_id;
        //         $arr['class_id'] = $request->class;
        //         $arr['year_id'] = $request->year;
        //         $arr['fees_id']=$id;
        //         $arr['amount']=$amount;
        //         $arr['payment_id']=$payment_id;
        //         $arr['created_by'] = Auth::user()->id;
        //         if (!empty($amount)) {
        //            ClassWiseFees::create($arr);
        //         }
        //     }
        // }

        session()->flash("success", "Operation successfully!");
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
