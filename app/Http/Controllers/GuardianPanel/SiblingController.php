<?php

namespace App\Http\Controllers\GuardianPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\gurdian_panel\Sibling;
use DB;
use Auth;

class SiblingController extends Controller
{
    
   public function store(Request $request)
   {
   	    $users = Auth::User()->id;
   	    $data = new Sibling;
   	    $data->user_id = $users;
   	    $data->student_id  = $request->student_id;
   	    $data->class_id  = $request->class_id;
   	    $data->created_by = $users;
   	    $data->save();
   	    session()->flash("success", "Student Added");
        return back();
   }

   public function checkOTP(Request $request)
   {
      // otp confirmation and checking logic will be here
      $otp = $request->otp_confirm;
      //after confirming otp ,fetch the corresponding student details.

      return back();
   }



}
