<?php

namespace App\Http\Controllers;

use App\Models\AssignClass;
use Illuminate\Http\Request;
use DB;
use Auth;

class AssignClassController extends Controller
{

    public function index(Request $request)
    {

        if($request->get("gen")){
            if($request->get("gen") == "trash"){

             $assignClasses = DB::table('assign_classes')
            ->join('school_infos', 'assign_classes.school_id', '=', 'school_infos.id')
            ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
            ->select('assign_classes.*', 'school_infos.school_name', 'class_infos.name')
            ->where('assign_classes.status', '=',2)
            ->paginate(11);

            }
        }
        else{

            $assignClasses = DB::table('assign_classes')
            ->join('school_infos', 'assign_classes.school_id', '=', 'school_infos.id')
            ->join('class_infos', 'assign_classes.class_id', '=', 'class_infos.id')
            ->select('assign_classes.*', 'school_infos.school_name', 'class_infos.name')
            ->where('assign_classes.status', '!=',2)
            ->paginate(11);

        }



        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        $class_info = DB::select( DB::raw("SELECT id,name FROM  class_infos"));
        return view('backend/assign_class')->with(['assignClasses' => $assignClasses,'school_info' => $school_info,'class_info'=>$class_info]);

    }




    public function store(Request $request)
    {
        if(isset(Auth::user()->school_id))
        {
            $schid=Auth::user()->school_id;
        }
        else{
            $schid=$request->school_id;

        }
        if($request->post("hidden_menu_id")){
            $check = AssignClass::where('school_id',$schid)
                ->where('class_id',$request->class_id)
                ->first();

            if (!empty($check)) {
                session()->flash("error", "Item is duplicate");
            }

            else{
                $data = AssignClass::find($request->post("hidden_menu_id"));
                $data->school_id = $schid;
                $data->class_id = $request->class_id;
                $data->updated_by = Auth::user()->id;

                try {
                    $data->save();
                    session()->flash("success", "Item is updated successfully");
                } catch(\Illuminate\Database\QueryException $e){
                    $errorCode = $e->errorInfo[1];
                    if($errorCode == '1062'){
                        session()->flash("error", "We are sorry. Item is not updated for duplicate entry.");
                    }
                }
            }

        } //end if block --update

        else{
             $class_ids = $request->class_id;
             $size = count($class_ids);


             for ($i=0; $i <$size ; $i++) {
                 $check = AssignClass::where('school_id',$schid)
                    ->where('class_id',$class_ids[$i])
                    ->first();

            if (!empty($check)) {
                session()->flash("error", "Item is duplicate");
            }

            else{
                $data = new AssignClass;
                $data->school_id = $schid;
                $data->class_id = $class_ids[$i];
                $data->created_by = Auth::user()->id;
                $data->save();
                session()->flash("success", "New Item is created successfully!");

            }

             }//end for loop

        } //end else block --insert

        return back();
    }


    public function destroy($id)
    {
        $data = AssignClass::find($id);
        $data->status = 2;
        $data->deleted_by = Auth::user()->id;
        $data->save();
        session()->flash("success", "Item is move to trash");
        return back();
    }

    public function edit_assign_class_ajax($id){
        $data = AssignClass::find($id);
        return $data;
    }

    public function update_status($id){
        $data = AssignClass::find($id);
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
