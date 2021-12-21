<?php

namespace App\Http\Controllers\officerpanel;

use App\Models\TransactionList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TransactionListController extends Controller
{

    public function index()
    {
        $transaction_lists = DB::table('transaction_lists')->get();
        return view('officerpanel/transaction_list')->with(['transaction_lists' => $transaction_lists]);
    }

    public function show_details($id){
        $trn_details = TransactionList::find($id);
        return response($trn_details);
    }


    public function search_trxn(Request $request)
    {
        $student_id = $request->student_id;
        $school_name = $request->school_name;
        $date_range = $request->sdate;

        /*Combined search with student_id and date_range*/
        if (!empty($student_id) && !empty($date_range)) {
            $part= explode("to", $date_range);
             $start_date = trim($part[0]," ");
             $end_date = trim($part[1]," ");
             $transaction_lists = DB::select( DB::raw("SELECT * FROM transaction_lists WHERE trn_date BETWEEN '$start_date' AND '$end_date' AND student_id=$student_id"));
             $size = count($transaction_lists);

             if($size>0){
                session()->flash("success", "Your search result are");
                return view('officerpanel/transaction_list')->with(['transaction_lists' => $transaction_lists]);
             }

            else{
                 /*If search student ID only*/
                if (!empty($student_id)) {
                    $transaction_lists = DB::select( DB::raw("SELECT * FROM transaction_lists WHERE student_id = $student_id"));
                    $size = count($transaction_lists);
                    if($size>0){
                        return view('officerpanel/transaction_list')->with(['transaction_lists' => $transaction_lists]);
                    }
                    else{
                        session()->flash("error", "No Item(s) found");
                        return back();
                     }
                }
            }

        }


        /*If search date range  only*/
        if (!empty($date_range)) {
             $part= explode("to", $date_range);
             $start_date = trim($part[0]," ");
             $end_date = trim($part[1]," ");
             $transaction_lists = DB::select( DB::raw("SELECT * FROM transaction_lists WHERE trn_date BETWEEN '$start_date' AND '$end_date'"));
             $size = count($transaction_lists);
             if($size>0){
                session()->flash("success", "Your search result are");
                return view('officerpanel/transaction_list')->with(['transaction_lists' => $transaction_lists]);
             }
             else{
                session()->flash("error", "No Item(s) found");
                return back();
             }
        }

        // /*If search student ID only*/
        // if (!empty($student_id)) {
        //     $transaction_lists = DB::select( DB::raw("SELECT * FROM transaction_lists WHERE student_id = $student_id"));
        //     $size = count($transaction_lists);
        //     if($size>0){
        //         return view('officerpanel/transaction_list')->with(['transaction_lists' => $transaction_lists]);
        //     }
        //     else{
        //         session()->flash("error", "No Item(s) found");
        //         return back();
        //      }

        // }






    }






}
