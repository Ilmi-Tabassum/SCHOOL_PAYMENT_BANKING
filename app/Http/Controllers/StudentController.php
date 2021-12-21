<?php

namespace App\Http\Controllers;

use App\Models\officerpanel\CreateUser;
use App\Models\schoolpanel\AssignSection;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\SchoolDivision;
use App\Models\SchoolDistrict;
use App\Models\SchoolPost;
use App\Models\DenyRemark;
use App\Models\StudentAcademic;
use App\Models\StudentGuardianInfo;
use App\Models\SchoolInfo;
use App\Models\ClassInfo;
use App\Models\Shift;
use App\Models\Section;
use App\Models\Session;
use App\Models\Group;
use Auth;
use DB;

use App\Imports\StudentImport;
use App\Imports\SectionImport;
use Maatwebsite\Excel\Facades\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;


class StudentController extends Controller
{

    // Get all without delete status(2)
    public function index(Request $request)
    {



        if(isset(Auth::user()->school_id)){
            $student = DB::table("students as a")
                   ->select("a.id", "a.student_id", "a.name", "a.mobile_number", "a.status", "b.father_name", "b.mother_name", "b.guardian_contact_no", "d.name as class_name", "e.school_name as school_name")
                   ->leftjoin("student_guardian_infos as b", "b.student_id", "=", "a.id")
                   ->leftjoin("student_academics as c", "c.student_id", "=", "a.id")
                   ->leftjoin("class_infos as d", "d.id", "=", "c.class_id")
                   ->leftjoin("school_infos as e", "e.id", "=", "c.school_id")
                   ->orderByDesc("a.id")
                   ->where("c.school_id", "=", Auth::user()->school_id);
        }else{
            $student = DB::table("students as a")
                   ->select("a.id", "a.student_id", "a.name", "a.mobile_number", "a.status", "b.father_name", "b.mother_name", "b.guardian_contact_no", "d.name as class_name", "e.school_name as school_name")
                   ->leftjoin("student_guardian_infos as b", "b.student_id", "=", "a.id")
                   ->leftjoin("student_academics as c", "c.student_id", "=", "a.id")
                   ->leftjoin("class_infos as d", "d.id", "=", "c.class_id")
                   ->leftjoin("school_infos as e", "e.id", "=", "c.school_id")
                   ->orderByDesc("a.id");
           // dd($student);
        }


        if($request->get("gen")){
            if($request->get("gen") == "trash"){
              $student = $student->where("a.status", "=", 2);
            }
        }else{
            $student = $student->where("a.status", "!=", 2);
        }


        // Search
        if($request->division_id){
             $student = $student->where("a.present_division_id", "=", $request->division_id);
        }

        if($request->district_id){
             $student = $student->where("a.present_district_id", "=", $request->district_id);
        }

        if($request->school_id){
             $student = $student->where("c.school_id", "=", $request->school_id);
        }

        if($request->class_id){
             $student = $student->where("c.class_id", "=", $request->class_id);
        }

        if($request->section_id){
             $student = $student->where("c.section_id", "=", $request->section_id);
        }

        if($request->search_by_anykey){
            //echo $request->search_by_anykey;
            //return 0;
             $student = $student->Where('a.student_id', 'like', '%' . $request->search_by_anykey . '%')
                                ->orWhere('a.name', 'like', '%' . $request->search_by_anykey . '%')
                                ->orWhere('a.mobile_number', 'like', '%' . $request->search_by_anykey . '%')
                                ->orWhere('b.father_name', 'like', '%' . $request->search_by_anykey . '%')
                                ->orWhere('b.mother_name', 'like', '%' . $request->search_by_anykey . '%');
        }



        $student = $student->orderBy('id', 'desc');
        $student = $student->paginate(50);

        $scid=Auth::user()->school_id;
        $divisions = SchoolDivision::orderBy("division_name", "asc")->get();
        $districts = SchoolDistrict::where("division_id", $request->division_id)->orderBy("name", "asc")->get();
        $schools = SchoolInfo::orderBy("school_name", "asc")->get();
        if(Auth::user()->school_id)
        {
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        }else{
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
        }

        $sections = Section::orderBy("name", "asc")->get();
        $shifts = Shift::orderBy("name", "asc")->get();

        if($request->get("gen")){
            if($request->get("gen") == "trash"){
                return view('backend/student/trash')->with(['students' => $student, 'divisions' => $divisions, 'districts' => $districts, 'schools' => $schools, 'classes' => $classes, 'sections' => $sections, 'shifts' => $shifts]);
            }
        }else{
            return view('backend/student/index')->with(['students' => $student, 'divisions' => $divisions, 'districts' => $districts, 'schools' => $schools, 'classes' => $classes, 'sections' => $sections, 'shifts' => $shifts]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blood_groups = array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-');
        $gender = array("male" => "Male", "female" => "Female");
        $divisions = SchoolDivision::all();

        if(isset(Auth::user()->school_id)){
            $schools = SchoolInfo::where("id", Auth::user()->school_id)->get();
        }else{
            $schools = SchoolInfo::all();
        }
        $scid=Auth::user()->school_id;
        if(Auth::user()->school_id)
        {
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
            $shift = DB::select(DB::raw("
            SELECT DISTINCT shifts.id id,shifts.name name
            FROM assign_shifts
            JOIN shifts on assign_shifts.shift_id=shifts.id
            WHERE assign_shifts.school_id = $scid
            ORDER BY shifts.name ASC"));
            $section = DB::select(DB::raw("
            SELECT DISTINCT sections.id id,sections.name name
            FROM assign_sections
            JOIN sections on assign_sections.section_id=sections.id
            WHERE assign_sections.school_id = $scid
            ORDER BY sections.name ASC"));
            $session = DB::select(DB::raw("
            SELECT DISTINCT sessions.id id,sessions.name name
            FROM assign_sessions
            JOIN sessions on assign_sessions.session_id=sessions.id
            WHERE assign_sessions.school_id = $scid
            ORDER BY sessions.name ASC"));
            $group = Group::all();
        }else{
            $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
            $shift = Shift::all();
            $section = Section::all();
            $session = Session::all();
            $group = Group::all();
        }


        $current_school_id = Auth::user()->school_id;

        $s_id=$this->generateStudentID($current_school_id, date('Y'));

        return view('backend/student/add')
                                        ->with(["blood_groups" => $blood_groups,
                                                "gender" => $gender,
                                                "divisions" => $divisions,
                                                "schools" => $schools,
                                                "classes" => $classes,
                                                "shift" => $shift,
                                                "section" => $section,
                                                "session" => $session,
                                                "group" => $group,
                                            "s_id" => $s_id

                                        ]);
    }

    public function createReadmission()
    {

        $blood_groups = array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-');
        $gender = array("male" => "Male", "female" => "Female");
        $divisions = SchoolDivision::all();
        $schools = SchoolInfo::all();
        $classes = ClassInfo::all();
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();

        return view('backend/student/readmission')
                                        ->with(["blood_groups" => $blood_groups,
                                                "gender" => $gender,
                                                "divisions" => $divisions,
                                                "schools" => $schools,
                                                "classes" => $classes,
                                                "shift" => $shift,
                                                "section" => $section,
                                                "session" => $session,
                                                "group" => $group,
                                            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
         if($request->post("hidden_student_id")){
            $student = Student::find($request->post("hidden_student_id"));
            $student->student_id = $request->student_id;
            $student->name_bn = $request->name_bn;
            $student->name = $request->name;
            $student->present_division_id = $request->present_division_id;
            $student->present_district_id = (int) $request->present_district_id;
            $student->present_post_id = (int) $request->present_post_id;
            $student->present_address = $request->present_address;
             if($request->sameas)
             {   $student->permanent_division_id = $request->present_division_id;
                 $student->permanent_district_id = (int) $request->present_district_id;
                 $student->permanent_post_id = (int) $request->present_post_id;
                 $student->permanent_address =  $request->present_address;
             }else{
                 $student->permanent_division_id = $request->permanent_division_id;
                 $student->permanent_district_id = (int) $request->permanent_district_id;
                 $student->permanent_post_id = (int) $request->permanent_post_id;
                 $student->permanent_address =  $request->permanent_address;
             }
            $student->date_of_birth = $this->processMysqlDate($request->date_of_birth);
            $student->blood_group = $request->blood_group;
            $student->mobile_number = $request->mobile_number;
            $student->email_address = $request->email_address;
            $student->gender = $request->gender;
            $student->admission_date = date('Y-m-d H:i:s');
            $student->updated_by = Auth::user()->id;

            if($request->student_photo != null){
               $student->photo = $this->updatePhoto($request->hidden_student_id, $request->student_photo);
            }


            $guardian_info = StudentGuardianInfo::find($request->post("hidden_std_guardian_id"));
            $guardian_info->father_name = $request->father_name;
            $guardian_info->mother_name = $request->mother_name;
            $guardian_info->guardian_name = $request->guardian_name;
            $guardian_info->guardian_contact_no = $request->guardian_contact_no;
            $guardian_info->father_nid = $request->father_nid;
            $guardian_info->mother_nid = $request->mother_nid;
            $guardian_info->relation_with_student = $request->relation_with_student;


            $student_academics = StudentAcademic::find($request->post("hidden_std_academic_id"));
             if(isset(Auth::user()->school_id)){
                 $student_academics->school_id = Auth::user()->school_id;
             }else{
                 $student_academics->school_id = (int) $request->school_id;
             }
            $student_academics->school_id = (int) $request->school_id;
            $student_academics->class_id = (int) $request->class_id;
            $student_academics->shift_id = (int) $request->shift_id;
            $student_academics->section_id = (int) $request->section_id;
            $student_academics->session_id = (int) $request->session_id;
            $student_academics->group_id = (int) $request->group_id;
            $student_academics->std_roll = $request->std_roll;

            try {
                $student->save();
                $guardian_info->save();
                $student_academics->save();

                session()->flash("success", "Student [" . $request->name . "(". $request->student_id . ")" . "] is updated successfully!");
            } catch(\Illuminate\Database\QueryException $e){

                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Menu [" . $request->name . "(". $request->student_id . ")" . "] is not updated for duplicate entry.");
                }
            }
        }else{
            $student = new Student;
            $student->student_id = $request->student_id;
            $student->name_bn = $request->name_bn;
            $student->name = $request->name;
            $student->present_division_id = $request->present_division_id;
            $student->present_district_id = (int) $request->present_district_id;
            $student->present_post_id = (int) $request->present_post_id;
            $student->present_address = $request->present_address;
            if($request->sameas)
            {   $student->permanent_division_id = $request->present_division_id;
                $student->permanent_district_id = (int) $request->present_district_id;
                $student->permanent_post_id = (int) $request->present_post_id;
                $student->permanent_address =  $request->present_address;
            }else{
                $student->permanent_division_id = $request->permanent_division_id;
                $student->permanent_district_id = (int) $request->permanent_district_id;
                $student->permanent_post_id = (int) $request->permanent_post_id;
                $student->permanent_address =  $request->permanent_address;
            }

            $student->date_of_birth = $this->processMysqlDate($request->date_of_birth);
            $student->mobile_number = $request->mobile_number;
            $student->email_address = $request->email_address;
            $student->blood_group = $request->blood_group;
            $student->gender = $request->gender;
            $student->admission_date = date('Y-m-d H:i:s');
            $student->created_by = Auth::user()->id;


            if($request->student_photo != null){
                $student->photo = $this->upload($request->student_id, $request->student_photo);
            }


            $guardian_info = new StudentGuardianInfo;
            $guardian_info->father_name = $request->father_name;
            $guardian_info->mother_name = $request->mother_name;
            $guardian_info->guardian_name = $request->guardian_name;
            $guardian_info->guardian_contact_no = $request->guardian_contact_no;
            $guardian_info->father_nid = $request->father_nid;
            $guardian_info->mother_nid = $request->mother_nid;
            $guardian_info->relation_with_student = $request->relation_with_student;


            $student_academics = new StudentAcademic;
             if(isset(Auth::user()->school_id)){
                 $student_academics->school_id = Auth::user()->school_id;
             }else {
                 $student_academics->school_id = (int)$request->school_id;
             }
            $student_academics->class_id = (int) $request->class_id;
            $student_academics->shift_id = (int) $request->shift_id;
            $student_academics->section_id = (int) $request->section_id;
            $student_academics->session_id = (int) $request->session_id;
            $student_academics->group_id = (int) $request->group_id;
            $student_academics->std_roll = $request->std_roll;

    /*         $data = new CreateUser;
             $data->name = $request->guardian_name;
             $data->email = $request->email_address;
             $data->mobile_number = $request->guardian_contact_no;
             $data->user_type_id = 3;
             $data->school_id = Auth::user()->school_id;
             $data->created_by = Auth::user()->id;
             $data->password = Hash::make("123456");*/

            try {

                $id = $student->save();
                $id = DB::getPdo()->lastInsertId();
                $student_academics->student_id = $id;
                $guardian_info->student_id = $id;

                $student_academics->save();
              $guardian_info->save();
               // $data->save();

/*
                $data = new CreateUser;
                $data->name = $request->guardian_name;
                $data->email = $request->email_address;
                $data->mobile_number = $request->guardian_contact_no;
                $data->user_type_id = 3;
                $data->school_id = Auth::user()->school_id;
                $data->created_by = Auth::user()->id;
                $data->password = Hash::make("123456");*/
               // dd($data);
/*                DB::table('users')->insert([
                    'name' => $request->guardian_name,
                    'email' => $request->email_address,
                    'mobile_number' => $request->guardian_contact_no,
                    'user_type_id' => 3,
                    'school_id' => Auth::user()->school_id,
                    'created_by' => Auth::user()->id,
                    'password' => Hash::make("123456")


                ]);*/


                    //$data->save();


                session()->flash("success", "Student [" . $request->name . "(". $request->student_id . ")" . "] is created successfully!");
            } catch(\Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == '1062'){
                    session()->flash("error", "We are sorry. Student [" . $request->name . "(". $request->student_id . ")"  . "] is not added for duplicate Student ID entry.");
                }
            }
        }


        return back();
    }



    private function upload($student_id, $file){
        if($file){
            $photo = $file;
            // take the image
            $image = Image::make($photo);
            // resize as you wish
            $image->fit(600,600);

            // upload the image in your server location
            //$thumbnail_filename = time()."_". rand(100000, 999999).".".$photo->getClientOriginalExtension();
            $thumbnail_filename = $student_id.".".$photo->getClientOriginalExtension();
            $image->save('storage/student/'. $thumbnail_filename);

            return $thumbnail_filename;
        }
    }

     private function updatePhoto($id, $new_photo){
        $file = "";
        if($new_photo != null){
           // get previous images. we need to delete from db and file manager
            $student = Student::find($id);
            $old_photo = $student->photo;
            $student_id = $student->student_id;

            $file = $old_photo;
            if($old_photo != $new_photo){
                $file = $this->upload($student_id, $new_photo);
                // upload first logo
                if($old_photo){
                    // now delete this previous logo
                   // unlink(storage_path('student/'. $old_photo));
                   // unlink(storage_path('/student/'. $old_photo));
                    unlink("storage/student/" . $old_photo);
                }
            }
        }

        return $file;
    }

    public function processMysqlDate($date){
        if($date > 0){
            $createdAt = explode('/', $date);
            return $createdAt[2].'-'.$createdAt[1].'-'.$createdAt[0];
        }else{
            return "";
        }
    }


    public function view_details($id){
        $sql = "select a.*, a.id as main_id, a.student_id as std_id, b.*, c.*
                from students as a
                inner join student_guardian_infos as b
                inner join student_academics as c
                inner join school_infos as d
                inner join class_infos as e
                inner join shifts as f
                inner join sections as g
                inner join `groups` as i
                on
                a.id = b.student_id and
                a.id = c.student_id and
                c.school_id = d.id and
                c.class_id = e.id and
                c.shift_id = f.id and
                c.section_id = g.id and

                c.group_id = i.id
                where a.id = $id";
        $student = DB::select( DB::raw($sql));
        //$divisions = SchoolDivision::all();

      //  return view('backend/student/view_details')->with(['student' => $result, 'divisions' => $divisions])->render();
        $blood_groups = array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-');
        $gender = array("male" => "Male", "female" => "Female");
        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::where("division_id", $student[0]->present_division_id)->get();
        $school_posts = SchoolPost::where("dist_id", $student[0]->present_district_id)->get();
        $permanent_districts = SchoolDistrict::where("division_id", $student[0]->permanent_division_id)->get();
        $permanent_school_posts = SchoolPost::where("dist_id", $student[0]->permanent_district_id)->get();
        $schools = SchoolInfo::all();
        $classes = ClassInfo::all();
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();

        return view('backend/student/view_details')
                                        ->with(["student" => $student,
                                                "blood_groups" => $blood_groups,
                                                "gender" => $gender,
                                                "divisions" => $divisions,
                                                "districts" => $districts,
                                                "school_posts" => $school_posts,
                                                "permanent_districts" => $permanent_districts,
                                                "permanent_school_posts" => $permanent_school_posts,
                                                "schools" => $schools,
                                                "classes" => $classes,
                                                "shift" => $shift,
                                                "section" => $section,
                                                "session" => $session,
                                                "group" => $group,
                                            ]);
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
    public function edit($id)
    {
        if($id > 0){
            $student = DB::table("students as a")
                   ->select("a.id", "a.student_id", "a.name", "a.name_bn", "a.present_division_id", "a.permanent_division_id", "a.present_district_id", "a.permanent_district_id", "a.present_post_id", "a.permanent_post_id", "a.present_address", "a.permanent_address", "a.mobile_number", "a.email_address",
                       "a.date_of_birth", "a.blood_group", "a.gender", "a.admission_date", "a.status", "a.photo",
                            "b.id as std_guardian_id", "b.father_name", "b.father_nid", "b.mother_name", "b.mother_nid", "b.guardian_name", "b.guardian_contact_no", "b.relation_with_student",
                            "c.id as std_academic_id", "c.school_id", "c.class_id", "c.shift_id", "c.section_id", "c.session_id", "c.group_id", "c.std_roll",
                            "d.name as class_name",
                            "e.name as group_name"
                        )
                   ->join("student_guardian_infos as b", "b.student_id", "=", "a.id")
                   ->join("student_academics as c", "c.student_id", "=", "a.id")
                   ->join("class_infos as d", "d.id", "=", "c.class_id")
                   ->join("groups as e", "e.id", "=", "c.group_id")
                   ->where("a.id", "=", $id)
                   ->get();
        }else{
            $student = array();
        }



        $blood_groups = array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-');
        $gender = array("male" => "Male", "female" => "Female");
        $divisions = SchoolDivision::all();
        $districts = SchoolDistrict::where("division_id", $student[0]->present_division_id)->get();
        $school_posts = SchoolPost::where("dist_id", $student[0]->present_district_id)->get();
        $permanent_districts = SchoolDistrict::where("division_id", $student[0]->permanent_division_id)->get();
        $permanent_school_posts = SchoolPost::where("dist_id", $student[0]->permanent_district_id)->get();


        if(isset(Auth::user()->school_id)){
            $schools = SchoolInfo::where("id", Auth::user()->school_id)->get();
        }else{
            $schools = SchoolInfo::all();
        }

        $classes = ClassInfo::all();
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();

        return view('backend/student/edit')
                                        ->with(["student" => $student,
                                                "blood_groups" => $blood_groups,
                                                "gender" => $gender,
                                                "divisions" => $divisions,
                                                "districts" => $districts,
                                                "school_posts" => $school_posts,
                                                "permanent_districts" => $permanent_districts,
                                                "permanent_school_posts" => $permanent_school_posts,
                                                "schools" => $schools,
                                                "classes" => $classes,
                                                "shift" => $shift,
                                                "section" => $section,
                                                "session" => $session,
                                                "group" => $group,
                                            ]);
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
        // Update status
        $object = Student::find($id);
        $object->status = 2;
        $object->deleted_by = Auth::user()->id;
        $object->save();

        session()->flash("success", "Student is move to trash successfully!");

        return back();
    }


    public function createUploadStudent(){
        $schools = SchoolInfo::all();
        $classes = ClassInfo::all();
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();

        return view('backend/student/upload-student')
                                        ->with([
                                                "schools" => $schools,
                                                "classes" => $classes,
                                                "shift" => $shift,
                                                "section" => $section,
                                                "session" => $session,
                                                "group" => $group,
                                            ]);
    }


    public function uploadStudent(Request $request){

        if($request->hasFile('student_batch_file') && $request->file('student_batch_file')->getClientOriginalExtension()=="xls"){
            $extension = $request->file('student_batch_file')->getClientOriginalExtension();
            $path = $request->file("student_batch_file")->getRealPath();
            $objPHPExcel = PHPExcel_IOFactory::load($path);
           // Specify the excel sheet index
            $sheet = $objPHPExcel->getSheet(0);
            $total_rows = $sheet->getHighestRow();
            $highestColumn      = $sheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            //  loop over the rows
            for ($row = 1; $row <= $total_rows; ++ $row) {
                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    $records[$row][$col] = $val;
                }
            }

            $i = 0;
            foreach ($records as $row) {
                if($i > 0){
                 //echo $row[0] . ", " . $row[1] . ",". $row[2] . "," . $row[3] . ",". $row[4] . ",". $row[5] . ",". $row[6] . ",". $row[7] . ",". $row[8] ."<br>";
                    $check_std=$this->checstudent($request->session_id,$request->school_id,$request->class_id,$row[2]);
                    if($check_std)
                    {

                    }
                    else
                    {
                        $student = new Student;
                        $student->student_id =  $this->generateStudentID($request->school_id, date('Y'));
                        $student->name = $row[0];
                        $student->present_division_id = 0;
                        $student->present_district_id = 0;
                        $student->present_post_id = 0;
                        $student->present_address = $row[7];
                        $student->permanent_division_id = 0;
                        $student->permanent_district_id = 0;
                        $student->permanent_post_id = 0;
                        $student->permanent_address =  $row[8];
                        $student->date_of_birth = $row[5];
                        $student->mobile_number = $row[6];
                        $student->email_address = '';
                        $student->blood_group = $row[9];
                        $student->gender = $row[1];
                        $student->admission_date = date('Y-m-d H:i:s');
                        $student->created_by = Auth::user()->id;


                        $guardian_info = new StudentGuardianInfo;
                        $guardian_info->father_name = $row[3];
                        $guardian_info->mother_name = $row[4];


                        $student_academics = new StudentAcademic;
                        $student_academics->school_id = (int) $request->school_id;
                        $student_academics->class_id = (int) $request->class_id;
                        $student_academics->shift_id = (int) $request->shift_id;
                        $student_academics->section_id = (int) $request->section_id;
                        $student_academics->session_id = (int) $request->session_id;
                        $student_academics->group_id = (int) $request->group_id;
                        $student_academics->std_roll = $row[2];

                        $id = $student->save();
                        $id = DB::getPdo()->lastInsertId();
                        $student_academics->student_id = $id;
                        $guardian_info->student_id = $id;

                        $student_academics->save();
                        $guardian_info->save();
                    }
                }
                $i++;
            }
            session()->flash("success", $i." students imported successfully done!");
        }
        else
        {
            session()->flash("error", "Excel file not found! Please select a valid .xls file");
        }



        return back();
    }


    public function checstudent($session_id,$school_id,$class_id,$std_roll)
     {
        $stdid_exist = StudentAcademic::where('session_id', '=', $session_id)
                        ->where('school_id','=',$school_id)
                        ->where('class_id','=',$class_id)
                        ->where('std_roll','=',$std_roll)
                        ->where('session_id','=',$session_id)
                        ->first();
        if (empty($stdid_exist)) {
            return false;
        }
        else
        {
            return true;
        }
     }




    public function uploadStudent2(Request $request){

        if($request->hasFile('student_batch_file')){
            $path = $request->file("student_batch_file")->getRealPath();
            $data = Excel::import(new StudentImport, 'student.xlsx');

            //$data = Excel::import(new StudentImport, 'student.xlsx', 'public', \Maatwebsite\Excel\Excel::XLSX);
            if(count($data) > 0){
                echo "Yes";
                foreach ($data as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if(array_key_exists($key2, $excel_file_data)){
                            $excel_file_data[$key2] .= ",". $value2;
                        }else{
                            $excel_file_data[$key2] = $value2;
                        }
                    } // ./ nested loop
                } // ./ loop
                // ./ data loop
            } // ./ data() > 0
        }// ./ hasFile
    }






    public function generateStudentID2($school_id, $year){
        $sql = "select a.student_id
            from students as a
            inner join student_academics as b
            on
            a.id = b.student_id
            and
            b.school_id = $school_id
            and b.session_id = $year";

        $student_id = DB::select(DB::raw($sql));

        if(count($student_id) > 0){
            echo "yes";
        }else{

        }
    }

    public function generateStudentID($school_id, $year)
    {
        // Student ID Formula 0000 0000 00000
        // First 4 digit for Year
        // Second 4 digit for School Code like 1001
        // Last 5 digit autoincrement Student id for school like 00001
        // For a student 2021100100001 This is a unique student ID
        // $school_code = SchoolInfo::find($school_id)->school_ein;
        $school_code = str_pad($school_id, 4, '0', STR_PAD_LEFT);

        $sql = "select count(a.student_id) as total_std
                                    from students as a
                                    inner join student_academics as b
                                    on
                                    a.id = b.student_id
                                    and
                                    b.school_id = '$school_id' and session_id='$year'";

        $student_id = DB::select(DB::raw($sql));
        if(!empty($student_id))
        {
            if($student_id[0]->total_std > 0)
            {
                $student_id = $student_id[0]->total_std;
            }
            else{
                $student_id =0;
            }

            // $student_id=Student::where('school_id','=',$school_id)->where('session_id','=',$year)->count()+1;
            $student_id = str_pad($student_id, 5, '0', STR_PAD_LEFT);

            $student_id = $year . "" . $school_code . "" . $student_id;

            //Check Duplicate Student ID
            $stdid_exist = Student::where('student_id', '=', $student_id)->first();

            if (empty($stdid_exist)) {

            } else {
                // $student_id=Student::where('school_id','=',$school_id)->where('session_id','=',$year)->max('school_id');
                $student_id = DB::table('students as t1')
                    ->join('student_academics as t2', 't1.id', '=', 't2.student_id')
                    ->where('t2.school_id', '=', $school_id)->where('t2.session_id', '=', $year)->max('t1.student_id');
                $student_id = $student_id + 1;
            }
            return $student_id;

        }


        return false;
    }

    public function restore($id)
    {
        $object = Student::find($id);
        $object->status = 1;
        $object->save();

        session()->flash("success", "Student is restored from trash successfully!");

        return back();
    }

}
