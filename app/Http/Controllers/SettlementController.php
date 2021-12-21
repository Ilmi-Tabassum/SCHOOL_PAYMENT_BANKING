<?php

namespace App\Http\Controllers;

use App\Models\SchoolDistrict;
use App\Models\SchoolDivision;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSession;
use App\Models\Session;
use Illuminate\Http\Request;
use Auth;
use DB;


class SettlementController extends Controller
{
    public function index(Request $request)
    {

        $divisions = SchoolDivision::orderBy("division_name","asc")->get();
        $districts = SchoolDistrict::orderBy("name","asc")->get();
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));
        if(Auth::user()->school_id)
        {
            $sid = Auth::user()->school_id;

            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.status', '=', 1 )
                ->where('withdraws.school_id', '=', $sid )
                ->orderby('school_infos.school_name')
                ->paginate(50);
        }else
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.status', '=', 1 )
                ->orderby('school_infos.school_name')
                ->paginate(50);
        }

        return view('backend/settlement')->with(['data'=>$data,'school_info'=>$school_info, 'divisions' => $divisions, 'districts' => $districts]);
    }

    public function search_settle(Request $request)
    {

        $divisions = SchoolDivision::orderBy("division_name","asc")->get();
        $districts = SchoolDistrict::orderBy("name","asc")->get();
        $school_info = DB::select( DB::raw("SELECT id,school_name FROM  school_infos"));

        $sdate = $request->start_date;
        $edate = $request->end_date;
        $div = $request->present_division_id;
        $dis = $request->present_district_id;
        $sid = $request->schoolid;

        if(!empty($sid) && !empty($sdate) && !empty($edate))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('withdraws.school_id','=',$sid)
                ->whereBetween('withdraws.sett_date', [$sdate, $edate])
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
        elseif (!empty($div) && !empty($dis) && !empty($sdate) && !empty($edate))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name,school_infos.school_div,school_infos.school_dist'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('school_infos.school_div','=',$div)
                ->where('school_infos.school_dist','=',$dis)
                ->whereBetween('withdraws.sett_date', [$sdate, $edate])
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($div) && !empty($sdate) && !empty($edate))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name,school_infos.school_div'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('school_infos.school_div','=',$div)
                ->whereBetween('withdraws.sett_date', [$sdate, $edate])
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($div) && !empty($dis))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name,school_infos.school_div,school_infos.school_dist'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('school_infos.school_div','=',$div)
                ->where('school_infos.school_dist','=',$dis)
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($div))
        {
            $data =DB::table('withdraws')
                ->select(DB::raw('withdraws.*,school_infos.school_name,school_infos.school_div'))
                ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                ->where('school_infos.school_div','=',$div)
                ->orderby('school_infos.school_name')
                ->paginate(50);

        }
        elseif (!empty($sdate) && !empty($edate))
        {
            if(Auth::user()->school_id) {
                $scid=Auth::user()->school_id;

                $data = DB::table('withdraws')
                    ->select(DB::raw('withdraws.*,school_infos.school_name'))
                    ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                    ->whereBetween('withdraws.sett_date', [$sdate, $edate])
                    ->where('withdraws.school_id', '=', $scid )
                    ->orderby('school_infos.school_name')
                    ->paginate(50);
            }else{
                $data = DB::table('withdraws')
                    ->select(DB::raw('withdraws.*,school_infos.school_name'))
                    ->join('school_infos', 'school_infos.id', '=', 'withdraws.school_id')
                    ->whereBetween('withdraws.sett_date', [$sdate, $edate])
                    ->orderby('school_infos.school_name')
                    ->paginate(50);
            }

        }
        else
        {
            $data="";
            session()->flash("error", "Please try again");

        }


        return view('backend/settlement')->with(['data'=>$data,'school_info'=>$school_info, 'divisions' => $divisions, 'districts' => $districts]);
    }
    public function settled($id)
    {
        if(Auth::user()->school_id)
        {
            $scid=Auth::user()->school_id;
            $data =DB::table('withdraws')
                ->join('school_infos','withdraws.school_id', '=', 'school_infos.id')
                ->select('withdraws.*','school_infos.school_name')
                ->where('withdraws.id', '=', $id )
                ->where('withdraws.status', '=', 1 )
                ->where('withdraws.school_id', '=', $scid )
                ->get();
        }else{
            $data =DB::table('withdraws')
                ->join('school_infos','withdraws.school_id', '=', 'school_infos.id')
                ->select('withdraws.*','school_infos.school_name')
                ->where('withdraws.id', '=', $id )
                ->where('withdraws.status', '=', 1 )
                ->get();
        }

        return $data;
    }

}





