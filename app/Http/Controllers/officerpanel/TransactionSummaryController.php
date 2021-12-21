<?php

namespace App\Http\Controllers\officerpanel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TransactionSummaryController extends Controller
{

    public function index()
    {
        $transaction_lists = DB::select(DB::raw("SELECT fees_collections.school_id,school_infos.school_name schoolname ,fees_collections.payment_date,fees_collections.received_amount
                        FROM fees_collections
                        INNER JOIN school_infos ON school_infos.id = fees_collections.school_id"));
        return view('officerpanel/transaction_summary')->with(['transaction_lists' => $transaction_lists]);
    }



    public function search_trxn(Request $request)
    {
        $date_range = $request->sdate;

        /*If search date range  only*/
        if (!empty($date_range)) {
            $part = explode("to", $date_range);
            $start_date = trim($part[0], " ");
            $end_date = trim($part[1], " ");
            $transaction_lists = DB::select(DB::raw("SELECT fees_collections.school_id,school_infos.school_name schoolname ,fees_collections.payment_date,fees_collections.received_amount
                        FROM fees_collections
                        INNER JOIN school_infos ON school_infos.id = fees_collections.school_id

                        WHERE fees_collections.payment_date BETWEEN '$start_date' AND '$end_date'"));
            $size = count($transaction_lists);
            if ($size > 0) {
                session()->flash("success", "Your search result are");
                return view('officerpanel/transaction_summary')->with(['transaction_lists' => $transaction_lists]);
            } else {
                session()->flash("error", "No Item(s) found");
                return back();
            }

        }

    }






}
