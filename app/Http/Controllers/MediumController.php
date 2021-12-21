<?php

namespace App\Http\Controllers;

use App\Models\Medium;
use Illuminate\Http\Request;
use Auth;


class MediumController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {

        if($request->get("gen")){
            // get delete data
            if($request->get("gen") == "trash"){
                $object = Medium::where("status", "=", 2);
            }
        }else{
            // get != deteled data
            $object = Medium::where("status", "!=", 2);      
        } 

        $object = $object->paginate(10);

        return view('backend/medium_info')->with(['media' => $object]);
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
         if($request->post("hidden_medium_id")){
            $object = Medium::find($request->post("hidden_medium_id"));
            
            $object->name = $request->medium_name;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->updated_by = Auth::user()->id;


            try {
                $object->save();
                session()->flash("success", "Medium [" . $request->medium_name . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Medium [" . $request->medium_name . "] is already exist.");
                }
            }
        }else{
            $object = new Medium;
            $object->name = $request->medium_name;
            $object->created_by = Auth::user()->id;
            $object->created_at = date('Y-m-d H:i:s');


            try {
                $object->save();
                session()->flash("success", "Medium [" . $request->medium_name . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Medium [" . $request->medium_name . "] is already exist.");
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
        $object = Medium::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();
        
        session()->flash("success", "Medium is move to trash successfully!");
        
        return back();
    }


    public function loading_medium_info_item_ajax_hit($id){
        $object = Medium::find($id);
        return $object;
    }
}
