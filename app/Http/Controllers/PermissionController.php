<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\MenuSetup;
use Auth;
use DB;
use Carbon\Carbon;

class PermissionController extends Controller
{

    public function index(){
      $master_menu = DB::table('menu_setups')
                        ->select('id','sub_id','menu_name','menu_title')
                        ->where('sub_id', '=', null)
                        ->get();

      $sub_menu = DB::table('menu_setups')
                        ->select('id','sub_id','menu_name','menu_title')
                        ->get();

      $user_types = DB::select(DB::raw("SELECT id,name FROM user_types WHERE status != 2"));
      return view('backend/permission')->with(['master_menu' => $master_menu, 'sub_menu' =>$sub_menu,'user_types'=>$user_types]);
    }



     public function assign_permission(Request $request){
        ini_set('memory_limit','-1');
        

        //print_r($request->all());exit;
        //echo $request->user_type;exit;
        foreach($request->parent_menu as $id) {
        $user_type=$request['user_type'];

        $menues=$request['parent_menu'];
        $menues_arr=array();

        foreach ($menues as $key => $value) {
          $menu_id=$value;
          $sub_menu=$request['sub_menu_'.$menu_id];
          $sub_menus=array();
          foreach ($sub_menu as $key1 => $value1) {
            $sub_menu_id=$value1;
            $sub_menus[]=array(
              'sub_menu'=>$sub_menu_id,
              'read'=>$request['in_read_'.$sub_menu_id],
              'edit'=>$request['in_edit_'.$sub_menu_id],
              'delete'=>$request['in_del_'.$sub_menu_id],
              'restore'=>$request['in_rest_'.$sub_menu_id],
            );
          } //end sub_menu foreach

          $menues_arr[]=array(
            'main_menu'=>$menu_id,
            'sub_menu'=>$sub_menus,
            
          );     
          


        } //end menues foreach

        $menu_text=serialize($menues_arr);
        
        $categories = new Permission;
        $object=$categories->select('id' )->where('utype_id',$user_type)->get()->toArray();
        $error="";
        $success="";
        if(count($object)>0)
        {
          $id=$object[0]['id'];
          $object = Permission::find($id);
          $object->updated_by = Auth::user()->id;
          $object->updated_at =Carbon::now();
          $error="update";
          $success="updated";
        }
        else
        {
          $object = new Permission;
          $object->created_by = Auth::user()->id;
          $error="create";
          $success="created";
        }

        $object->utype_id = $request->user_type;
        $object->id_menu =$menu_text;

        try{
          $object->save();
          session()->flash("success", "New Permission is ".$success." successfully!");
        }catch(\Illuminate\Database\QueryException $e){
          $errorCode = $e->errorInfo[1];
          if($errorCode == '1062'){
              session()->flash("error", "We are sorry. Permission is not ".$error." for duplicate entry.");
          }
        }
        return back();
    } //end sub_menu foreach

 }


 public function check_permissions($user_type_id)
 {       
         $find_u_id = DB::select(DB::raw("SELECT id,utype_id FROM permissions WHERE utype_id=$user_type_id"));
         //dd($find_u_id);
         
         if (count($find_u_id)>0) {
            $assigned_menus = DB::select(DB::raw("SELECT utype_id,id_menu FROM `permissions` WHERE utype_id= $user_type_id"));
             $query_output = unserialize($assigned_menus[0]->id_menu);
             
             $size = count($query_output);
             $main_menu = array();

            for ($i=0; $i <$size ; $i++) { 
               $main_menu_id = (int) $query_output[$i]['main_menu'];
               array_push($main_menu,$main_menu_id);
            }

            $parent_menus_info = DB::table("menu_setups")->select('id')->whereIn('id', $main_menu)->get();

             // $parent_menu = MenuSetup::select('id')
             //                              ->where("status", "!=", 2)
             //                              ->where("sub_id", "=", null)
             //                              ->get();

              
              //for submenu
              $store_submenu_id = array();
             for ($p=0; $p <$size ; $p++) { 
               $submenu_size = count($query_output[$p]['sub_menu']);
               //echo "Submenu size:".$submenu_size."<br>";
                
               
               for ($s=0; $s <$submenu_size ; $s++) { 

                  $sub_menu_id = (int)$query_output[$p]['sub_menu'][$s]['sub_menu'];
                  //echo $sub_menu_id ." ";
                  
                  $left_child_menu_items = DB::table("menu_setups")
                                            ->select('id')
                                            ->where('sub_id', '=',$p+1)
                                            ->where('id','=',$sub_menu_id)
                                            ->get();
                  
                // var_dump($left_child_menu_items[0]->id);
                  //echo $left_child_menu_items[0]->id." ";
                  array_push($store_submenu_id, $left_child_menu_items[0]->id);
               } 
              

                
              } 
               //return $store_submenu_id;

            return response()->json(['data' =>$parent_menus_info, 'store_submenu_id' => $store_submenu_id]);
           // return $parent_menus_info;
         }

 }






}