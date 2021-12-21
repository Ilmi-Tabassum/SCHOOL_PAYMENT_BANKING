<?php

namespace App\Http\Controllers;

use App\Models\AllNotification;
use App\Models\User;
use Intervention\Image\Facades\Image;
use App\Models\SchoolInfo;
use Illuminate\Http\Request;
use App\Models\SchoolDivision;
use App\Models\SchoolDistrict;
use App\Models\SchoolPost;
use App\Models\DenyRemark;
use Auth;
use DB;


class SchoolInfoController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {

        $flag = 0;

        if($request->get("gen")){
            // get delete data
            if($request->get("gen") == "trash"){
                $sql = "select a.id, a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status=3
                order by a.id desc";

                $school = DB::select( DB::raw($sql));
                /*$school = SchoolInfo::where("status", "=", 3);*/
            }else{
              if($request->get("gen") == "approved"){
                    $flag = 5;
                  $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name as division_name ,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status=5
                order by a.id desc";

                  $school = DB::select( DB::raw($sql));
              }else{
                    if($request->get("gen") == "active"){
                        $flag = 1;
                        $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status=1
                order by a.id desc
               ";

                        $school = DB::select( DB::raw($sql));
                }else{
                         if($request->get("gen") == "terminated"){
                            $flag = 3;
                             $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status=3
                order by a.id desc";

                    $school = DB::select( DB::raw($sql));
                }
                    }
                }
            }
        }else{
            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status !=3
                order by a.id desc";

            $school = DB::select( DB::raw($sql));
        }

       // $school = $school->paginate(10);
        $school_list = SchoolInfo::orderBy("school_name","asc")->get();
        $divisions = SchoolDivision::orderBy("division_name","asc")->get();
        $districts = SchoolDistrict::orderBy("name","asc")->get();
        $school_posts = SchoolPost::orderBy("name","asc")->get();
        $status_list = array('Active' => 'Active', 'Approved' => 'Approved', 'Pending' => 'Pending', 'Terminate' => 'Terminate');


        if($request->get("gen") == "trash"){
            return view('backend/school_trash_list')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
        }else{
            if($flag == 5){
                return view('backend/school_approved_list')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
            }else{
                if($flag == 1){
                    return view('backend/school_active_list')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
                }else{
                    if($flag == 3){
                        return view('backend/school_terminated_list')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
                    }else{
                        return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
                    }
                }
            }
        }
    }

    public function search(Request $request)
    {
        $req_div=$request->present_division_id;
        $req_dis=$request->present_district_id;
        $req_post=$request->present_post_id;
        $req_school=$request->schoolid;
        $status=$request->status;
        $status_list = array('Active' => 'Active', 'Approved' => 'Approved', 'Pending' => 'Pending', 'Terminated' => 'Terminated');

        if(!empty($req_school))
        {
            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.id='$req_school'
                and a.status !=3
               ";

            $school = DB::select( DB::raw($sql));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);


        }

        elseif (!empty($req_div) && !empty($req_dis) && !empty($req_post) && $req_dis!=0 && $req_post!=0)
        {
            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.school_div = $req_div
                and a.school_dist = $req_dis
                and a.school_ps = $req_post
                and a.status !=3
                order by a.school_name asc
               ";

            $school = DB::select( DB::raw($sql));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);

        }

        elseif (!empty($req_div) && !empty($req_dis) && $req_post==0)
        {

            // $school = DB::table("school_infos")
            //             ->join('school_divisions', 'school_infos.school_div', '=', 'school_divisions.id')
            //             ->join('school_districts', 'school_infos.school_dist', '=', 'school_districts.id')
            //             ->join('school_posts', 'school_infos.school_ps', '=', 'school_posts.id')
            //             ->select('school_infos.*', 'school_divisions.division_name', 'school_districts.name as district_name','school_posts.name as post_name')
            //             ->where('school_infos.school_div',$req_div)
            //             ->where('school_infos.school_dist',$req_dis)
            //             ->where('school_infos.status','!=',3)
            //             // ->orderBy('school_infos.school_name asc')
            //             ->get();



            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address

                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                    school_districts as c
                inner join
                    school_posts as d

                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id

                where a.school_div = $req_div
                and a.school_dist = $req_dis
                and a.status !=3

                order by a.school_name asc ";

           $school = DB::select( DB::raw($sql));
           //dd($school);

            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            return view('backend/school_info')->with(['status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts,'school_list'=>$school_list]);
        }


        elseif (!empty($req_div) &&$req_dis==0)
        {
            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.school_div = $req_div
                and a.status !=3
                order by a.school_name asc
";

            $school = DB::select( DB::raw($sql));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
        }
        elseif (!empty($status))
        {
            if($status=="Active")
            {
                $status_id=1;
            }
            elseif($status=="Approved")
            {
                $status_id=5;
            }
            elseif($status=="Terminated")
            {
                $status_id=2;
            }
            else{
                $status_id=0;
            }
            $sql = "select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                where a.status =$status_id
                order by a.school_name asc
";

            $school = DB::select( DB::raw($sql));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
        }
        else
        {

            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            $divisions = SchoolDivision::orderBy("division_name","asc")->get();
            $districts = SchoolDistrict::orderBy("name","asc")->get();
            $school_posts = SchoolPost::orderBy("name","asc")->get();
            print_r($divisions);
            exit();
            session()->flash("No data found");
            return view('backend/school_info')->with(['school_list' => $school_list,'status_list' => $status_list,'schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);

        }


    }


    public function new_onboard_schools()
    {
        $school =SchoolInfo::whereMonth('created_at', '=', date('m'))->paginate(10);
        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::all();
        $school_posts = SchoolPost::all();

       return view('backend/school-info/new_onboard_schools')->with(['schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
    }


    public function monthly_active_payment()
    {

        $monthly_active_payment =DB::table('fees_collections')
                    ->distinct()
                    ->select('school_id')
                    ->whereMonth('created_at', '=', date('m'))
                    ->get();


        $s_info = array();
        for ($i=0; $i <count($monthly_active_payment) ; $i++) {
            array_push($s_info, $monthly_active_payment[$i]->school_id);
            if ($i != count($monthly_active_payment)-1) {
                array_push( $s_info,',');
            }
        }

        $ids = implode(",",$s_info);
        $pure_ids = str_replace(',', '', $ids);

       // $school_infoo = SchoolInfo::whereIn('id', [$pure_ids])->paginate(10);


        $query ="select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name as division_name ,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
            and  a.id in($pure_ids)";
        $school_infoo = DB::select( DB::raw($query));

        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::all();
        $school_posts = SchoolPost::all();

       return view('backend/school_info')->with(['schools' => $school_infoo, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);

    }


    public function payment_dues()
    {
        $paid_school =DB::table('fees_collections')
                    ->distinct()
                    ->select('school_id')
                    ->get();

        $ss = array();

        for ($i=0; $i <count($paid_school); $i++) {
            array_push($ss,$paid_school[$i]->school_id);
            if ($i != count($paid_school)-1) {
                array_push($ss,',');
            }
        }

        $ids = implode(",",$ss);
        $pure_ids = str_replace(',', '', $ids);

        $query ="select a.id,a.status as status , a.school_name, a.school_ein, a.school_mobile, a.school_email, a.school_logo,
                b.division_name as division_name ,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
            and  a.id NOT IN($pure_ids)";
        $school_infoo = DB::select( DB::raw($query));

        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::all();
        $school_posts = SchoolPost::all();

       return view('backend/school_info')->with(['schools' => $school_infoo, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);

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
         if($request->post("hidden_school_info_id")){
            $object = SchoolInfo::find($request->post("hidden_school_info_id"));

            $object->school_name = $request->school_name;
            $object->school_ein = $request->school_ein;
            $object->school_address = $request->school_address;
            $object->school_div = (int) $request->school_division;
            $object->school_dist = (int) $request->school_district;
            $object->school_ps = (int) $request->school_post;
            $object->school_mobile = $request->school_mobile;
            $object->school_email = $request->school_email;
            $object->updated_by = Auth::user()->id;

            if($request->school_logo != null){
               $object->school_logo = $this->updateLogo($request->hidden_school_info_id, $request->school_logo);
            }

            try {
                $object->save();
                session()->flash("success", "School [" . $request->school_name . "(". $request->school_ein . ")" . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Menu [" . $request->school_name . "(". $request->school_ein . ")" . "] is not updated for duplicate entry.");
                }
            }
        }else{
            $object = new SchoolInfo;
            $object->school_name = $request->school_name;
            $object->school_ein = $request->school_ein;
            $object->school_address = $request->school_address;
            $object->school_div = (int) $request->school_division;
            $object->school_dist = (int) $request->school_district;
            $object->school_ps = (int) $request->school_post;
            $object->school_mobile = $request->school_mobile;
            $object->school_email = $request->school_email;
            $object->created_by = Auth::user()->id;


            // Logo
            if($request->school_logo != null){
                $object->school_logo = $this->upload($request->school_logo);
            }


            try {
                $object->save();
                session()->flash("success", "New School [" . $request->school_name . "(". $request->school_ein . ")" . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. School [" . $request->school_name . "(". $request->school_ein . ")"  . "] is not added for duplicate EIN entry.");
                }
            }
        }


        return back();
    }


    private function updateLogo($id, $new_photo){
        $file = "";
        if($new_photo != null){
           // get previous images. we need to delete from db and file manager
            $old_photo = SchoolInfo::find($id)->school_logo;
            $file = $old_photo;
            if($old_photo != $new_photo){
                $file = $this->upload($new_photo);
                // upload first logo
  /*              if($old_photo){
                    // now delete this previous logo
                    unlink(storage_path('school_logo/'. $old_photo));
                }*/
            }
        }

        return $file;
    }


    private function upload($file){
        if($file){
            $photo = $file;
            // take the image
            $image = Image::make($photo);
            // resize as you wish
           // $image->fit(600,600);

            // upload the image in your server location
            $thumbnail_filename = time()."_". rand(100000, 999999).".".$photo->getClientOriginalExtension();
            $image->save('storage/school_logo/'. $thumbnail_filename);

            return $thumbnail_filename;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuSetup  $menuSetup
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data = DB::table('school_infos')
        //                 ->select('school_name')
        //                 ->join('school_divisions', 'school_divisions.id', '=', 'school_infos.school_div')
        //                 ->join('school_districts', 'school_districts.id', '=', 'school_infos.school_dist')
        //                 ->join('school_posts', 'school_posts.id', '=', 'school_infos.school_ps')
        //                 ->where('school_infos.id', $id)
        //                 ->get();

        $sql = "select a.id, a.school_name, a.school_ein, a.school_mobile, a.school_email,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                and
                    a.id = $id limit 1";
        $result = DB::select( DB::raw($sql));

        return response()->json(view('backend.school-info.view')->with(['school' => $result])->render());
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
        $object = SchoolInfo::find($id);
        // Update status
        $object->status = 3;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "School has been terminated successfully!");

        return back();
    }
    public function restore($id)
    {
        $object = SchoolInfo::find($id);
        // Update status
        $object->status = 1;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "School restored from  trash successfully!");

        return back();
    }
    public function approve($id)
    {
        $object = SchoolInfo::find($id);
        // Update status
        $object->status = 1;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "School status changed from pending to active successfully!");

        return back();
    }
    public function pending($id)
    {
        $object = SchoolInfo::find($id);
        // Update status
        $object->status = 0;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "School status changed from active to pending successfully!");

        return back();
    }


    public function approvedView($id)
    {
        $sql = "select a.id, a.school_name, a.school_ein, a.school_mobile, a.school_email,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                and
                    a.id = $id limit 1";
        $result = DB::select( DB::raw($sql));

        return response()->json(view('backend.school-info.approved')->with(['school' => $result])->render());
    }

    public function approved($id){
        $object = SchoolInfo::find($id);
        $object->status = 5;
        $object->approved_by = Auth::user()->id;
        $object->approved_date = date('Y-m-d H:i:s');
        $object->save();

        session()->flash("success", "$object->school_name is approved successfully!");
        return back();
    }
    public function terminate($id){

        $object = SchoolInfo::find($id);
        $object->status = 2;
        $object->approved_by = Auth::user()->id;
        $object->approved_date = date('Y-m-d H:i:s');
        $object->save();
        $objects = User::where('school_id',$id)->get();
        if(!empty($objects))
        {
            foreach ($objects as $object)
            {
                $object->status = 0;
                $object->save();
            }
        }



        session()->flash("success", "$object->school_name is terminated successfully!");
        return back();
    }


    public function deniedView($id)
    {
        $sql = "select a.id, a.school_name, a.school_ein, a.school_mobile, a.school_email,
                b.division_name,
                c.name as district_name,
                d.name as post_name,
                a.school_address
                from
                    school_infos as a
                inner join
                    school_divisions as b
                inner join
                 school_districts as c
                inner join
                    school_posts as d
                on
                    a.school_div = b.id
                and
                    a.school_dist = c.id
                and
                    a.school_ps = d.id
                and
                    a.id = $id limit 1";
        $result = DB::select( DB::raw($sql));

        return response()->json(view('backend.school-info.denied')->with(['school' => $result])->render());
    }

    public function changeApprovedToActive($id){
        $object = SchoolInfo::find($id);
        $object->status = 1;
        $object->confirm_by = Auth::user()->id;
        $object->confirm_date = date('Y-m-d H:i:s');
        $object->save();

        session()->flash("success", "$object->school_name is active successfully!");
        return back();
    }

    public function pendingOnboardRequestList(){
        $school = SchoolInfo::where("status", "=", 0);

        $school = $school->paginate(10);
        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::all();
        $school_posts = SchoolPost::all();

        return view('backend/pendingOnboardRequestList')->with(['schools' => $school, 'divisions' => $divisions, 'districts' => $districts, 'school_posts' => $school_posts]);
    }

    public function loading_school_info_ajax_hit($id){
        $object = SchoolInfo::find($id);
        return $object;
    }
}
