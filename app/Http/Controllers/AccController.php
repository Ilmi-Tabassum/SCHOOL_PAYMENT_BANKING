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


class AccController extends Controller{

    public function bulkAcc(Request $request)
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
               // echo $row[0];
                if ($i > 0) {
                    $student = StudentGuardianInfo::where('student_id', '=', $row[0])->get();
                    print_r($student);
                    exit();


                   // echo $row[0] ."<br>";
                    /*    echo $row[1] ."<br>";
                        exit();*/

                    /*     echo gettype($inv_no);
                          echo strlen($inv_no);
                          echo strlen(trim($inv_no));
                          //print_r($inv_no);
                          exit();*/
/*                    $inv_no="0".$row[1]."";
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
                    /*     $sql = "update invoice set status = 1 where invoice_no = '$inv'";
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
               }*/


                    /*   $sql = "update invoice set status = 4 where invoice_no = '$inv_no'";
                       DB::statement($sql);*/

                }
                $i++;
            }
            session()->flash("success", $i-1 . " students imported successfully done!");
        } else {
            session()->flash("error", "Excel file not found! Please select a valid .xls file");
        }

exit();
        return back();
    }
    public function acc_bulk()
    {
        return view('backend/acc_upload');
    }




}
