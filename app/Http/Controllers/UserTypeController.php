<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    

     public function index(Request $request)
    {
        if($request->get("gen")){
            if($request->get("gen") == "trash"){
                $user_types = UserType::where("status", "=", 2);
            }
        }
        else{
            $user_types = UserType::where("status", "!=", 2);      
        } 

        $user_types = $user_types->paginate(11);

       return view('backend/user_type')->with(['user_types' => $user_types]);
    }

    
    public function store(Request $request)
    {
        if($request->post("hidden_menu_id")){

            $data = UserType::find($request->post("hidden_menu_id"));
            $data->name = $request->name;
            $data->status = $request->status;
            try {
                $data->save();
                session()->flash("success", "User type is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "User type is not updated for duplicate entry.");
                }
            }
        }

        else{

            $data = new UserType;
            $data->name = $request->name;
            $data->status = $request->status;
            try {
                $data->save();
                session()->flash("success", "User type is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "User type is not added for duplicate entry.");
                }
            }
        }

        return back();
    }

    public function destroy($id)
    {
        $data = UserType::find($id);
        $data->status = 2;
        $data->save();
        session()->flash("success", "User Type is move to trash");
        return back();
    }


    public function edit_user_type($id){
        $data = UserType::find($id);
        return $data;
    }




}
