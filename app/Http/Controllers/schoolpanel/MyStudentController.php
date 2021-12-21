<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Models\AllNotification;
use App\Models\ClassInfo;
use App\Models\Group;
use App\Models\officerpanel\CreateUser;
use App\Models\SchoolDivision;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\MyStudent;

use App\Models\Section;
use App\Models\Session;
use App\Models\Shift;
use App\Models\Student;
use App\Models\StudentAcademic;
use App\Models\StudentGuardianInfo;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Response;

class MyStudentController extends Controller
{

   public function downloadExcelFormat( Request $request)
    {
        $file= public_path(). "/bulk_student_upload_format.xls";
        $headers = array(
                  'Content-Type: application/xls',
                );

        return Response::download($file, 'bulk_student_upload_format.xls', $headers);

    }


    public function index(Request $request)
    {
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        if ($request->get("gen")) {
            // get delete data
            if ($request->get("gen") == "trash") {
                $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id ,student.student_id studentid,students.name , student_academics.std_roll,sections.name sname,school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id

            WHERE students.status=2 AND student_academics.school_id=1 "));
            }

        } else {
            // get != deteled data

            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id

            WHERE students.status != 2 AND student_academics.school_id=1"));

        }
        /* $students = $students->paginate(10);*/

        return view('schoolpanel/my_student/my_student')->with(['students' => $students, 'classes' => $classes]);
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
        $schools = SchoolInfo::all();
        $classes = ClassInfo::all();
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();
        $current_school_id = auth::user()->school_id;

        $s_id=$this->generateStudentID($current_school_id, date('Y'));



        return view('schoolpanel/my_student/create')
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_school_id = auth()->user()->school_id;
        if ($request->post("hidden_student_id")) {

            $student = Student::find($request->post("hidden_student_id"));
            $student->student_id = $request->student_id;
            $student->name_bn = $request->name_bn;
            $student->name = $request->name;
            $student->present_division_id = $request->present_division_id;
            $student->present_district_id = (int)$request->present_district_id;
            $student->present_post_id = (int)$request->present_post_id;
            $student->present_address = $request->present_address;
            $student->permanent_division_id = $request->permanent_division_id;
            $student->permanent_district_id = (int)$request->permanent_district_id;
            $student->permanent_post_id = (int)$request->permanent_post_id;
            $student->permanent_address = $request->permanent_address;
            $student->date_of_birth = $this->processMysqlDate($request->date_of_birth);
            $student->blood_group = $request->blood_group;
            $student->mobile_number = $request->mobile_number;
            $student->email_address = $request->email_address;
            $student->gender = $request->gender;
            $student->admission_date = date('Y-m-d H:i:s');
            $student->updated_by = Auth::user()->id;


            $guardian_info = StudentGuardianInfo::find($request->post("hidden_std_guardian_id"));
            $guardian_info->father_name = $request->father_name;
            $guardian_info->mother_name = $request->mother_name;
            $guardian_info->guardian_name = $request->guardian_name;
            $guardian_info->guardian_contact_no = $request->guardian_contact_no;
            $guardian_info->father_nid = $request->father_nid;
            $guardian_info->mother_nid = $request->mother_nid;
            $guardian_info->relation_with_student = $request->relation_with_student;


            $student_academics = StudentAcademic::find($request->post("hidden_std_academic_id"));
            $student_academics->school_id = $user_school_id;
            $student_academics->class_id = (int)$request->class_id;
            $student_academics->shift_id = (int)$request->shift_id;
            $student_academics->section_id = (int)$request->section_id;
            $student_academics->session_id = (int)$request->session_id;
            $student_academics->group_id = (int)$request->group_id;
            $student_academics->std_roll = $request->std_roll;

            try {
                $student->save();
                $guardian_info->save();
                $student_academics->save();

                session()->flash("success", "Student [" . $request->name . "(" . $request->student_id . ")" . "] is updated successfully!");
            } catch (\Illuminate\Database\QueryException $e) {
                var_dump($e);
                return 0;
                $errorCode = $e->errorInfo[1];
                if ($errorCode == '1062') {
                    session()->flash("error", "We are sorry. Menu [" . $request->name . "(" . $request->student_id . ")" . "] is not updated for duplicate entry.");
                }
            }
        } else {
            $student = new Student;
            $student->student_id = $request->student_id;
            $student->name_bn = $request->name_bn;
            $student->name = $request->name;
            $student->present_division_id = $request->present_division_id;
            $student->present_district_id = (int)$request->present_district_id;
            $student->present_post_id = (int)$request->present_post_id;
            $student->present_address = $request->present_address;
            $student->permanent_division_id = $request->permanent_division_id;
            $student->permanent_district_id = (int)$request->permanent_district_id;
            $student->permanent_post_id = (int)$request->permanent_post_id;
            $student->permanent_address = $request->permanent_address;
            $student->date_of_birth = $this->processMysqlDate($request->date_of_birth);
            $student->mobile_number = $request->mobile_number;
            $student->email_address = $request->email_address;
            $student->blood_group = $request->blood_group;
            $student->gender = $request->gender;
            $student->admission_date = date('Y-m-d H:i:s');
            $student->created_by = Auth::user()->id;


            $guardian_info = new StudentGuardianInfo;
            $guardian_info->father_name = $request->father_name;
            $guardian_info->mother_name = $request->mother_name;
            $guardian_info->guardian_name = $request->guardian_name;
            $guardian_info->guardian_contact_no = $request->guardian_contact_no;
            $guardian_info->father_nid = $request->father_nid;
            $guardian_info->mother_nid = $request->mother_nid;
            $guardian_info->relation_with_student = $request->relation_with_student;


            $student_academics = new StudentAcademic;
            $student_academics->school_id = $user_school_id;
            $student_academics->class_id = (int)$request->class_id;
            $student_academics->shift_id = (int)$request->shift_id;
            $student_academics->section_id = (int)$request->section_id;
            $student_academics->session_id = (int)$request->session_id;
            $student_academics->group_id = (int)$request->group_id;
            $student_academics->std_roll = $request->std_roll;


            try {

                $id = $student->save();
                $id = DB::getPdo()->lastInsertId();
                $student_academics->student_id = $id;
                $guardian_info->student_id = $id;

                $student_academics->save();
                $guardian_info->save();

                session()->flash("success", "Student [" . $request->name . "(" . $request->student_id . ")" . "] is created successfully!");
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == '1062') {
                    session()->flash("error", "We are sorry. Student [" . $request->name . "(" . $request->student_id . ")" . "] is not added for duplicate Student ID entry.");
                }
            }
        }


        return back();
    }

    public function processMysqlDate($date)
    {
        if ($date > 0) {
            $createdAt = explode('/', $date);
            return $createdAt[2] . '-' . $createdAt[1] . '-' . $createdAt[0];
        } else {
            return "";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\schoolpanel\MyStudent $myStudents
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $classes = DB::select(DB::raw("SELECT id,name FROM class_infos"));
        $student_id = $request->student_id;
        $student_name = $request->student_name;
        $guardian_name = $request->guardian_name;
        $guardian_number = $request->guardian_number;
        $guardian_email = $request->guardian_email;
        $status = $request->status;
        $class_id = $request->class_name;
        if (!empty($student_id)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id, students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE student_academics.student_id='$student_id'"));
        } elseif (!empty($student_name)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE students.name='$student_name' "));
        } elseif (!empty($guardian_name)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE student_guardian_infos.guardian_name='$guardian_name'"));
        } elseif (!empty($guardian_number)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE student_guardian_infos.guardian_contact_no='$guardian_number'"));
        } elseif (!empty($guardian_email)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE student_guardian_infos.guardian_email='$guardian_email'"));
        } elseif (!empty($status)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE students.status=$status"));
        } elseif (!empty($class_id)) {
            $students = DB::select(DB::raw("
            SELECT student_academics.id,student_academics.student_id , students.student_id studentid ,students.name , student_academics.std_roll,sections.name sname,
            school_infos.school_name schname,class_infos.name cname,shifts.name shname,sessions.name sename,students.status,
            students.name,student_guardian_infos.guardian_name,student_guardian_infos.guardian_contact_no,student_academics.class_id
            FROM student_academics
            INNER JOIN students ON student_academics.student_id = students.id
            INNER JOIN class_infos ON student_academics.class_id = class_infos.id
            INNER JOIN school_infos ON student_academics.school_id = school_infos.id
            INNER JOIN sections ON student_academics.section_id = sections.id
            INNER JOIN sessions ON student_academics.session_id = sessions.id
            INNER JOIN student_guardian_infos ON students.student_id = student_guardian_infos.student_id
            INNER JOIN shifts ON student_academics.shift_id = shifts.id
            WHERE  student_academics.class_id=$class_id"));
        } else {
            $students = "";
        }

        if (!empty($students)) {

            return view('schoolpanel/my_student/my_student')->with(['students' => $students, 'classes' => $classes]);
        } else {
            session()->flash("error", "No Student found");

            $students = "";
            return view('schoolpanel/my_student/my_student')->with(['students' => $students, 'classes' => $classes]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\schoolpanel\MyStudent $myStudents
     * @return \Illuminate\Http\Response
     */
    public function edit(MyStudents $myStudents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\schoolpanel\MyStudent $myStudents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyStudents $myStudents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\schoolpanel\MyStudent $myStudents
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = MyStudent::find($id);

        $data->status = 2;
        $data->save();
        session()->flash("success", "Student  deleted successfully");

        return back();
    }

    public function restore($id)
    {
        $data = MyStudent::find($id);

        $data->status = 1;
        $data->save();
        session()->flash("success", "Student removed from trash  successfully");

        return back();
    }

    public function details($id)
    {
        $details = MyStudent::find($id);
        return view('schoolpanel/student/details')->with(['details' => $details]);


    }

    public function createUploadStudent()
    {
        $schools = SchoolInfo::all();
        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $shift = Shift::all();
        $section = Section::all();
        $session = Session::all();
        $group = Group::all();

        return view('schoolpanel/upload_bulk/upload_bulk')
            ->with([
                "schools" => $schools,
                "classes" => $classes,
                "shift" => $shift,
                "section" => $section,
                "session" => $session,
                "group" => $group,
            ]);
    }


    public function uploadStudent(Request $request)
    {
        if ($request->hasFile('student_batch_file') && $request->file('student_batch_file')->getClientOriginalExtension() == "xls") {
            $extension = $request->file('student_batch_file')->getClientOriginalExtension();
            $path = $request->file("student_batch_file")->getRealPath();
            $objPHPExcel = PHPExcel_IOFactory::load($path);
            // Specify the excel sheet index
            $sheet = $objPHPExcel->getSheet(0);
            $total_rows = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $current_school_id = auth::user()->school_id;
            //  loop over the rows
            for ($row = 1; $row <= $total_rows; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    $records[$row][$col] = $val;
                }
            }

            $i = 0;
            foreach ($records as $row) {
                if ($i > 0) {
                    //echo $row[0] . ", " . $row[1] . ",". $row[2] . "," . $row[3] . ",". $row[4] . ",". $row[5] . ",". $row[6] . ",". $row[7] . ",". $row[8] ."<br>";
                    $check_std = $this->checstudent($request->session_id, $current_school_id, $request->class_id, $row[2]);
                    if ($check_std) {

                    } else {
                        $s_id=$this->generateStudentID($current_school_id, $request->session_id);
                        if(!empty($s_id))
                        {
                            $student = new Student;
                            $student->student_id = $this->generateStudentID($current_school_id, $request->session_id);
                            $student->name = $row[0];
                            $student->present_division_id = 0;
                            $student->present_district_id = 0;
                            $student->present_post_id = 0;
                            $student->present_address = $row[7];
                            $student->permanent_division_id = 0;
                            $student->permanent_district_id = 0;
                            $student->permanent_post_id = 0;
                            $student->permanent_address = $row[8];
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
                            $guardian_info->guardian_name = $row[3];
                            $guardian_info->guardian_contact_no = $row[6];




                            $student_academics = new StudentAcademic;
                            $student_academics->school_id = $current_school_id;
                            $student_academics->class_id = (int)$request->class_id;
                            $student_academics->shift_id = (int)$request->shift_id;
                            $student_academics->section_id = (int)$request->section_id;
                            $student_academics->session_id = (int)$request->session_id;
                            $student_academics->group_id = (int)$request->group_id;
                            $student_academics->std_roll = $row[2];

                            $id = $student->save();
                            $id = DB::getPdo()->lastInsertId();
                            $student_academics->student_id = $id;
                            $guardian_info->student_id = $id;

                            $student_academics->save();
                            $guardian_info->save();
                           $data = new CreateUser;
                            $data->name = $row[3];
                            $data->email = "";
                            $data->mobile_number = $row[6];
                            $data->user_type_id = 3;
                            $data->school_id = Auth::user()->school_id;
                            $data->created_by = Auth::user()->id;
                            $data->password = Hash::make("123456");
                            try {
                                $data->save();
                                session()->flash("success", "User Created successfully.");
                            } catch(\Illuminate\Database\QueryException $e){
                                $errorCode = $e->errorInfo[1];
                                if($errorCode == '1062'){
                                    session()->flash("error", "Duplicate entry of Mobile Number " . $row[6]. " ");
                                }
                            }
                        }
                        else
                        {
                            session()->flash("error", "Excel file not found! Please select a valid .xls file");
                        }

                    }
                }
                $i++;
            }
            session()->flash("success", $i-1 . " students imported successfully done!");
        } else {
            session()->flash("error", "Excel file not found! Please select a valid .xls file");
        }


        return back();
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

    public function checstudent($session_id,$school_id,$class_id,$std_roll)
    {
        $stdid_exist = StudentAcademic::where('session_id', '=', $session_id)
            ->where('school_id','=',$school_id)
            ->where('class_id','=',$class_id)
            ->where('std_roll','=',$std_roll)
            ->first();
        if ($stdid_exist === null) {
            return false;
        }
        else
        {
            return true;
        }
    }
}
