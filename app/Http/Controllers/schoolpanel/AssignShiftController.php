<?php

namespace App\Http\Controllers\schoolpanel;
use App\Models\SchoolInfo;
use App\Models\Shift;
use App\Http\Controllers\Controller;
use App\Models\schoolpanel\AssignShift;
use Illuminate\Http\Request;
use DB;
use Auth;

class AssignShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get("gen")){
            // get delete data
            if($request->get("gen") == "trash"){
                $object = DB::select( DB::raw("
            SELECT shifts.name shname,class_infos.name classname,school_infos.school_name schoolname , assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            INNER JOIN class_infos ON class_infos.id = assign_shifts.class_id
            INNER JOIN school_infos ON school_infos.id = assign_shifts.school_id
           WHERE assign_shifts.status=2 "));
            }

        }else{
            // get != deteled data

            $object = DB::select( DB::raw("
            SELECT shifts.name shname,class_infos.name classname,school_infos.school_name schoolname, assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            INNER JOIN class_infos ON class_infos.id = assign_shifts.class_id
            INNER JOIN school_infos ON school_infos.id = assign_shifts.school_id
             WHERE assign_shifts.status !=2" ));


        }



      /*  $shifts = $shifts->paginate(10);*/

        return view('schoolpanel/assign_shift/assign_shift')->with(['myshifts' => $object]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->school_id)
        {
            $scid=Auth::user()->school_id;
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
            $shifts = Shift::all();
        }
        else{
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
            $shifts = Shift::all();
        }
        $schools=SchoolInfo::all();

        return view('schoolpanel/assign_shift/create')->with(['shifts' => $shifts,'classes' => $classes,'schools' => $schools]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shifts=$request->shift_name;
        if(Auth::user()->school_id)
        {
            $school_id = Auth::user()->school_id;
        }else{
            $school_id = $request->school_id;
        }

        $classid=$request->class_id;
        if($classid && $shifts && $school_id)
        {
            foreach($shifts as $value)
            {
                $check=DB::select(DB::raw("
            SELECT id
            FROM assign_shifts
            WHERE shift_id = $value And school_id=$school_id And class_id=$classid"));



                if(!empty($check))
                {
                    session()->flash("error", "We are sorry.shifts is not assigned for duplicate entry.");
                    $return='';



                }
                else {


                    $object = new AssignShift;
                    $object->school_id = $school_id;
                    $object->class_id = $classid;
                    $object->shift_id = $value;
                    $object->status = 1;


                    try {
                        $object->save();
                        $return = 'true';
                        session()->flash("success", "shift assigned successfully!");
                    } catch (\Illuminate\Database\QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        if ($errorCode == '1062') {
                            session()->flash("error", "We are sorry.shift is not assigned for duplicate entry.");
                        }
                        $return = "";
                    }

                }

            }
        }else
        {
            session()->flash("error", "Please try again");
            $return="false";
        }

        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Assignshift  $assignshift
     * @return \Illuminate\Http\Response
     */
    public function show(Assignshift $assignshift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Assignshift  $assignshift
     * @return \Illuminate\Http\Response
     */
    public function edit(Assignshift $assignshift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assignshift  $assignshift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assignshift $assignshift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assignshift  $assignshift
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object = Assignshift::find($id);
        $object->status = 2;
        $object->save();

        session()->flash("success", "shift is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = Assignshift::find($id);
        $object->status = 1;
        $object->save();

        session()->flash("success", "shift is removed from successfully!");

        return back();
    }
}
