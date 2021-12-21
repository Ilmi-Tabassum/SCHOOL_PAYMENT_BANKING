<?php

namespace App\Http\Controllers\schoolpanel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Models\Invoice;
use App\Models\SchoolInfo;
use App\Models\schoolpanel\AssignSection;
use App\Models\schoolpanel\LateFee;
use App\Models\TransactionList;
use Illuminate\Http\Request;
use DB;
use Auth;
use Mail;
use PDF;

use App\Models\officerpanel\CreateUser;
use Illuminate\Support\Facades\Hash;


class SchoolPanelHome extends Controller
{

    public function goInvoicePage()
    {
        $school_id = Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $school_id
            ORDER BY class_infos.name ASC"));

       $invoices = DB::table('invoice')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
            ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
            ->where('invoice.status','!=',2)
            ->where('invoice.school_id',$school_id)
            ->paginate(50);
       // dd($invoices);

        return view('schoolpanel/viewinvoices')->with(['invoices'=>$invoices,'classes'=>$classes]);

    }


    public function  userList()
    {
        //Here 10 is School Account's Panel
        //You can add as many as
        //just use comman in WhereIn array  ->whereIn('id', [10,2])

        $user_id =Auth::user()->id;

        $userType = DB::table('user_types')
                    ->select('id','name')
                    ->whereIn('id', [10,3])
                    ->get();

        $schools = DB::table('school_infos')
                    ->select('id','school_name')
                    ->get();

        $users_info = DB::table('users')
                    ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                    ->select('users.id','users.name','users.email','users.mobile_number','users.user_type_id','user_types.name as user_type_name')
                    ->where('users.created_by',$user_id)
                    ->paginate(30);

        return view('schoolpanel/user_manage/user_index')->with(['userType'=>$userType,'schools'=>$schools,'users_info'=>$users_info]);
    }



     public function storeCreateUserInfo(Request $request)
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









    public function setupFee()
    {

        return view('schoolpanel/late_fee_setup');

    }
    public function setupFeeStore(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $day=$request->date_number;
        $amount=$request->Amount;
        if($day && $amount)
        {           $object = new LateFee;
                    $object->school_id=$school_id;
                    $object->day=$day;
                    $object->amount=$amount;
                    try {
                        $object->save();
                        $return='true';
                        session()->flash("success", "Sections assigned successfully!");
                    } catch(\Illuminate\Database\QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        if ($errorCode == '1062') {
                            session()->flash("error", "We are sorry.Sections is not assigned for duplicate entry.");
                        }
                        $return="false";
                    }
        }else{
            session()->flash("error", "Please try again");
            $return="false";
        }
        return back();
    }
    public function goInvoiceUnverPage()
    {
        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $school_id = Auth::user()->school_id;
        $invoices = DB::table('invoice')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
            ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
            ->where('invoice.school_id',$school_id)
            ->where('invoice.status',2)
            ->paginate(50);
        return view('schoolpanel/viewinvoices_unver')->with(['invoices'=>$invoices,'classes'=>$classes]);

    }


    public function insertSMSlogData($std_id,$sms_body,$status,$sms_send_id)
    {
        $school_id = Auth::user()->school_id;
        $user_id = Auth::user()->id;
        $title = "";
        DB::table('sms_log')->insert([
            'school_id' => $school_id,
            'user_id' => $user_id,
            'std_id'=>$std_id,
            'title'=>$title,
            'sms_body'=>$sms_body,
            'status'=>$status,
            'sms_send_id'=>$sms_send_id
        ]);
    }

    public function approveMultiple(Request $request){
        $invoices=$request->invoice;

        //dd($invoices);
        $sms= new SmsController;
            foreach ($invoices as $inv) {
                $object = Invoice::find($inv);

                $object->status = 0;
                $object->save();
                $month="";

                $data = $object->month;
                if ($data=='01') {
                    $month="January";
                }
                else if($data=='02'){

                    $month="February";
                }
                 else if($data=='03'){

                    $month="March";
                }
                 else if($data=='04'){

                    $month="April";
                }
                 else if($data=='05'){

                    $month="May";
                }
                 else if($data=='06'){

                    $month="June";
                }
                 else if($data=='07'){

                    $month="July";
                }
                 else if($data=='08'){

                    $month="August";
                }
                 else if($data=='09'){

                    $month="September";
                }
                 else if($data=='10'){

                    $month="October";
                }
                 else if($data=='11'){

                    $month="November";
                }
                else if($data=='12'){

                    $month="December";
                }

                // $sms->sendSms($cell,$body,$mask);
                if($object->is_sms==1)
                {
                    $student_id = $object->student_id;
                $guardian_info = DB::select(DB::raw("SELECT guardian_contact_no FROM student_guardian_infos WHERE student_id=$student_id"));

                  $mobile_number = "88".$guardian_info[0]->guardian_contact_no;



                    $body="Please pay school tution fee BTD ".$object->total_amount." for the month of ".$month.". Your Invoice No ".$object->invoice_no;

                    $sms_id = $sms->sendSms($mobile_number,$body,'ABBANK');

                    $student_id=$object->student_id;
                    $this->insertSMSlogData($student_id,$body,0,$sms_id);
                   // dd($sms_id);

                }
                if($object->is_email==1)
                {
                    $data= $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id as studentid, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
           INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id
            WHERE i.id= $inv"));
                    $pay=$data[0]->payid;
                    $studentid=$data[0]->studentid;
                    $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));
                    $email=DB::select(DB::raw("SELECT DISTINCT email_address
        FROM students
        WHERE id=$studentid"));
                    $email=$email[0]->email_address;
                    $data=array('data'=>$data,'feesheeads' => $feesheeads,'trx_info'=>'');
                    $pdf = PDF::loadView('backend/invoice/pdf', $data);
                    Mail::send('backend/invoice/mail', $data, function($message)use($data, $pdf,$email) {
                        $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                        $message->from('no-reply@abbankems.com','Tution Fees');
                    });

                }
                //SmsController
            }
        session()->flash("success", "approved successfully!");
        return back();
    }

    public function approved($id){
        $sms= new SmsController;

        $object = Invoice::find($id);
        $object->status = 0;
        $object->save();
        $month="";

        $data = $object->month;
        if ($data=='01') {
            $month="January";
        }
        else if($data=='02'){

            $month="February";
        }
        else if($data=='03'){

            $month="March";
        }
        else if($data=='04'){

            $month="April";
        }
        else if($data=='05'){

            $month="May";
        }
        else if($data=='06'){

            $month="June";
        }
        else if($data=='07'){

            $month="July";
        }
        else if($data=='08'){

            $month="August";
        }
        else if($data=='09'){

            $month="September";
        }
        else if($data=='10'){

            $month="October";
        }
        else if($data=='11'){

            $month="November";
        }
        else if($data=='12'){

            $month="December";
        }

        // $sms->sendSms($cell,$body,$mask);
        if($object->is_sms==1)
        {
            $student_id = $object->student_id;
            $guardian_info = DB::select(DB::raw("SELECT guardian_contact_no FROM student_guardian_infos WHERE student_id=$student_id"));

            $mobile_number = "88".$guardian_info[0]->guardian_contact_no;



            $body="Please pay school tution fee BTD ".$object->total_amount." for the month of ".$month.". Your Invoice No ".$object->invoice_no;



            $sms_id = $sms->sendSms($mobile_number,$body,'ABBANK');

            $student_id=$object->student_id;
            $this->insertSMSlogData($student_id,$body,0,$sms_id);

        }

        //Email
        if($object->is_email==1)
        {
            $data= $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id as studentid, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
            INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id
            WHERE i.id= $id"));
        $pay=$data[0]->payid;
        $studentid=$data[0]->studentid;
        $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));
        $email=DB::select(DB::raw("SELECT DISTINCT email_address
        FROM students
        WHERE id=$studentid"));
        $email=$email[0]->email_address;
        $data=array('data'=>$data,'feesheeads' => $feesheeads,'trx_info'=>'');
            $pdf = PDF::loadView('backend/invoice/pdf', $data);
            Mail::send('backend/invoice/mail', $data, function($message)use($data, $pdf,$email) {
                $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                $message->from('no-reply@abbankems.com','Tution Fees');
            });

        }

        session()->flash("success", "$object->invoice_no is approved successfully!");
        return back();
    }

    public function store_update(Request $request)
    {
        $id=$request->hidden_invoice_id;
        $status=$request->pay_status;
        $type=$request->pay_type;
        $trxn_id=$request->trxn;
        if($status ==1)
        {
            TransactionList::where('invoice_no', $id)
                ->update(['status' => $status]);
           TransactionList::where('invoice_no', $id)
                ->update(['trx_id' => $trxn_id]);
            Invoice::where('invoice_no', $id)
                ->update(['status' => $status]);
        }else
        {
            $data = TransactionList::where('invoice_no', $id)
                ->update(['status' => $status]);
            $data2 = Invoice::where('invoice_no', $id)
                ->update(['status' => $status]);
        }

        session()->flash("success", "Payment Status has been updated successfully");
        return back();
    }

    public function editInvoiceDetails($id)
    {
        $data = DB::select(DB::raw("SELECT * FROM invoice WHERE id=$id"));
        return $data;
    }
    public function SearchInvoicee(Request $request)
    {
        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $school_id = Auth::user()->school_id;
        $month_number = $request->month_number;
        $year_number = $request->year_number;
        $class_no=$request->class_id;

            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.year',$year_number)
                ->where('invoice.class_id',$class_no)
                ->paginate(50);




        return view('schoolpanel/viewinvoices_unver')->with(['invoices'=>$invoices,'classes'=>$classes]);
    }

    public function SearchInvoice_unver(Request $request)
    {
        $scid=Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $scid
            ORDER BY class_infos.name ASC"));
        $school_id = Auth::user()->school_id;
        $month_number = $request->month_number;
        $year_number = $request->year_number;
        $class_no=$request->class_id;

        $invoices = DB::table('invoice')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
            ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
            ->where('invoice.school_id',$school_id)
            ->where('invoice.month',$month_number)
            ->where('invoice.year',$year_number)
            ->where('invoice.class_id',$class_no)
            ->where('invoice.status',2)
            ->paginate(50);




        return view('schoolpanel/viewinvoices_unver')->with(['invoices'=>$invoices,'classes'=>$classes]);
    }

    public function SearchInvoiceMonthWise(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $classes =DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM assign_classes
            JOIN class_infos on assign_classes.class_id=class_infos.id
            WHERE assign_classes.school_id = $school_id
            ORDER BY class_infos.name ASC"));

        $month_number = $request->month_number;
        $year_number = $request->year_number;
        $class_no=$request->class_id;
        $status=$request->status;

        if(!empty($month_number) && !empty($year_number) && !empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','!=',2)
                ->paginate(50);

        }
        elseif (!empty($month_number) && !empty($year_number) && empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif (!empty($month_number) && !empty($class_no) && empty($year_number) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif (empty($month_number) && !empty($class_no) && !empty($year_number) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.year',$year_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif (empty($class_no) && empty($month_number) && !empty($year_number) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif (!empty($month_number) && empty($year_number) && empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif (empty($month_number) && empty($year_number) && !empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','!=',2)
                ->paginate(50);
        }
        elseif(!empty($month_number) && !empty($year_number) && !empty($class_no) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','=',$status)
                ->paginate(50);

        }
        elseif (!empty($month_number) && !empty($year_number) && empty($class_no) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (!empty($month_number) && !empty($class_no) && empty($year_number) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (empty($month_number) && !empty($class_no) && !empty($year_number) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.year',$year_number)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (empty($class_no) && empty($month_number) && !empty($year_number) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (!empty($month_number) && empty($year_number) && empty($class_no) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (empty($month_number) && empty($year_number) && !empty($class_no) && !empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        elseif (empty($class_no) && empty($month_number) && !empty($year_number) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.year',$year_number)
                ->where('invoice.status','!=',$status)
                ->paginate(50);
        }
        elseif (!empty($month_number) && empty($year_number) && empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.month',$month_number)
                ->where('invoice.status','!=',$status)
                ->paginate(50);
        }
        elseif (empty($month_number) && empty($year_number) && !empty($class_no) && empty($status))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.class_id',$class_no)
                ->where('invoice.status','!=',$status)
                ->paginate(50);
        }
        elseif (!empty($status) && empty($month_number) && empty($year_number) && empty($class_no))
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }
        else
        {
            $invoices = DB::table('invoice')
                ->join('students', 'invoice.student_id', '=', 'students.id')
                ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                ->where('invoice.school_id',$school_id)
                ->where('invoice.status','=',$status)
                ->paginate(50);
        }


        return view('schoolpanel/viewinvoices')->with(['invoices'=>$invoices,'classes'=>$classes]);
    }
    public function invoiceEdit()
    {
        return view('schoolpanel/viewinvoices_edit');

    }

    public function goWaiverPage()
    {
        $school_id = Auth::user()->school_id;
        $user_id = Auth::user()->id;
        $hasData = 1;
        $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();

        $waivers = DB::table('fees_waivers')
                ->join('students', 'fees_waivers.student_id', '=', 'students.id')
                ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
                ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
                ->select('fees_waivers.*', 'students.student_id as full_student_id','class_infos.name as class_name','fees_heads.fees_head_name')
                ->where('fees_waivers.created_by',$user_id)
                ->paginate(50);
        // dd($waivers);
        return view('schoolpanel/view-waivers')->with(['waivers'=>$waivers,'hasData'=>$hasData,'classes'=>$classes]);
    }


    public function searchWaiver(Request $request)
    {
        $student_id = $request->student_id;
        $class_id = $request->class_id;

        /*If student_id and class_id is are both empty*/
        if (empty($student_id) && empty($class_id)) {
            $school_id = Auth::user()->school_id;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();

            $hasData = 0;
             return view('schoolpanel/view-waivers')->with(['hasData'=>$hasData,'classes'=>$classes]);

        }


         /*If class_id is selected but student_id is not selected*/
         if (!empty($class_id) && empty($student_id)) {
            $school_id = Auth::user()->school_id;
            $user_id = Auth::user()->id;
            $hasData = 1;

            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();

            $waivers = DB::table('fees_waivers')
                        ->join('students', 'fees_waivers.student_id', '=', 'students.id')
                        ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
                        ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
                        ->select('fees_waivers.*', 'students.student_id as full_student_id','class_infos.name as class_name','fees_heads.fees_head_name')
                        ->where('fees_waivers.class_id','=',$class_id)
                        ->paginate(50);
             return view('schoolpanel/view-waivers')->with(['waivers'=>$waivers,'hasData'=>$hasData,'classes'=>$classes]);
        }

        /*This case is rarely appeared*/
        /*If student_id is selected but class_id is not selected*/
        if (!empty($student_id) && empty($class_id)) {
            $school_id = Auth::user()->school_id;
            $user_id = Auth::user()->id;
            $hasData = 1;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();
            $waivers = DB::table('fees_waivers')
                        ->join('students', 'fees_waivers.student_id', '=', 'students.id')
                        ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
                        ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
                        ->select('fees_waivers.*', 'students.student_id as full_student_id','class_infos.name as class_name','fees_heads.fees_head_name')
                        ->where('fees_waivers.student_id','=',$student_id)
                        ->paginate(50);
             return view('schoolpanel/view-waivers')->with(['waivers'=>$waivers,'hasData'=>$hasData,'classes'=>$classes]);
        }


         /*If class_id is and student_id both selected*/
         if (!empty($class_id) && !empty($student_id)) {
            $school_id = Auth::user()->school_id;
            $user_id = Auth::user()->id;
            $hasData = 1;

            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();

            $waivers = DB::table('fees_waivers')
                        ->join('students', 'fees_waivers.student_id', '=', 'students.id')
                        ->join('class_infos', 'fees_waivers.class_id', '=', 'class_infos.id')
                        ->join('fees_heads', 'fees_waivers.fees_id', '=', 'fees_heads.id')
                        ->select('fees_waivers.*', 'students.student_id as full_student_id','class_infos.name as class_name','fees_heads.fees_head_name')
                        ->where('fees_waivers.student_id','=',$student_id)
                        ->where('fees_waivers.class_id','=',$class_id)
                        ->paginate(50);
             return view('schoolpanel/view-waivers')->with(['waivers'=>$waivers,'hasData'=>$hasData,'classes'=>$classes]);
        }

    }



    public function getClassSchoolWiseStudents($class_id)
    {
       if (!empty($class_id)) {
           $school_id = Auth::user()->school_id;
           $studentData = DB::table("student_academics")
                               ->join('students', 'student_academics.student_id', '=', 'students.id')
                               ->select('student_academics.id','students.student_id as s_id_full')
                               ->where('student_academics.school_id',$school_id)
                               ->where('student_academics.class_id',$class_id)
                               ->get();
            $student_no = count($studentData);
            if ($student_no> 0) {
                return response()->json(['hasStudent'=> '1','students'=>$studentData]);
            }

            else{
                return response()->json(['hasStudent'=> '0']);
            }

       }

    }


    public function goDuesReport()
    {
        $hasData=1;
        $school_id = Auth::user()->school_id;
        $status = 0;

        $invoices = DB::table('invoice')
            ->join('students', 'invoice.student_id', '=', 'students.id')
            ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
            ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
            ->where('invoice.school_id',$school_id)
            ->where('invoice.status',$status)
            ->paginate(50);




        $classes = DB::select(DB::raw("
            SELECT class_infos.id id,class_infos.name name
            FROM class_infos
            ORDER BY class_infos.name ASC"));
        return view('schoolpanel/dues-report-sp')->with(['hasData'=>$hasData,'invoices'=>$invoices,'classes'=>$classes]);
    }



    public function SearchDuesReport(Request $request)
    {
        $class_id = $request->class_id;
        $month_number = $request->month_number;
        $school_id = Auth::user()->school_id;
        $status = 0;

        /*If class_id and month number are both empty*/
        if (empty($class_id) && empty($month_number)) {
            $hasData = 0;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();
            return view('schoolpanel/dues-report-sp')->with(['hasData'=>$hasData,'classes'=>$classes]);
        }


        /*If month number is selected but class_id is not selected*/
        if (!empty($month_number) && empty($class_id)) {
            $hasData = 1;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();
            $invoices = DB::table('invoice')
                    ->join('students', 'invoice.student_id', '=', 'students.id')
                    ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                    ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                    ->where('invoice.status',$status)
                    ->where('invoice.school_id',$school_id)
                    ->where('invoice.month',$month_number)
                    ->paginate(50);

            return view('schoolpanel/dues-report-sp')->with(['hasData'=>$hasData,'classes'=>$classes,'invoices'=>$invoices]);
        }

         /*If class_id is selected but month number is not selected*/
        if (!empty($class_id) && empty($month_number)) {
            $hasData = 1;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();
            $invoices = DB::table('invoice')
                    ->join('students', 'invoice.student_id', '=', 'students.id')
                     ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                     ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                    ->where('invoice.status',$status)
                    ->where('invoice.school_id',$school_id)
                    ->where('invoice.class_id',$class_id)
                    ->paginate(50);
            return view('schoolpanel/dues-report-sp')->with(['hasData'=>$hasData,'classes'=>$classes,'invoices'=>$invoices]);
        }


        /*If class_id and  month number are both selected*/
        if (!empty($class_id) && !empty($month_number)) {
            $hasData = 1;
            $classes = DB::table("class_infos")->select('id','name')->orderBy('name', 'asc')->get();
            $invoices = DB::table('invoice')
                    ->join('students', 'invoice.student_id', '=', 'students.id')
                    ->join('class_infos', 'invoice.class_id', '=', 'class_infos.id')
                    ->select('invoice.*', 'students.student_id as full_student_id','class_infos.name as class_name')
                    ->where('invoice.status',$status)
                    ->where('invoice.school_id',$school_id)
                    ->where('invoice.class_id',$class_id)
                    ->where('invoice.month',$month_number)
                    ->paginate(50);
            return view('schoolpanel/dues-report-sp')->with(['hasData'=>$hasData,'classes'=>$classes,'invoices'=>$invoices]);
        }

    }










}
