<?php

namespace App\Http\Controllers;

use App\Models\ClassInfo;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Auth;
use DB;

class ClassInfoController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {

        /*if($request->get("gen")){
            // get delete data
            if($request->get("gen") == "trash"){
                $object = ClassInfo::where("status", "=", 2);
            }
        }else{
            // get != deteled data
            $object = ClassInfo::where("status", "!=", 2);
        }

        $object = $object->paginate(10);*/


        if($request->get("gen")){
            if($request->get("gen") == "trash"){
               if(isset(Auth::user()->school_id)){
                    $school_id = Auth::user()->school_id;
                    $sql = "select a.id, a.name as name, c.school_name, c.school_ein, a.status
                        from class_infos as a 
                        inner join assign_classes as b 
                        inner join school_infos as c 
                        on 
                        a.id = b.class_id and 
                        b.school_id = c.id and 
                        a.status = 2 and 
                        c.id = $school_id";
                }else{
                     $sql = "select a.id, a.name as name, c.school_name, c.school_ein, a.status
                        from class_infos as a 
                        inner join assign_classes as b 
                        inner join school_infos as c 
                        on 
                        a.id = b.class_id and 
                        b.school_id = c.id and 
                        a.status = 2";   
                } 
            }
        }else{
            if(isset(Auth::user()->school_id)){
                $school_id = Auth::user()->school_id;
                $sql = "select a.id, a.name as name, c.school_name, c.school_ein, a.status
                    from class_infos as a 
                    inner join assign_classes as b 
                    inner join school_infos as c 
                    on 
                    a.id = b.class_id and 
                    b.school_id = c.id and 
                    a.status != 2 and 
                    c.id = $school_id";
            }else{
                 $sql = "select a.id, a.name as name, c.school_name, c.school_ein, a.status
                    from class_infos as a 
                    inner join assign_classes as b 
                    inner join school_infos as c 
                    on 
                    a.id = b.class_id and 
                    b.school_id = c.id and 
                    a.status != 2";   
            }
        }


        
        
    
        
        $classes = DB::select( DB::raw($sql));    


        


        $class_infos = ClassInfo::where("status", "!=", 2)->get();
        $schools = SchoolInfo::where("status", "!=", 3)->get();

        return view('backend/class_info')->with(['classes' => $classes, 'class_infos' => $class_infos, 'schools' => $schools]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/class/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
         if($request->post("hidden_class_id")){
            $object = ClassInfo::find($request->post("hidden_class_id"));

            $object->name = $request->class_name;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->updated_by = Auth::user()->id;


            try {
                $object->save();
                session()->flash("success", "Class [" . $request->class_name . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Class [" . $request->class_name . "] is already exist.");
                }
            }
        }else{
            $object = new ClassInfo;
            $object->name = $request->class_name;
            $object->created_by = Auth::user()->id;
            $object->created_at = date('Y-m-d H:i:s');


            try {
                $object->save();
                session()->flash("success", "New Class [" . $request->class_name . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Class [" . $request->class_name . "] is already exist.");
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
    public function destroy($id)
    {
        $object = ClassInfo::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Class is move to trash successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = ClassInfo::find($id);
        $object->status = 1;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Class is move to trash successfully!");

        return back();
    }


    public function loading_class_info_item_ajax_hit($id){
        $object = ClassInfo::find($id);
        return $object;
    }
}
