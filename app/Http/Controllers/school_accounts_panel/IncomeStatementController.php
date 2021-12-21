<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class IncomeStatementController extends Controller
{

    public function index()
    {
        $school_id=Auth::user()->school_id;
        if(!empty($school_id))
        {
            $transaction_lists = DB::select(DB::raw("SELECT transaction_lists.trn_date as payment_date,SUM(invoice.total_amount) as amount
                        FROM invoice
                         INNER JOIN transaction_lists ON transaction_lists.invoice_no = invoice.invoice_no
                        WHERE invoice.school_id=$school_id
                        GROUP BY transaction_lists.trn_date"));
            $total_amount = DB::select(DB::raw("SELECT SUM(invoice.total_amount) as amount
                        FROM invoice
                        WHERE invoice.status=1 AND school_id=$school_id"));
        }else{
            $transaction_lists = DB::select(DB::raw("SELECT transaction_lists.trn_date as payment_date,SUM(invoice.total_amount) as amount
                        FROM invoice
                         INNER JOIN transaction_lists ON transaction_lists.invoice_no = invoice.invoice_no
                        GROUP BY transaction_lists.trn_date"));
            $total_amount = DB::select(DB::raw("SELECT SUM(invoice.total_amount) as amount
                        FROM invoice
                        WHERE invoice.status=1"));
        }


        return view('school_accounts_panel/income_statement/income_statement')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);
    }

    public function processMysqlDate($date){
        if($date > 0){
            $createdAt = explode('/', $date);
            return $createdAt[2].'-'.$createdAt[0].'-'.$createdAt[1];
        }else{
            return "";
        }
    }

    public function search_trxn(Request $request)
    {
        $start_date = ($request->start_date);
        $end_date = ($request->end_date);


        if (!empty($start_date) && !empty($end_date)) {

            $transaction_lists = DB::select(DB::raw("SELECT fees_collections.school_id,school_infos.school_name schoolname ,fees_collections.payment_date,SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                        INNER JOIN school_infos ON school_infos.id = fees_collections.school_id
                        WHERE fees_collections.payment_date BETWEEN '$start_date' AND '$end_date'
                        GROUP BY fees_collections.school_id,school_infos.school_name,fees_collections.payment_date"));
            $total_amount = DB::select(DB::raw("SELECT SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                        INNER JOIN school_infos ON school_infos.id = fees_collections.school_id
                        WHERE fees_collections.payment_date BETWEEN '$start_date' AND '$end_date'"));

            if (!empty($transaction_lists)) {
                session()->flash("success", "Your search result are");
                return view('school_accounts_panel/income_statement/income_statement')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);

            } else {
                $transaction_lists="";
                session()->flash("error", "No Item(s) found");
                return view('school_accounts_panel/income_statement/income_statement')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);

            }

        }

    }






}
