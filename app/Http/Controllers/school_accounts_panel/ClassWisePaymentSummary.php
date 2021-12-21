<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ClassWisePaymentSummary extends Controller
{

    public function index()
    {
        $transaction_lists = DB::select(DB::raw("SELECT fees_collections.class_id,class_infos.name classname ,MAX(fees_collections.payment_date) as date,SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                        INNER JOIN class_infos ON class_infos.id = fees_collections.class_id
                        GROUP BY fees_collections.class_id,class_infos.name"));
        $total_amount = DB::select(DB::raw("SELECT SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                       INNER JOIN class_infos ON class_infos.id = fees_collections.class_id
"));
        return view('school_accounts_panel/class_wise_payment_summary/class_wise_payment_summary')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);
    }



    public function search_trxn(Request $request)
    {
        $date_range = $request->sdate;

        if (!empty($date_range)) {
            $part = explode("to", $date_range);
            $start_date = trim($part[0], " ");
            $end_date = trim($part[1], " ");
            $transaction_lists = DB::select(DB::raw("SELECT fees_collections.class_id,class_infos.name classname ,MAX(fees_collections.payment_date) as date,SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                        INNER JOIN class_infos ON class_infos.id = fees_collections.class_id
                        WHERE fees_collections.payment_date BETWEEN '$start_date' AND '$end_date'
                        GROUP BY fees_collections.class_id,class_infos.name"));
            $total_amount = DB::select(DB::raw("SELECT SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                       INNER JOIN class_infos ON class_infos.id = fees_collections.class_id
                        WHERE fees_collections.payment_date BETWEEN '$start_date' AND '$end_date'"));
            $size = count($transaction_lists);
            if ($size > 0) {
                session()->flash("success", "Your search result are");
                return view('school_accounts_panel/class_wise_payment_summary/class_wise_payment_summary')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);
            } else {
                session()->flash("error", "No Item(s) found");
                return back();
            }

        }

    }






}
