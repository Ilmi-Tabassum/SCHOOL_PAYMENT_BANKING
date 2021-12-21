<?php

namespace App\Http\Controllers;

use App\Models\AllNotification;
use App\Models\Notice;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;

class AllNotificationController extends Controller
{
   
    public function index(Request $request)
    {
        $user_type_id = Auth::user()->user_type_id;
        if($user_type_id==1){
            if($request->get("gen")){
                if($request->get("gen") == "trash"){
                    $notices = AllNotification::where("status", "=", 2)->orderByRaw('id DESC');
                }
            }
            else{
                $notices = AllNotification::whereIn('status', [0,1,2,3])->orderByRaw('id DESC');

            }

            $notices = $notices->paginate(30);
            return view('backend/notification/all_notification')->with(['notices' => $notices]);
        }

        if ($user_type_id==2) {
            $current_school_id = auth::user()->school_id;
            $notices = DB::table('all_notifications')
                     ->join('school_infos','all_notifications.school_id','=','school_infos.id')
                     ->join('class_infos','all_notifications.class_id','=','class_infos.id')
                     ->select('all_notifications.*', 'school_infos.school_name', 'class_infos.name')
                     ->where('school_id',$current_school_id)
                     ->paginate(30);
            return view('backend/notification/all_notification')->with(['notices' => $notices]);
        }
    }



    public function create(Request $request)
    {
        $school_list = SchoolInfo::all();
        return view('backend/notification/create')->with(['schools' => $school_list]);
    }

   
    public function store(Request $request)
    {
        $request->notification_for;
        $school_id=$request->notification_for;

        if($request->post("hidden_notification_id")){
            $object=AllNotification::find($request->post("hidden_notification_id"));
        }
        else
        {
            $object = new AllNotification;
        }

        if($school_id=='All')
        {
            $object->for_all=1;
            $object->school_id='';
        }
        else
        {
            $object->for_all=0;
            $object->school_id=$school_id;
        }
        $object->notification_title=$request->notification_title;
        $object->notification_body=$request->notification_body;
        $object->notification_attachment='';
        $object->status=1;


        try {
                $object->save();
                $return='true';
                session()->flash("success", "Notice [" . $request->notification_title . "] saved successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry.  [" . $request->notification_title . "] is not save for duplicate entry.");
                }
                $return='';
            }
        return $return;
    }

   
    public function edit(AllNotification $allNotification)
    {
        $id=$allNotification->id;
        $data=AllNotification::find($id);
        $school_list = SchoolInfo::all();
        return view('backend/notification/edit')->with(['schools' => $school_list,'details' => $data]);
    }

   
   
    public function destroy($id)
    {
        $data = AllNotification::find($id);

        $data->status=2;
        $data->save();
        session()->flash("success", "Notice deleted successfully");

        return back();
    }
    public function restore($id)
    {
        $data = AllNotification::find($id);

        $data->status=3;
        $data->save();
        session()->flash("success", "Notification removed from trash successfully");

        return back();
    }
    public function activate($id)
    {
        $data = AllNotification::find($id);

        $data->status=1;
        $data->save();
        session()->flash("success", "Notification Activated successfully");

        return back();
    }
    public function inactivate($id)
    {
        $data = AllNotification::find($id);

        $data->status=3;
        $data->save();
        session()->flash("success", "Notification inactivated successfully");

        return back();
    }

    public function details($id)
    {
        $data=AllNotification::find($id);
        $school_id=$data->school_id;
        $school_list=array();
        if(!empty($school_id))
        {
            $school_list = $users = DB::table('school_infos')->whereIn('id', array($school_id))->get();
        }

        return view('backend/notification/details')->with(['schools' => $school_list,'details' => $data]);


    }


    public function showGuardianNotice()
    {

        $current_user_id = Auth::user()->id;
        $sibling_students = DB::select(DB::raw("SELECT * FROM siblings WHERE user_id=$current_user_id"));
        $count_student = count($sibling_students);

         if ($count_student>0) {
            $student_class = array();
            for ($i=0; $i <$count_student ; $i++) { 
                $id = (int)($sibling_students[$i]->class_id);
                array_push($student_class,$id);
            }

            $all_notice = DB::table('all_notifications')
                         ->join('school_infos','all_notifications.school_id','=','school_infos.id')
                         ->join('class_infos','all_notifications.class_id','=','class_infos.id')
                         ->select('all_notifications.*', 'school_infos.school_name', 'class_infos.name')
                         ->whereIn('class_id', $student_class)
                         ->paginate(10);
            $hasData = 1;
            return view('backend/notification/guardian_notification')->with(['all_notice' => $all_notice,'hasData'=>$hasData]);
        }
        else{
            $hasData = 0;
            return view('backend/notification/guardian_notification')->with(['hasData'=>$hasData]);
        }
    }

    public function showSchoolNotification()
    {
        $current_school_id = auth::user()->school_id;
        $all_notice = DB::table('all_notifications')
                     ->join('school_infos','all_notifications.school_id','=','school_infos.id')
                     ->join('class_infos','all_notifications.class_id','=','class_infos.id')
                     ->select('all_notifications.*', 'school_infos.school_name', 'class_infos.name')
                     ->where('school_id',$current_school_id)
                     ->paginate(10);

        return view('backend/notification/school_notification')->with(['all_notice' => $all_notice]); 
    }

}
