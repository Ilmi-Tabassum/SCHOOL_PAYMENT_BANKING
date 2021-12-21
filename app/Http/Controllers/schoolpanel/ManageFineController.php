<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\schoolpanel\ManageFine;
use Auth;
use DB;
use Response;
class ManageFineController extends Controller
{

    public function GoManageFinePage()
    {
    	$school_id = Auth::user()->school_id;
    	$fines = DB::table('managefines')
            ->join('students', 'managefines.student_id', '=', 'students.id')
            ->join('fees_heads', 'managefines.head_id', '=', 'fees_heads.id')
            ->join('class_infos', 'managefines.class_id', '=', 'class_infos.id')
            ->select('managefines.*', 'students.student_id as full_student_id', 'fees_heads.fees_head_name','students.name','class_infos.name as class_name')
            ->where('school_id','=',$school_id)
            ->paginate(50);


        //dd($fines);

        $classes = DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));    	//dd($classes);


    	$heads = DB::select(DB::raw("SELECT id,fees_head_name FROM fees_heads ORDER BY fees_head_name ASC"));
    	//dd($heads);

    	return view('schoolpanel/manage-fine')->with(['heads'=>$heads,'fines'=>$fines,'classes'=>$classes]);
    }


    public function StoreFine(Request $request)
    {

		if($request->post("updateFine")){
	        $data = ManageFine::find($request->post("updateFine"));
	        $data->class_id = $request->class_namee;
	        $data->student_id = $request->student_idd;
	    	$data->school_id = Auth::user()->school_id;
	    	$data->year = Date('Y');
	    	$data->month = $request->month;
	    	$data->amount = $request->amount;
	    	$data->head_id = $request->head_id;
	    	$data->reasons = $request->reasons;
	        try {
	            $data->save();
	            session()->flash("success", "Fine Details Updated Successfully");
	            return back();
	        } catch(\Illuminate\Database\QueryException $e){
	            $errorCode = $e->errorInfo[1];
	            if($errorCode == '1062'){
	                session()->flash("error", "Fine Details is not updated");
	                return back();
	            }
	        }
	    }
	    else{
	        $student_id= $request->student_idd;
	        $month= $request->month;

	        $check=DB::select(DB::raw("SELECT * FROM invoice WHERE student_id=$student_id AND month=$month"));

	        if(empty($check))
            {
                $data = new ManageFine;
                $data->class_id = $request->class_namee;
                $data->student_id = $request->student_idd;
                $data->school_id = Auth::user()->school_id;
                $data->year = Date('Y');
                $data->month = $request->month;
                $data->amount = $request->amount;
                $data->head_id = $request->head_id;
                $data->reasons = $request->reasons;
                $data->save();
                session()->flash("success", "Fine Added Successfully.");
            }else{
                session()->flash("error", "Invoice has already been generated");

            }

	    	return back();
	    }

    }


    public function EditFine($id)
    {
    	$data = ManageFine::find($id);
    	return $data;
    }


    public function DeleteFineDetails($id)
    {
    	$data = DB::select(DB::raw("DELETE FROM managefines WHERE id=$id"));
    	session()->flash("success", "Fine Details Deleted Successfully.");
    	return back();
    }


    public function FindClassWiseStudents($class_id)
    {
    	$school_id = Auth::user()->school_id;
    	$students = DB::select(DB::raw("SELECT sa.student_id as id,students.student_id as student_fullid
										FROM student_academics as sa
										INNER JOIN students
										ON sa.student_id = students.id
										WHERE sa.school_id=$school_id AND sa.class_id = $class_id"));
    	$size = count($students);

    	if ($size>0) {
    		return response()->json(['hasStudent' => '1', 'students' =>$students]);
    	}
    	else{
    		return response()->json(['hasStudent' => '0']);
    	}


    }

    public function class_wise_student(Request $request){
     	$school_id = Auth::user()->school_id;
        $students = DB::select(DB::raw("SELECT sa.student_id as id,students.student_id as student_fullid
										FROM student_academics as sa
										INNER JOIN students
										ON sa.student_id = students.id
										WHERE sa.school_id=$school_id AND sa.class_id = ".$request->class_id));

        $selected_box  = '';
        $selected_box .= "<option value=''>Select Student ID</option>";

        if(count($students) > 0){
            foreach ($students as $key => $value) {
                $selected_box .= '<option value="'.$value->id.'">'.$value->student_fullid.'</option>';
            }
        }
        return Response::json($selected_box);
    }






}
