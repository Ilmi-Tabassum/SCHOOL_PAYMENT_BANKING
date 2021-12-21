<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Http\Controllers\Controller;
use App\Models\schoolpanel\AssignSection;
use App\Models\schoolpanel\AssignShift;
use App\Models\Section;
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
        if ($request->get("gen")) {
            // get delete data
            if ($request->get("gen") == "trash") {
                $section = DB::select(DB::raw("
            SELECT sections.name,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            WHERE assign_sections.status=2 AND assign_sections.school_id=1"));
            }

        } else {
            // get != deteled data

            $section = DB::select(DB::raw("
            SELECT sections.name,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id

            WHERE assign_sections.status != 2 AND assign_sections.school_id=1"));

        }

        /*$object = $object->paginate(10);*/
        return view('school_accounts_panel/assign_section/assign_section')->with(['mysections' => $section]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('school_accounts_panel/assign_section/create')->with(['sections' => $sections]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $sections=$request->section_name;
        foreach($sections as $value)
        {

            $check=DB::select(DB::raw("
            SELECT id
            FROM assign_sections
            WHERE section_id = $value And school_id=1"));



            if(!empty($check))
            {
                session()->flash("error", "We are sorry.Sections is not assigned for duplicate entry.");
                $return='';



            }
            else{

                $object = new AssignSection;
                $object->school_id=1;
                $object->class_id=1;
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
                }


            }


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
