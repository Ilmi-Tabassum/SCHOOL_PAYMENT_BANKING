<?php

namespace App\Http\Controllers;

use App\Models\schoolpanel\AssignSection;
use App\Models\schoolpanel\AssignSession;
use App\Models\Section;
use Illuminate\Http\Request;
use Auth;
use DB;


class SectionController extends Controller
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
            SELECT sections.name secname,class_infos.name classname,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            INNER JOIN class_infos ON class_infos.id = assign_sections.class_id

            WHERE assign_sections.status = 2 AND assign_sections.school_id=$scid"));
                }

            } else {
                // get != deteled data

                $object = DB::select(DB::raw("
            SELECT sections.name secname,class_infos.name classname,assign_sections.status status ,assign_sections.section_id,assign_sections.id id
            FROM assign_sections
            INNER JOIN sections ON assign_sections.section_id = sections.id
            INNER JOIN class_infos ON class_infos.id = assign_sections.class_id

            WHERE assign_sections.status != 2 AND assign_sections.school_id=$scid"));

            }


            /*$object = $object->paginate(10);*/
            return view('backend/section_info')->with(['sections' => $object]);
        }
        else {
            if($request->get("gen")){
                // get delete data
                if($request->get("gen") == "trash"){
                    $object = Section::where("status", "=", 2);
                }
            }else{
                // get != deteled data
                $object = Section::where("status", "!=", 2);
            }

            $object = $object->paginate(10);

            return view('backend/section_info')->with(['sections' => $object]);
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
         if($request->post("hidden_section_id")){
            $object = Section::find($request->post("hidden_section_id"));

            $object->name = $request->section_name;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->updated_by = Auth::user()->id;


            try {
                $object->save();
                session()->flash("success", "Section [" . $request->section_name . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Section [" . $request->section_name . "] is already exist.");
                }
            }
        }else{
            $object = new Section;
            $object->name = $request->section_name;
            $object->created_by = Auth::user()->id;
            $object->created_at = date('Y-m-d H:i:s');


            try {
                $object->save();
                session()->flash("success", "Section [" . $request->section_name . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Section [" . $request->section_name . "] is already exist.");
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
        $object = Section::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Section is move to trash successfully!");

        return back();
    }*/

    public function destroy($id)
    {
        $object = AssignSection::find($id);
        $object->status =2;
        $object->save();

        session()->flash("success", "Session is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = AssignSection::find($id);
        $object->status = 1;
        $object->save();

        session()->flash("success", "Session is removed from successfully!");

        return back();
    }


    public function loading_section_info_item_ajax_hit($id){
        $object = Section::find($id);
        return $object;
    }
}
