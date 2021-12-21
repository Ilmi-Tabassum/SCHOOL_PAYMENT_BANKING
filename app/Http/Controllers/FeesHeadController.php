<?php

namespace App\Http\Controllers;

use App\Models\FeesHead;
use App\Models\schoolpanel\AssignParticular;
use Illuminate\Http\Request;
use DB;
use Auth;

class  FeesHeadController extends Controller
{

    public function index(Request $request)
    {
        $school_id = Auth::User()->school_id;
        if($request->get("gen")){
            if($request->get("gen") == "trash"){
                $fees_heads = AssignParticular::where("status", "=", 2)->where("school_id","=",$school_id);
            }
        }
        else{
            $fees_heads =  DB::select( DB::raw("SELECT DISTINCT fees_heads.id,fees_head_name,fees_heads.status
FROM fees_heads
JOIN assign_particulars ON  assign_particulars.fees_head_id=fees_heads.id
WHERE fees_heads.status = 1 AND assign_particulars.school_id = $school_id"));;
        }

       // $fees_heads = $fees_heads->paginate(30);

       return view('backend/feeshead')->with(['fees_heads' => $fees_heads]);
    }


    public function store(Request $request)
    {
         if($request->post("hidden_menu_id")){

            $data = FeesHead::find($request->post("hidden_menu_id"));

            $data->fees_head_name = $request->fees_head_name;
            $data->updated_by = Auth::user()->id;
            try {
                $data->save();
                session()->flash("success", "Particular is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Particular is not updated for duplicate entry.");
                }
            }
        }

        else{
            $data = new FeesHead;
            $data->fees_head_name = $request->fees_head_name;
            $data->created_by = Auth::user()->id;
            try {
                $data->save();
                session()->flash("success", "Particular is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Particular is not added for duplicate entry.");
                }
            }
        }

        return back();
    }



    public function destroy($id)
    {
        if(Auth::user()->school_id)
        {
            $data = FeesHead::find($id);
            $data->status = 2;
            $data->deleted_by = Auth::user()->id;
            $data->save();
            session()->flash("success", "Particular is move to trash");
        }else{
            $data = FeesHead::find($id);
            $data->status = 2;
            $data->deleted_by = Auth::user()->id;
            $data->save();
            session()->flash("success", "Particular is move to trash");
        }

        return back();
    }
    public function restore($id)
    {
        if(Auth::user()->school_id)
        {
            $data = FeesHead::find($id);
            $data->status = 1;
            $data->deleted_by = Auth::user()->id;
            $data->save();
            session()->flash("success", "Particular is move to trash");
        }else{
            $data = FeesHead::find($id);
            $data->status = 1;
            $data->deleted_by = Auth::user()->id;
            $data->save();
            session()->flash("success", "Particular is move to trash");
        }
        return back();
    }

    public function edit_ajax($id){
        $head=AssignParticular::find($id);
        $data = FeesHead::find($head->fees_head_id);
        return $data;
    }

     public function update_status_fh($id){
        $data = FeesHead::find($id);
        $present_status = $data->status;
        if ($present_status == 0 || $present_status ==2) {
            $data->status = 1;
            $data->update();
        }
        else if($data->status ==1 ){
            $data->status = 0;
            $data->update();

        }
        return $data;
    }



}
