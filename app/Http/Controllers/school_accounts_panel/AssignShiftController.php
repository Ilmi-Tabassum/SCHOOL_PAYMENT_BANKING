<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Http\Controllers\Controller;
use App\Models\schoolpanel\AssignShift;
use App\Models\shift;
use Illuminate\Http\Request;
use DB;

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
                $shifts = DB::select( DB::raw("
      SELECT shifts.name,assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            WHERE assign_shifts.status=2 AND assign_shifts.school_id=1"));
            }

        }else{
            // get != deteled data

                $shifts = DB::select( DB::raw("
            SELECT shifts.name,assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            WHERE assign_shifts.status !=2 AND assign_shifts.school_id=1"));


        }

      /*  $shifts = $shifts->paginate(10);*/

        return view('school_accounts_panel/assign_shift/assign_shift')->with(['myshifts' => $shifts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::all();
        return view('school_accounts_panel/assign_shift/create')->with(['shifts' => $shifts]);
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
        foreach($shifts as $value)
        {
            $check=DB::select(DB::raw("
            SELECT id
            FROM assign_shifts
            WHERE shift_id = $value And school_id=1"));



            if(!empty($check))
            {
                session()->flash("error", "We are sorry.shifts is not assigned for duplicate entry.");
                $return='';



            }
            else {


                $object = new AssignShift;
                $object->school_id = 1;
                $object->class_id = 1;
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
