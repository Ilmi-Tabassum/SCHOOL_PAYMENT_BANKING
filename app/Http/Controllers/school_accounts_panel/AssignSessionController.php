<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Http\Controllers\Controller;
use App\Models\AssignSection;
use App\Models\schoolpanel\AssignSession;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;
use DB;

class AssignSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get("gen")) {
            // get delete data
            if ($request->get("gen") == "trash") {
                $session = DB::select(DB::raw("
            SELECT sessions.name,assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            WHERE assign_sessions.status=2 AND assign_sessions.school_id=1"));
            }

        } else {
            // get != deteled data

            $session = DB::select(DB::raw("
            SELECT sessions.name,assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            WHERE assign_sessions.status != 2 AND assign_sessions.school_id=1"));

        }

        /*$object = $object->paginate(10);*/
        return view('school_accounts_panel/assign_session/assign_session')->with(['mysessions' => $session]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sessions = Session::all();
        return view('school_accounts_panel/assign_session/create')->with(['sessions' => $sessions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sessions=$request->session_name;
        foreach($sessions as $value)
        {
            $check=DB::select(DB::raw("
            SELECT id
            FROM assign_sessions
            WHERE session_id = $value And school_id=1"));



            if(!empty($check))
            {
                session()->flash("error", "We are sorry.sessions is not assigned for duplicate entry.");
                $return='';



            }
            else {

                $object = new AssignSession;
                $object->school_id = 1;
                $object->class_id = 1;
                $object->session_id = $value;
                $object->status = 1;


                try {
                    $object->save();
                    $return = 'true';
                    session()->flash("success", "Session assigned successfully!");
                } catch (\Illuminate\Database\QueryException $e) {
                    $errorCode = $e->errorInfo[1];
                    if ($errorCode == '1062') {
                        session()->flash("error", "We are sorry.Session is not assigned for duplicate entry.");
                    }
                    $return = '';
                }


            }
        }
        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssignSession  $assignSession
     * @return \Illuminate\Http\Response
     */
    public function show(AssignSession $assignSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssignSession  $assignSession
     * @return \Illuminate\Http\Response
     */
    public function edit(AssignSession $assignSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssignSession  $assignSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssignSession $assignSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssignSession  $assignSession
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object = AssignSession::find($id);
        $object->status = 2;
        $object->save();

        session()->flash("success", "Session is move to trash successfully!");

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
}
