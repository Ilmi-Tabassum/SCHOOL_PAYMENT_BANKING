<?php

namespace App\Http\Controllers;

use App\Models\officerpanel\CreateUser;
use App\Models\schoolpanel\AssignSection;
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
use App\Http\Controllers\SmsController;
use App\Models\Invoice;
use App\Models\schoolpanel\LateFee;
use App\Models\TransactionList;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use PHPExcel_Cell;
use PHPExcel_IOFactory;


class InvoiceController extends Controller{

    public function create(){

    }
    public function store(Request $request){

    }
    public function show($id){
        $school_id = Auth::User()->school_id;

        $data= $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
           INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id
            WHERE i.invoice_no= $id AND cw.school_id = $school_id"));
$pay=$data[0]->payid;
        $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));

        $school_logo = SchoolInfo::where('id',$school_id)->first()->school_logo;

        if(is_null($school_logo)!=1){

            $school_logo_img_path = '/storage/school_logo/'. $school_logo;
        }
        else{
            $school_logo_img_path = asset('default_school_logo.png');

        }

        return view('backend/invoice/show')->with(['data' => $data, 'feesheeads' => $feesheeads,'path'=>$school_logo_img_path]);
    }
    public function showr($id){
        $school_id = Auth::User()->school_id;

        $data= $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
           INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id

            WHERE i.invoice_no= $id AND cw.school_id = $school_id"));
        $pay=$data[0]->payid;
        $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));
        $school_logo = SchoolInfo::where('id',$school_id)->first()->school_logo;

        if(is_null($school_logo)!=1){

            $school_logo_img_path = '/storage/school_logo/'. $school_logo;
        }
        else{
            $school_logo_img_path = asset('default_school_logo.png');

        }
        return view('backend/invoice/showr')->with(['data' => $data, 'feesheeads' => $feesheeads,'path'=>$school_logo_img_path]);
    }
    public function index(Request $request)
    {
        $id=$request->id;
        $user_school_id = Auth::User()->school_id;
        $data= $students = DB::select(DB::raw("
            SELECT i.invoice_no invoice,i.due due,i.student_id, i.created_at pdate,i.invoice_no,i.total_amount amount,i.status status,
                   i.payment_id payid,i.month pmonth,i.year pyear,cw.class_id,s.student_id stuid,
                   s.id,sa.student_id, cw.school_id,si.school_name school,s.name name, sa.std_roll roll ,c.name class,se.name sec
            FROM invoice as i
            INNER JOIN students as s ON s.id = i.student_id
            INNER JOIN student_academics as sa ON sa.student_id = s.id
           INNER JOIN class_wise_fees as cw ON cw.payment_id = i.payment_id
            INNER JOIN class_infos as c ON c.id = cw.class_id
            INNER JOIN school_infos as si ON si.id = cw.school_id
            INNER JOIN sections  as se  ON sa.section_id = se.id

            WHERE i.invoice_no=$id AND cw.school_id=$user_school_id"));
        $pay=$data[0]->payid;
        $feesheeads=DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
        FROM class_wise_fees c
        INNER JOIN fees_heads f ON f.id = c.fees_id
        INNER JOIN invoice i ON  i.payment_id = c.payment_id
        WHERE c.payment_id=$pay"));

        $user = Auth::user()->school_id;
        $school_logo = SchoolInfo::where('id',$user)->first()->school_logo;

        if(is_null($school_logo)!=1){

            $school_logo_img_path = '/storage/school_logo/'. $school_logo;
        }
        else{
            $school_logo_img_path = ('default_school_logo.png');

        }
        if($request->has('download'))
        {
            $pdf = \PDF::loadView('backend/invoice/pdf',compact('user','data','feesheeads','school_logo_img_path'))->setPaper('a4', 'portrait');
            return $pdf->download('pdfview.pdf');
        }

        return view('backend/invoice/pdf',compact('user','data','school_logo_img_path'));
    }
    public function fetchInvoice($id){
        $data = DB::select(DB::raw("
            SELECT *
            FROM invoice
            WHERE invoice_no= $id"));
        return $data;
    }
    public function bulkInvoice(Request $request)
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
                /*    echo $row[1] ."<br>";
                    exit();*/

              /*     echo gettype($inv_no);
                    echo strlen($inv_no);
                    echo strlen(trim($inv_no));
                    //print_r($inv_no);
                    exit();*/
                    $inv_no="0".$row[1]."";
                    //print_r($inv_no);
                  $invoice = Invoice::where('invoice_no','=',$inv_no)->get();
                  $status=$invoice[0]->status;
                  $due=$invoice[0]->due;
                  $inv=$invoice[0]->invoice_no;
                  if($status==1)
                  {
                  }
                  elseif ($status==3)
                  {
                      $due_new=$due-$row[2];
                      if($due_new==0)
                      {
                          $sql = "update invoice set status = 1 where invoice_no = '$inv'";
                          DB::statement($sql);
                          $sql = "update invoice set due = '$due_new' where invoice_no = '$inv'";
                          DB::statement($sql);
                      }else{
                          $sql = "update invoice set due = '$due_new' where invoice_no = '$inv'";
                          DB::statement($sql);
                      }
                  }elseif ($status==0)
                  {
                      $due_new=$due-$row[2];
                      if($due_new==0)
                      {
                  /* $invoice->status=1;
                          $invoice->due=$due_new;*/
                          $sql = "update invoice set status = 1 where invoice_no = '$inv'";
                          DB::statement($sql);
                          $sql = "update invoice set due = '$due_new' where invoice_no = '$inv'";
                          DB::statement($sql);

                      }elseif ($due_new>0)
                      {
                          $sql = "update invoice set status = 3 where invoice_no = '$inv'";
                          DB::statement($sql);
                          $sql = "update invoice set due = '$due_new' where invoice_no = '$inv'";
                          DB::statement($sql);
                      } else{
                          session()->flash("error", "Invalid Invoice no".$row[0]);
                          break;                      }
                  }else{
                      session()->flash("error", "Invalid Invoice no".$row[0]);
                      break;
                  }


                 /*   $sql = "update invoice set status = 4 where invoice_no = '$inv_no'";
                    DB::statement($sql);*/

                }
                $i++;
            }
            session()->flash("success", $i-1 . " students imported successfully done!");
        } else {
            session()->flash("error", "Excel file not found! Please select a valid .xls file");
        }


        return back();
    }
    public function inv_bulk()
    {
        return view('backend/invoice_upload');
    }

    public function edit($id){


    }
    public function update(Request $request){

    }
    public function destroy($id){

    }


}
