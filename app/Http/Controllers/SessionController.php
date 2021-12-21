<?php

namespace App\Http\Controllers;

use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSession;
use App\Models\Session;
use Illuminate\Http\Request;
use Auth;
use DB;


class SessionController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {
        if(Auth::user()->school_id)
        {
            $scid=Auth::user()->school_id;

            if ($request->get("gen")) {
                // get delete data
                if ($request->get("gen") == "trash") {
              $object = DB::select(DB::raw("
            SELECT sessions.name sessname,class_infos.name classname,assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            INNER JOIN class_infos ON class_infos.id = assign_sessions.class_id
             WHERE assign_sessions.status=2 AND assign_sessions.school_id=$scid"));
                }

            } else {
                // get != deteled data

                $object = DB::select(DB::raw("
            SELECT sessions.name sessname,class_infos.name classname,assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            INNER JOIN class_infos ON class_infos.id = assign_sessions.class_id
             WHERE assign_sessions.status !=2 AND assign_sessions.school_id=$scid"));

            }

            /*$object = $object->paginate(10);*/
            return view('backend/session_info')->with(['sessions' => $object]);
        }
        else
        {
            if($request->get("gen")){
                // get delete data
                if($request->get("gen") == "trash"){
                    $object = Session::where("status", "=", 2);
                }
            }else{
                // get != deteled data
                $object = Session::where("status", "!=", 2);
            }

            $object = $object->paginate(10);

            return view('backend/session_info')->with(['sessions' => $object]);
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
         if($request->post("hidden_session_id")){
            $object = Session::find($request->post("hidden_session_id"));

            $object->name = $request->session_name;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->updated_by = Auth::user()->id;


            try {
                $object->save();
                session()->flash("success", "Session [" . $request->session_name . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Session [" . $request->session_name . "] is already exist.");
                }
            }
        }else{
            $object = new Session;
            $object->name = $request->session_name;
            $object->created_by = Auth::user()->id;
            $object->created_at = date('Y-m-d H:i:s');


            try {
                $object->save();
                session()->flash("success", "Session [" . $request->session_name . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Session [" . $request->session_name . "] is already exist.");
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
        $object = Session::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Session is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = Session::find($id);
        // Update status
        $object->status = 1;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Session removed from  trash successfully!");

        return back();
    }*/
    public function destroy($id)
    {
        $object = AssignSession::find($id);
        $object->status = 2;
        $object->save();

        session()->flash("success", "Session is move to  trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = AssignSession::find($id);
        $object->status = 1;
        $object->save();

        session()->flash("success", "Session is removed from successfully!");

        return back();
    }


    public function loading_session_info_item_ajax_hit($id){
        $object = Session::find($id);
        return $object;
    }
}
