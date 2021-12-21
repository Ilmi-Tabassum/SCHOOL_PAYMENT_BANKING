<?php

namespace App\Http\Controllers\officerpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\officerpanel\CreateUser;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Intervention\Image\Facades\Image;

class CreateUserController extends Controller
{

    public function index()
    {
        $userType = DB::table('user_types')
                    ->select('id','name')
                    ->where('id','!=',1)
                    ->get();

        $schools = DB::table('school_infos')
                    ->select('id','school_name')
                    ->get();

        $users_info = DB::table('users')
                    ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                    ->select('users.id','users.name','users.email','users.mobile_number','users.user_type_id','user_types.name as user_type_name')
                    ->where('user_type_id','!=',1)
                    ->paginate(11);

        return view('officerpanel/setting/user_create')->with(['userType'=>$userType,'schools'=>$schools,'users_info'=>$users_info]);
    }

    public function changePassword(Request $request)
    {
        $current_password = (trim($request->current_pass));
          $New_password = $request->new_pass;
         $confirm_password = $request->confirm_pass;


        $user_info = Auth::user();
         $user_id = $user_info->id;


        $current_password_db=DB::select(DB::raw("SELECT password
                            FROM  users
                            WHERE id=$user_id"));

        if(Hash::check($request->current_pass, $current_password_db[0]->password))
        {
            if($New_password==$confirm_password)
            {

                $data = CreateUser::find($user_id);
                $data->password=Hash::make($New_password);
                if($data->save())
                {
                    session()->flash("success", "Password Updated successfully.");

                }else{
                    session()->flash("error", "Please try again");

                }
            }
            else{
                session()->flash("error", "Doesn't match ");

            }
        }else{
            session()->flash("error", "Incorrect password");

        }
        return back();

    }


    public function store(Request $request)
    {
         /*Get the value from form*/
        $name = $request->name;
        $email = $request->email;
        $mobile_number = $request->mobile_number;
        $user_type_id = $request->user_type_id;
        $school_id = $request->school_id;
        $password = $request->password;
        $created_by = Auth::user()->id;



        if($request->post("hidden_id")){
            $data = CreateUser::find($request->post("hidden_id"));
            $data->name = $name;
            $data->email = $email;
            $data->mobile_number = $mobile_number;
            $data->user_type_id = $user_type_id;
            $data->school_id = $school_id;
            $data->password = Hash::make($password);

              try {
                $data->save();
                session()->flash("success", "User Updated successfully.");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Duplicate entry of Mobile Number " . $mobile_number. " ");
                }
            }

            return back();
        }

        else{
             /*Save the value*/
            $data = new CreateUser;
            $data->name = $name;
            $data->email = $email;
            $data->mobile_number = $mobile_number;
            $data->user_type_id = $user_type_id;
            $data->school_id = $school_id;
            $data->created_by = $created_by;
            $data->password = Hash::make($password);



             try {
                $data->save();
                session()->flash("success", "User Created successfully.");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "Duplicate entry of Mobile Number " . $mobile_number. " ");
                }
            }

            return back();
        }


    }

    public function edit_user($id){
        $data = CreateUser::find($id);
        return $data;
    }

     public function destroy($id)
    {
        $data = CreateUser::find($id);
        DB::select(DB::raw("DELETE FROM users WHERE id=$id"));
        session()->flash("success", "User Deleted");
        return back();
    }
}
