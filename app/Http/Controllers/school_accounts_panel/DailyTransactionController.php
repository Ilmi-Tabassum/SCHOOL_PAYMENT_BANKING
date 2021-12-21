<?php

namespace App\Http\Controllers\school_accounts_panel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class DailyTransactionController extends Controller
{

    public function index()
    {
        $date = Carbon::today();
        $date=$date->toDateString();
        /*print($date->toDateString());*/
        $users = DB::select(DB::raw("SELECT id,name
                        FROM users"));
        $transaction_lists= DB::select(DB::raw("SELECT student_id,invoice_no,received_amount amount
                        FROM fees_collections
                        WHERE payment_date='$date'"));

        return view('school_accounts_panel/daily_transaction/daily_transaction')->with(['transaction_lists' => $transaction_lists,'users' => $users]);
    }



    public function search_trxn(Request $request)
    {
        $date = $request->date;
        $user=$request->user;
        if (!empty($date) && !empty($user)) {
            $users = DB::select(DB::raw("SELECT id,name
                        FROM users"));

            $transaction_lists= DB::select(DB::raw("SELECT student_id,invoice_no,received_amount amount
                        FROM fees_collections
                        WHERE payment_date='$date' AND created_by=$user"));
            $size = count($transaction_lists);
            if ($size > 0) {
                session()->flash("success", "Your search result are");
                return view('school_accounts_panel/daily_transaction/daily_transaction')->with(['transaction_lists' => $transaction_lists, 'users' => $users]);
            } else {
                session()->flash("error", "No Item(s) found");
                return back();
            }

        }
        else{
            session()->flash("error", "No Item found");
            return back();
        }

    }






}
