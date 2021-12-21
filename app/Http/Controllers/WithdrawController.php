<?php

namespace App\Http\Controllers;

use App\Models\SchoolDistrict;
use App\Models\SchoolDivision;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSession;
use App\Models\Session;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Auth;
use DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Input\Input;


class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        if(Auth::user()->school_id) {
            $sid = Auth::user()->school_id;
            $data = DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.status', '=', 0)
                ->where('withdraws.school_id', '=', $sid )
                ->paginate(50);
        }else{
            $data = DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.status', '=', 0)
                ->orderby('school_infos.school_name')
                ->paginate(50);
        }
        return view('backend/withdraw_list')->with(['data'=>$data,'school_info'=>$school_info]);
    }

    public function search_withdraw(Request $request)
    {


        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        $sdate = $request->start_date;
        $edate = $request->end_date;
        $sid = $request->schoolid;

        if(!empty($sid) && !empty($sdate) && !empty($edate))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.school_id','=',$sid)
                ->whereBetween('withdraws.req_date', [$sdate, $edate])
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($sid))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.school_id','=',$sid)
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($sdate) && !empty($edate))
        {
            if(Auth::user()->school_id) {
                $scid = Auth::user()->school_id;
                $data = DB::table('withdraws')
                    ->select(DB::raw('withdraws.*,school_infos.school_name'))
                    ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                    ->where('withdraws.school_id', '=', $scid )
                    ->whereBetween('withdraws.req_date', [$sdate, $edate])
                    ->orderby('school_infos.school_name')
                    ->paginate(50);
            }else{
                $data = DB::table('withdraws')
                    ->select(DB::raw('withdraws.*,school_infos.school_name'))
                    ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                    ->whereBetween('withdraws.req_date', [$sdate, $edate])
                    ->orderby('school_infos.school_name')
                    ->paginate(50);
            }
        }
        else
        {
            $data="";
            session()->flash("error", "Please try again");

        }


        return view('backend/withdraw_list')->with(['data'=>$data,'school_info'=>$school_info]);
    }

    public function pay($id)
    {
        $data =DB::table('withdraws')
            ->join('school_infos','withdraws.school_id', '=', 'school_infos.id')
            ->select('withdraws.*','school_infos.school_name')
            ->where('withdraws.id', '=', $id )
            ->where('withdraws.status', '=', 0 )
            ->get();
       return $data;
    }

    public function settled($id)
    {
        $data =DB::table('withdraws')
            ->join('school_infos','withdraws.school_id', '=', 'school_infos.id')
            ->select('withdraws.*','school_infos.school_name')
            ->where('withdraws.id', '=', $id )
            ->where('withdraws.status', '=', 1 )
            ->get();
        return $data;
    }
    public function deny($id)
    {
        $withdraw = Withdraw::find($id);
        $withdraw->status = 2;
        $withdraw->save();
        return back();
    }
    private function upload($file)
    {
        if($file){
            $photo = $file;
            //dd($photo);
           $image = Image::make($photo);
            //$image = Image::make(Input::file('artist_pic')->getRealPath())->resize(120,75);
            //dd($image);
            $image->fit(80,80);
            $thumbnail_filename = time()."_". rand(100000, 999999).".".$photo->getClientOriginalExtension();
            $image->save('storage/docs/'. $thumbnail_filename);

            return $thumbnail_filename;
        }
    }

    public function settleWithdrawReq(Request $request)
    {
        $req_pay_for=$request->payment_for;
        $req_details=$request->w3review;
        $req_id=$request->withdrawId;
        $withdraw = Withdraw::find($req_id);

        $withdraw->sett_date = date('Y-m-d');
        $withdraw->details = $req_details;
        $withdraw->payment_for = $req_pay_for;
        $withdraw->status = 1;
        $withdraw->save();

            if($request->supp_doc!= null){

                $img_name = $this->upload($request->supp_doc);
                $update_up = DB::select(DB::raw("UPDATE withdraws SET doc='$img_name'"));
            }

        return back();
    }

}





