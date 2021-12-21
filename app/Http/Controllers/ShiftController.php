<?php

namespace App\Http\Controllers;

use App\Models\schoolpanel\AssignShift;
use App\Models\Session;
use App\Models\Shift;
use Illuminate\Http\Request;
use Auth;
use DB;


class ShiftController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {
        if(Auth::user()->school_id)
        {
            $school_id = Auth::user()->school_id;

            if($request->get("gen")){
                // get delete data
                if($request->get("gen") == "trash"){
             $object = DB::select( DB::raw("
            SELECT shifts.name shname,class_infos.name classname,assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            INNER JOIN class_infos ON class_infos.id = assign_shifts.class_id
            WHERE assign_shifts.status=2 AND assign_shifts.school_id=$school_id"));
                }

            }else{
                // get != deteled data

                $object = DB::select( DB::raw("
            SELECT shifts.name shname,class_infos.name classname,assign_shifts.status status ,assign_shifts.shift_id,assign_shifts.id id
            FROM assign_shifts
            INNER JOIN shifts ON assign_shifts.shift_id = shifts.id
            INNER JOIN class_infos ON class_infos.id = assign_shifts.class_id
            WHERE assign_shifts.status !=2 AND assign_shifts.school_id=$school_id"));


            }

            /*  $shifts = $shifts->paginate(10);*/

            return view('backend/shift_info')->with(['myshifts' => $object]);
        }else{
            if($request->get("gen")){
                // get delete data
                if($request->get("gen") == "trash"){
                    $object = Shift::where("status", "=", 2);
                }
            }else{
                // get != deteled data
                $object = Shift::where("status", "!=", 2);
            }

            $object = $object->paginate(10);

            return view('backend/shift_info')->with(['myshifts' => $object]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
         if($request->post("hidden_shift_id")){
            $object = Shift::find($request->post("hidden_shift_id"));

            $object->name = $request->shift_name;
            $object->description = $request->description;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->updated_by = Auth::user()->id;


            try {
                $object->save();
                session()->flash("success", "Shift [" . $request->shift_name . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Shift [" . $request->shift_name . "] is already exist.");
                }
            }
        }else{
            $object = new Shift;
            $object->name = $request->shift_name;
            $object->description = $request->description;
            $object->start_time = $request->start_time;
            $object->end_time = $request->end_time;
            $object->created_by = Auth::user()->id;
            $object->created_at = date('Y-m-d H:i:s');


            try {
                $object->save();
                session()->flash("success", "Shift [" . $request->shift_name . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Shift [" . $request->shift_name . "] is already exist.");
                }
            }
        }


        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuSetup  $menuSetup
     * @return \Illuminate\Http\Response
     */
    public function show(MenuSetup $menuSetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuSetup  $menuSetup
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuSetup $menuSetup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuSetup  $menuSetup
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuSetup  $menuSetup
     * @return \Illuminate\Http\Response
     */
/*    public function destroy($id)
    {
        $object = Shift::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Shift is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = Shift::find($id);
        // Update status
        $object->status = 1;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Shift removed from  trash successfully!");

        return back();
    }*/

    public function destroy($id)
    {
        if(Auth::user()->school_id) {
            $object = Assignshift::find($id);
            $object->status = 2;
            $object->save();
        }else{
            $object = Shift::find($id);
            $object->status = 2;
            $object->save();
        }


        session()->flash("success", "shift is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        if(Auth::user()->school_id) {
            $object = Assignshift::find($id);
            $object->status = 1;
            $object->save();
        }else{
            $object = Shift::find($id);
            $object->status = 1;
            $object->save();
        }

        session()->flash("success", "shift is removed from successfully!");

        return back();
    }

    public function loading_shift_info_item_ajax_hit($id){
        $object = Shift::find($id);
        return $object;
    }
}
