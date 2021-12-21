<?php

namespace App\Http\Controllers;

use App\Models\SchoolType;
use Illuminate\Http\Request;
use DB;
use Auth;

class SchoolTypeController extends Controller
{
    
    public function index()
    {
        $school_types = SchoolType::paginate(50);
        return view('backend/school_type')->with(['school_types' => $school_types]);
    }

    public function store(Request $request)
    {

        if($request->post("hidden_school_type_id")){
            $object = SchoolType::find($request->post("hidden_school_type_id"));
            
            $object->school_type = $request->school_type;
            $object->status = $request->school_status;

            try {
                $object->save();
                session()->flash("success", "School Type is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. School Type is not updated for duplicate entry.");
                }
            }
        }else{
            $object = new SchoolType;
            $object->school_type = $request->school_type;
            $object->status = $request->school_status;

            try {
                $object->save();
                session()->flash("success", "New School Type is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "School Type is not added for duplicate entry.");
                }
            }
        }

        return back();
    }

   

    public function editSchoolType($id){
        $data = SchoolType::find($id);
        return $data;
    }

    public function destroy($id)
    {
        $data = DB::select(DB::raw("DELETE FROM school_types WHERE id=$id"));
        session()->flash("success", "School Type is Deleted successfully");
        return back();
    }


}
