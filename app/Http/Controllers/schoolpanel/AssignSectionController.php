<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSection;
use App\Models\schoolpanel\AssignShift;
use App\Models\Section;
use App\Models\Session;
use Illuminate\Http\Request;
use DB;
use Auth;


class AssignSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->school_id)
        {
            $scid=Auth::user()->school_id;

            if ($request->get("gen")) {
                // get delete data
                if ($request->get("gen") == "trash") {
                    $section = DB::select(DB::raw("
            SELECT sections.name,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            WHERE assign_sections.status=2 AND assign_sections.school_id=$scid"));
                }

            } else {
                // get != deteled data

                $section = DB::select(DB::raw("
            SELECT sections.name,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id

            WHERE assign_sections.status != 2 AND assign_sections.school_id=$scid"));

            }


            /*$object = $object->paginate(10);*/
            return view('schoolpanel/assign_section/assign_section')->with(['mysections' => $section]);

        }
        else
        {
            if($request->get("gen")){
                // get delete data
                if($request->get("gen") == "trash"){
                    $object = DB::select( DB::raw("
            SELECT sections.name shname,class_infos.name classname,school_infos.school_name schoolname , assign_sections.status status ,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            INNER JOIN class_infos ON class_infos.id = assign_sections.class_id
            INNER JOIN school_infos ON school_infos.id = assign_sections.school_id
           WHERE assign_sections.status=2 "));
                }

            }else{
                // get != deteled data

                $object = DB::select( DB::raw("
            SELECT sections.name shname,class_infos.name classname,school_infos.school_name schoolname , assign_sections.status status ,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            INNER JOIN class_infos ON class_infos.id = assign_sections.class_id
            INNER JOIN school_infos ON school_infos.id = assign_sections.school_id
           WHERE assign_sections.status !=2 "));


            }
            return view('schoolpanel/assign_section/assign_section')->with(['mysections' => $object]);

        }


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
        }else{
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
        }


        $sections =Section::all();
        $schools=SchoolInfo::all();

        return view('schoolpanel/assign_section/create')->with(['sections' => $sections,'classes' => $classes,'schools' => $schools]);
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
        $sections=$request->section_name;
        $classid=$request->class_id;
        if(Auth::user()->school_id)
        {
            $school_id = Auth::user()->school_id;
        }else{
            $school_id = $request->school_id;
        }
        if($school_id && $sections && $classid)
        {
            foreach($sections as $value)
            {

                $check=DB::select(DB::raw("
            SELECT id
            FROM assign_sections
            WHERE section_id = $value And school_id=$school_id And class_id= $classid "));



                if(!empty($check))
                {
                    session()->flash("error", "We are sorry.Sections is not assigned for duplicate entry.");
                    $return='false';

                }
                else{

                    $object = new AssignSection;
                    $object->school_id=$school_id;
                    $object->class_id=$classid;
                    $object->section_id=$value;
                    $object->status=1;


                    try {
                        $object->save();
                        $return='true';
                        session()->flash("success", "Sections assigned successfully!");
                    } catch(\Illuminate\Database\QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        if ($errorCode == '1062') {
                            session()->flash("error", "We are sorry.Sections is not assigned for duplicate entry.");
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
    public function destroy($id)
    {
        $object = AssignSection::find($id);
        $object->status = 2;
        $object->save();

        session()->flash("success", "Section is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = AssignSection::find($id);
        $object->status = 1;
        $object->save();

        session()->flash("success", "Section is removed from successfully!");

        return back();
    }
}
