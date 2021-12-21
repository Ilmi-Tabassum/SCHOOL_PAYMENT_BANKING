<?php

namespace App\Http\Controllers\officerpanel;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuSetup;
use Auth;
use DB;
use Carbon\Carbon;

class OfficerPanelPermissionController extends Controller
{
    
    public function index(){
      $master_menu = DB::table('menu_setups')
                        ->select('id','sub_id','menu_name','menu_title')
                        ->where('sub_id', '=', null)
                        ->get();

      $sub_menu = DB::table('menu_setups')
                        ->select('id','sub_id','menu_name','menu_title')
                        ->get();

      $user_types = DB::select(DB::raw("SELECT id,name FROM user_types WHERE id !=1"));

      return view('officerpanel/setting/officerpanel_permission')->with(['master_menu' => $master_menu, 'sub_menu' =>$sub_menu,'user_types'=>$user_types]);
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
         if (count($find_u_id)>0) {
            $assigned_menus = DB::select(DB::raw("SELECT utype_id,id_menu FROM permissions WHERE utype_id= $user_type_id"));
            //dd($assigned_menus);

            $query_output = unserialize($assigned_menus[0]->id_menu);
            //dd($query_output);
          
            $size = count($query_output);
           // echo $size;exit;

            //store main menu in main_menu array
            $main_menu = array();
            for ($i=0; $i <$size ; $i++) { 
               $main_menu_id = (int) $query_output[$i]['main_menu'];
               array_push($main_menu,$main_menu_id);
            }

            $parent_menus_info = DB::table("menu_setups")->select('id')->whereIn('id', $main_menu)->get();
            //dd($parent_menus_info);

             
              
              //for submenu and crud
              $store_submenu_id = array();
              $read_ids = array();
              $edit_ids = array();
              $delete_ids = array();
              $restore_ids = array();

             for ($p=0; $p <$size ; $p++) { 
               $submenu_size = count($query_output[$p]['sub_menu']);
               //echo "Submenu size:".$submenu_size."<br>";
               for ($s=0; $s <$submenu_size ; $s++) { 

                   $sub_menu_id = (int)$query_output[$p]['sub_menu'][$s]['sub_menu'];


                   $read = $query_output[$p]['sub_menu'][$s]['read'];
                   $edit = $query_output[$p]['sub_menu'][$s]['edit'];
                   $delete = $query_output[$p]['sub_menu'][$s]['delete'];
                   $restore = $query_output[$p]['sub_menu'][$s]['restore'];
                   
                   /*Read */
                   if(is_null($read)){
                     array_push($read_ids, 0);
                   }
                   else{
                     array_push($read_ids, $sub_menu_id);
                   }

                   /*Edit */
                  if(is_null($edit)){
                     array_push($edit_ids, 0);
                   }
                   else{
                     array_push($edit_ids, $sub_menu_id);
                   }

                   /*Delete*/
                   if(is_null($delete)){
                     array_push($delete_ids, 0);
                   }
                   else{
                     array_push($delete_ids, $sub_menu_id);
                   }


                  /*Restore*/
                  if(is_null($restore)){
                     array_push($restore_ids, 0);
                   }
                   else{
                     array_push($restore_ids, $sub_menu_id);
                   }



                
                  /*Sub Menu IDs*/
                  if(isset($sub_menu_id))
                  {
                    array_push($store_submenu_id, $sub_menu_id);
                  }

               } 
              

                
              }

              //dd($store_submenu_id);
              //dd($parent_menus_info);
            return response()->json(['hasValue'=>"1",'data' =>$parent_menus_info, 'store_submenu_id' => $store_submenu_id,'read_ids'=>$read_ids,'edit_ids'=>$edit_ids,'delete_ids'=>$delete_ids,'restore_ids'=>$restore_ids]);
         }
         return response()->json(['hasValue' =>"0"]);



 }
}
