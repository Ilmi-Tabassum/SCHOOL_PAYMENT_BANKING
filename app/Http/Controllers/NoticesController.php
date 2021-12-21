<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class NoticesController extends Controller
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
                $notices = Notice::where("status", "=", 2)->orderByRaw('id DESC');
            }
        }else{
            // get != deteled data
            $notices = Notice::where("status", "!=", 2)->orderByRaw('id DESC');
        }

        $notices = $notices->paginate(10);

        return view('notice/index')->with(['notices' => $notices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $school_list = SchoolInfo::all();
        return view('notice/create')->with(['schools' => $school_list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $school_id=$request->notice_for;

        if($request->post("hidden_notice_id")){
            $object=Notice::find($request->post("hidden_notice_id"));
        }
        else
        {
            $object = new Notice;
        }

        if($school_id=='All')
        {
            $object->for_all=1;
            $object->school_id='';
        }
        else
        {
            $object->for_all=0;
            $object->school_id=$school_id;
        }
        $object->notice_title=$request->notice_title;
        $object->notice_body=$request->notice_body;
        $object->notice_attachment='';


        try {
                $object->save();
                $return='true';
                session()->flash("success", "Notice [" . $request->notice_title . "] saved successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Menu [" . $request->notice_title . "] is not save for duplicate entry.");
                }
                $return='';
            }
        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        $id=$notice->id;
        $data=Notice::find($id);
        $school_list = SchoolInfo::all();
        return view('notice/edit')->with(['schools' => $school_list,'details' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Notice::find($id);
        $data->status=2;
        $data->save();
        session()->flash("success", "Notice deleted successfully");
        return back();
    }

    public function details($id)
    {
        $data=Notice::find($id);
        $school_id=$data->school_id;
        $school_list=array();
        if(!empty($school_id))
        {
            $school_list = $users = DB::table('school_infos')->whereIn('id', array($school_id))->get();
        }

        return view('notice/details')->with(['schools' => $school_list,'details' => $data]);


    }


}
