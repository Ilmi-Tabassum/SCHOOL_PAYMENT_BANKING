<?php

namespace App\Http\Controllers;

use App\Models\FeesSubHead;
use Illuminate\Http\Request;
use DB;
use Auth;

class FeesSubHeadController extends Controller
{
    public function index(Request $request)
    {
       $fees_heads = DB::select( DB::raw("
        SELECT id,fees_head_name FROM  fees_heads
        WHERE status =1
        ORDER BY fees_head_name ASC"));

        if($request->get("gen")){
            if($request->get("gen") == "trash"){
                $fees_sub_head = DB::table('fees_sub_heads')
                ->join('fees_heads', 'fees_sub_heads.fees_head_id', '=', 'fees_heads.id')
                ->select('fees_sub_heads.*', 'fees_heads.fees_head_name')
                ->where('fees_sub_heads.status','=',2)
                ->paginate(11);
                 return view('backend/subhead')->with(['fees_heads' => $fees_heads,'fees_sub_head' => $fees_sub_head]);
            }
           
        }

        else{
            $fees_sub_head = DB::table('fees_sub_heads')
                ->join('fees_heads', 'fees_sub_heads.fees_head_id', '=', 'fees_heads.id')
                ->select('fees_sub_heads.*', 'fees_heads.fees_head_name')
                ->where('fees_sub_heads.status','!=',2)
                ->paginate(11);
            return view('backend/subhead')->with(['fees_heads' => $fees_heads,'fees_sub_head' => $fees_sub_head]); 
        } 
    }

   
    public function store(Request $request)
    {
        if($request->post("hidden_menu_id")){

            $data = FeesSubHead::find($request->post("hidden_menu_id"));
            
            $data->fees_head_id = $request->fees_head_id;
            $data->fees_subhead_name = $request->fees_subhead_name;
            $data->updated_by = Auth::user()->id;
            try {
                $data->save();
                session()->flash("success", "Subhead Name is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Subhead Name is not updated for duplicate entry.");
                }
            }
        }else{
            $data = new FeesSubHead;
            $data->fees_head_id = $request->fees_head_id;
            $data->fees_subhead_name = $request->fees_subhead_name;
            $data->created_by = Auth::user()->id;
            try {
                $data->save();
                session()->flash("success", "Subhead Name is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Subhead Name is not added for duplicate entry.");
                }
            }
        }

        return back();
    }

   
    public function destroy($id)
    {
        $data = FeesSubHead::find($id);
        $data->status = 2;
        $data->deleted_by = Auth::user()->id;
        $data->save();
        session()->flash("success", "Subhead Name is move to trash");
        return back();
    }

    public function edit_subhead_ajax($id){
      $data = FeesSubHead::find($id);
      return $data;
    }

     public function update_status_fsh($id){
        $data = FeesSubHead::find($id);
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
