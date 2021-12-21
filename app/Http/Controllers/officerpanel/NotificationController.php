<?php

namespace App\Http\Controllers\officerpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllNotification;
use App\Models\SchoolInfo;
use App\Models\ClassInfo;
use Carbon\Carbon;
use Auth;
use DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
       $user_type_id = Auth::user()->user_type_id;
       if($user_type_id==1){
       $notificationIndex = DB::table('all_notifications')
           // ->join('school_infos', 'all_notifications.school_id', '=', 'school_infos.id')
           //->select('all_notifications.*', 'school_infos.school_name')
            ->select('all_notifications.*')
            ->orderByRaw('all_notifications.id DESC')
            ->paginate(11);

        $schools = SchoolInfo::all();
        $classes = ClassInfo::all();
        return view('officerpanel/notification')->with(['notificationIndex' => $notificationIndex,'schools'=>$schools,'classes'=>$classes]);
       }

        if($user_type_id==2){
             $current_school_id = auth::user()->school_id;
            $notificationIndex = DB::table('all_notifications')
            //->join('school_infos', 'all_notifications.school_id', '=', 'school_infos.id')
            //->select('all_notifications.*', 'school_infos.school_name')
            ->select('all_notifications.*')
            ->orderByRaw('all_notifications.id DESC')
            ->where('school_id',$current_school_id)
            ->paginate(11);

            $schools = SchoolInfo::all();
            $classes = ClassInfo::all();
            return view('officerpanel/notification')->with(['notificationIndex' => $notificationIndex,'schools'=>$schools,'classes'=>$classes]);
        }
    }


    public function showDetailsNotification($id)
    {
        $detailsData =DB::select(DB::raw("SELECT id,notification_title,notification_body  FROM all_notifications WHERE id=$id"));
        return $detailsData;
    }


    public function store(Request $request)
    {
        $user_type_id = Auth::user()->user_type_id;
        if($user_type_id==1) {
            $title = $request->notification_title;
            $body = $request->notification_body;
            $recipients = $request->notification_for;
            $class_id = $request->class_id;
            if ($recipients=='All') {
                $isForAll = 1;
                $school_id=0;
            }
            else{
                $isForAll = 0;
                $school_id=$request->notification_for;
            }


            $data = new AllNotification;
            $data->school_id = $school_id;
            $data->for_all = $isForAll;
            $data->notification_title = $title;
            $data->notification_body = $body;
            $data->class_id = $class_id;
            $data->save();

            session()->flash("success", "Notification created successfully.");
            return back();
        }
        elseif($user_type_id==2) {
            $current_school_id = auth::user()->school_id;
            $title = $request->notification_title;
            $body = $request->notification_body;
            //$recipients = $request->notification_for;
            $class_id = $request->class_id;
             $isForAll = 0;
             $school_id=$current_school_id;



            $data = new AllNotification;
            $data->school_id = $school_id;
            $data->for_all = $isForAll;
            $data->notification_title = $title;
            $data->notification_body = $body;
            $data->class_id = $class_id;
            $data->save();

            session()->flash("success", "Notification created successfully.");
            return back();
        }


    }

    public function destroy($id)
    {
        $data = AllNotification::find($id);
        DB::select(DB::raw("DELETE FROM all_notifications WHERE id=$id"));
        session()->flash("success", "Notification Deleted");
        return back();
    }

    public function getDetails($id)
    {
        $details = DB::table('all_notifications')
            ->join('school_infos', 'all_notifications.school_id', '=', 'school_infos.id')
            ->select('all_notifications.*', 'school_infos.school_name')
            ->where('all_notifications.id',$id)
            ->get();

        return $details;


    }



}
