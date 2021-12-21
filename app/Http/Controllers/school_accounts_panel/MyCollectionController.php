<?php


namespace App\Http\Controllers\school_accounts_panel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class MyCollectionController extends Controller
{

    public function index()
    {
        $user_info = Auth::user();
        $user_id = $user_info->id;

        $transaction_lists = DB::select(DB::raw("SELECT payment_date date ,SUM(received_amount) as amount
                        FROM fees_collections
                        WHERE created_by = $user_id
                        GROUP BY payment_date"));
        $total_amount = DB::select(DB::raw("SELECT SUM(received_amount) as amount
                        FROM fees_collections
                        WHERE created_by = $user_id"));
        return view('school_accounts_panel/my_collection/my_collection')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);
    }


    public function search_trxn(Request $request)
    {
        $user_info = Auth::user();
        $user_id = $user_info->id;
        $date_range = $request->sdate;

        if (!empty($date_range)) {
            $part = explode("to", $date_range);
            $start_date = trim($part[0], " ");
            $end_date = trim($part[1], " ");
            $transaction_lists = DB::select(DB::raw("SELECT payment_date date,SUM(received_amount) as amount
                        FROM fees_collections
                        WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND created_by = $user_id
                        GROUP BY payment_date"));
            $total_amount = DB::select(DB::raw("SELECT SUM(fees_collections.received_amount) as amount
                        FROM fees_collections
                        WHERE payment_date BETWEEN '$start_date' AND '$end_date' AND created_by = $user_id "));
            $size = count($transaction_lists);
            if ($size > 0) {
                session()->flash("success", "Your search result are");
                return view('school_accounts_panel/my_collection/my_collection')->with(['transaction_lists' => $transaction_lists, 'total_amount' => $total_amount]);
            } else {
                session()->flash("error", "No Item(s) found");
                return back();
            }

        }

    }


}
