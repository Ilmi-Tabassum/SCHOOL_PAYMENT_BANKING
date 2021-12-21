<?php

namespace App\Http\Controllers;

use App\Models\MenuSetup;
use Illuminate\Http\Request;
use DB;
use Auth;

class MenuSetupController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {
        //DB::enableQueryLog();
     
        if($request->get("gen")){
            // get delete data
            if($request->get("gen") == "trash"){
                $menus = MenuSetup::where("status", "=", 2);
            }
        }else{
            // get != deteled data
            $menus = MenuSetup::where("status", "!=", 2);      
        } 


        $parent_menu = MenuSetup::where("status", "!=", 2)
                                          ->where("sub_id", "=", null)
                                          ->get();  

        $menus = $menus->paginate(60);

        return view('backend/menusetup')->with(['menus' => $menus, 'parent_menu' => $parent_menu]);
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
         if($request->post("hidden_menu_id")){
            $object = MenuSetup::find($request->post("hidden_menu_id"));
            
            $object->sub_id = $request->parent_menu;
            $object->menu_name = $request->menu_name;
            $object->menu_title = $request->menu_title;
            $object->menu_url = $request->menu_url;
            $object->menu_icon = $request->menu_icon;
            $object->updated_by = Auth::user()->id;
        

            try {
                $object->save();
                session()->flash("success", "Menu [" . $request->menu_title. "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Menu [" . $request->menu_title. "] is not updated for duplicate entry.");
                }
            }
        }else{
            $object = new MenuSetup;
            $object->sub_id = $request->parent_menu;
            $object->menu_name = $request->menu_name;
            $object->menu_title = $request->menu_title;
            $object->menu_url = $request->menu_url;
            $object->menu_icon = $request->menu_icon;
            //$object->status = $request->status;
            $object->created_by = Auth::user()->id;
            $object->updated_by = $request->updated_by;
            $object->deleted_by = $request->deleted_by;
        

            try {
                $object->save();
                session()->flash("success", "New Menu [" . $request->menu_title. "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Menu [" . $request->menu_title. "] is not added for duplicate entry.");
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
        $menu = MenuSetup::find($id);
        $menu->status = 2;
        $menu->deleted_by = Auth::user()->id;
        $menu->save();
        
        session()->flash("success", "Menu is move to trash successfully!");
        
        return back();
    }


    public function loading_menu_item_ajax_hit($id){
        $object = MenuSetup::find($id);
        return $object;
    }

    
}
