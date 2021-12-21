<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Models\ClassWiseFees;
use Illuminate\Http\Request;
use DB;
use Auth;

class MyClassWiseFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_info = DB::select( DB::raw("SELECT DISTINCT id,school_name FROM  school_infos"));
        $years = DB::select(DB::raw("SELECT id,name,year FROM sessions"));
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));

        $sub_head = DB::select( DB::raw("SELECT id,fees_subhead_name FROM fees_sub_heads WHERE status = 1"));

        return view('schoolpanel/myclass_wise_fees/myclass_wise_fees')->with(['school_info' => $school_info,'sub_head'=>$sub_head,'years'=>$years,'classes'=>$classes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach($request->fees_id as $id) {

            $check = ClassWiseFees::where('school_id',$request->school_id)
                ->where('class_id',1)
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

            else{
                $data=array();

                $amount = $request['amount_'.$id];
                $arr['school_id'] = $request->school_id;
                $arr['class_id'] = $request->class;
                $arr['year_id'] = $request->year;
                $arr['fees_id']=$id;
                $arr['amount']=$amount;
                $arr['created_by'] = Auth::user()->id;
                if (!empty($amount)) {
                    ClassWiseFees::create($arr);
                }
            }


        }
        session()->flash("success", "Operation successfully!");
        return back();
    }

    public function retrieve_fees_amount($school_id,$class_id,$year_id)
    {
        $data = DB::table('myclass_wise_fees')
            ->select('amount','id','school_id','class_id','year_id','fees_id')
            ->where('school_id', '=',1)
            ->where('class_id', '=',$class_id)
            ->where('year_id', '=',$year_id)
            ->get();
        return $data;

    }
}
