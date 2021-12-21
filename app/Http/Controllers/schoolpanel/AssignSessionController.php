<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Models\AssignSection;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSession;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;
use DB;
use Auth;

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
            SELECT sessions.name shname,class_infos.name classname,school_infos.school_name schoolname , assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            INNER JOIN class_infos ON class_infos.id = assign_sessions.class_id
            INNER JOIN school_infos ON school_infos.id = assign_sessions.school_id
           WHERE assign_sessions.status=2 "));
            }

        } else {
            // get != deteled data

            $session = DB::select(DB::raw("
            SELECT sessions.name shname,class_infos.name classname,school_infos.school_name schoolname , assign_sessions.status status ,assign_sessions.session_id,assign_sessions.id id
            FROM assign_sessions
            INNER JOIN sessions ON assign_sessions.session_id = sessions.id
            INNER JOIN class_infos ON class_infos.id = assign_sessions.class_id
            INNER JOIN school_infos ON school_infos.id = assign_sessions.school_id
           WHERE assign_sessions.status !=2 "));

        }

        /*$object = $object->paginate(10);*/
        return view('schoolpanel/assign_session/assign_session')->with(['mysessions' => $session]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->school_id) {
            $scid = Auth::user()->school_id;
            $classes = DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        }else {
            $classes = DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
        }
        $sessions = Session::all();
        $schools=SchoolInfo::all();
        return view('schoolpanel/assign_session/create')->with(['sessions' => $sessions,'classes' => $classes,'schools' => $schools]);
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
        if(Auth::user()->school_id)
        {
            $school_id = Auth::user()->school_id;
        }else{
            $school_id = $request->school_id;
        }
        $classid=$request->class_id;
        if($classid && $sessions && $school_id){
            foreach($sessions as $value)
            {
                $check=DB::select(DB::raw("
            SELECT id
            FROM assign_sessions
            WHERE session_id = $value And school_id=$school_id And class_id=$classid"));



                if(!empty($check))
                {
                    session()->flash("error", "We are sorry.sessions is not assigned for duplicate entry.");
                    $return='';



                }
                else {

                    $object = new AssignSession;
                    $object->school_id = $school_id;
                    $object->class_id = $classid;
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

        } else{
            session()->flash("error", "Please try again");
            $return="false";
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
