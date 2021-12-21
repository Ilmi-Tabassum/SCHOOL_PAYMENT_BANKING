<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Models\FeesHead;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignParticular;
use App\Models\schoolpanel\AssignSection;
use App\Models\schoolpanel\AssignShift;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;
use DB;
use Auth;


class AssignParticularController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      /*  if(Auth::user()->school_id)
        {
            $scid=Auth::user()->school_id;
            $particular =DB::select(DB::raw("
            SELECT fees_heads.id id,fees_heads.fees_head_name name
            FROM fees_heads
            ORDER BY fees_heads.fees_head_name ASC"));
        }else{
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
        }*/


        $particulars =FeesHead::all();

        return view('schoolpanel/assign_particulars/create')->with(['particulars' => $particulars]);
    }
    public function section_load($id)
    {
        $scid=Auth::user()->school_id;

        $sections =DB::select(DB::raw("
            SELECT sections.id id,sections.name name
            FROM assign_sections
            JOIN sections on assign_sections.section_id=sections.id
            WHERE assign_sections.status != 2 AND assign_sections.school_id=1 AND assign_sections.class_id=$id
            ORDER BY sections.name ASC"));
        return $sections;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $particulars=$request->particulars_name;
        $school_id = Auth::user()->school_id;
        $user=Auth::user()->id;

        if($school_id && $particulars)
        {
            foreach($particulars as $value)
            {

                $check=DB::select(DB::raw("
            SELECT id
            FROM assign_particulars
            WHERE fees_head_id = $value And school_id=$school_id  "));



                if(!empty($check))
                {
                    session()->flash("error", "We are sorry.Particulars is not assigned for duplicate entry.");
                    $return='false';

                }
                else{

                    $object = new AssignParticular;
                    $object->school_id=$school_id;
                    $object->fees_head_id=$value;
                    $object->status=1;
                    $object->created_by=$user;



                    try {
                        $object->save();
                        $return='true';
                        session()->flash("success", "Particulars assigned successfully!");
                    } catch(\Illuminate\Database\QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        if ($errorCode == '1062') {
                            session()->flash("error", "We are sorry.Particulars is not assigned for duplicate entry.");
                        }
                        $return="false";
                    }


                }


            }
        }else{
            session()->flash("error", "Please try again");
            $return="false";
        }




        return $return;



    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AssignSection $assignSection
     * @return \Illuminate\Http\Response
     */
    public function show(AssignSection $assignSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AssignSection $assignSection
     * @return \Illuminate\Http\Response
     */
    public function edit(AssignSection $assignSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AssignSection $assignSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssignSection $assignSection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AssignSection $assignSection
     * @return \Illuminate\Http\Response
     */

}
