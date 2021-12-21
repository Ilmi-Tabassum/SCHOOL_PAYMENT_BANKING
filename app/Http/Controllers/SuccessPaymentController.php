<?php

namespace App\Http\Controllers;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use Auth;
use PDF;

class SuccessPaymentController extends Controller
{

    public function index($id = null)
    {
        $id = $id;
        print_r($id);
        exit();

        $partial = 0;

        if (count($invoice_no) > 1) {
            $partial = 1;
            $invoice_no = $invoice_no[0];
        } else {
            $invoice_no = $id;
        }
        if ($partial == 1) {
            $trxid = $request->trxid;
            $bank_tx_id = $request->bank_tx_id;
            $amount = $request->amount;
            $Tdue = DB::select(DB::raw("
                    SELECT *
                    FROM invoice
                    WHERE invoice_no= $invoice_no"));
            $totalDue = $Tdue[0]->due;
            $due = $totalDue - $amount;

            if ($due < 0) {
                session()->flash("error", "Please enter amount equal or less than due");


            } else {
                $transactions = new TransactionList;
                $transactions->invoice_no = $invoice_no;
                $transactions->student_id = $stdid;
                $transactions->amount = $amount;
                $transactions->trx_id = $trxid;
                $transactions->bank_trx_id = $bank_tx_id;
                $transactions->trn_date = now();

                $transactions->save();

                if ($due == 0) {
                    $sql = "update invoice set status = 1 where invoice_no = '$invoice_no'";
                    DB::statement($sql);
                } else {
                    $sql = "update invoice set status = 3 where invoice_no = '$invoice_no'";
                    DB::statement($sql);
                }

                $sql = "update invoice set due = $due where invoice_no = '$invoice_no'";
                DB::statement($sql);
                //invmail($invoice_no)
                $school_id = Auth::User()->school_id;

                $data = $students = DB::select(DB::raw("
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
                        WHERE i.invoice_no= $invoice_no AND cw.school_id = $school_id"));
                $pay = $data[0]->payid;
                $feesheeads = DB::select(DB::raw("SELECT DISTINCT f.fees_head_name title,c.amount samount,f.id,c.fees_id
                                                FROM class_wise_fees c
                                                INNER JOIN fees_heads f ON f.id = c.fees_id
                                                INNER JOIN invoice i ON  i.payment_id = c.payment_id
                                                WHERE c.payment_id=$pay"));
                $studentid = $data[0]->studentid;
                $email = DB::select(DB::raw("SELECT DISTINCT email_address
                                                FROM students
                                                WHERE id=$studentid"));
                $email = $email[0]->email_address;

                $data = array('data' => $data, 'feesheeads' => $feesheeads, 'trx_info' => $transactions);
                $pdf = PDF::loadView('backend/invoice/pdf', $data);
                Mail::send('backend/invoice/mail', $data, function ($message) use ($data, $pdf, $email) {
                    $message->to($email, 'shurjoPay')->subject('Tution Fees invoice')->attachData($pdf->output(), "invoice.pdf");
                    $message->from('no-reply@abbankems.com', 'Tution Fees');
                });

                return redirect("invoicer_view/" . $invoice_no);


            }
        }
    }
}
