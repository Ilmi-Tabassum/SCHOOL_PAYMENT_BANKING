<?php

namespace App\Http\Controllers\schoolpanel;


use App\Http\Controllers\Controller;
use App\Models\TransactionList;
use Illuminate\Http\Request;
use DB;

class StudentTransactionListController extends Controller
{

    public function index()
    {
        $transaction_lists = DB::table('transaction_lists')->get();
        return view('schoolpanel/report/student_transaction_list')->with(['transaction_lists' => $transaction_lists]);
    }

    public function show_details($id){
        $trn_details = TransactionList::find($id);
        return response($trn_details);
    }

    public function edit_trxn($id)
    {
        $data = TransactionList::find($id);
        return $data;
    }

    public function update_trxn(Request $request)
    {
        $id = $request->post("item_id_trxn");
        $data = TransactionList::find($id);
        $data->student_id = $request->student_id;
        $data->amount = $request->amount;
        $data->order_id = $request->order_id;
        $data->trx_id = $request->trx_id;
        $data->bank_trx_id = $request->bank_trx_id;
        $data->return_code = $request->return_code;
        $data->status = $request->status;
        $data->method = $request->method;
        $data->trn_date = $request->trn_date;
        $data->invoice_no = $request->invoice_no;
        try {
            $data->save();
            session()->flash("success", "Item is updated successfully!");
        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                session()->flash("error", "Item is not updated for duplicate entry.");
            }
        }
        return back();
    }


    public function search_trxn(Request $request)
    {
        $student_id = $request->student_id;
        /*$school_name = $request->school_name;*/
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
                return view('schoolpanel/report/student_transaction_list')->with(['transaction_lists' => $transaction_lists]);
             }

            else{
                 /*If search student ID only*/
                if (!empty($student_id)) {
                    $transaction_lists = DB::select( DB::raw("SELECT * FROM transaction_lists WHERE student_id = $student_id"));
                    $size = count($transaction_lists);
                    if($size>0){
                        return view('schoolpanel/report/student_transaction_list')->with(['transaction_lists' => $transaction_lists]);
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
                return view('schoolpanel/report/student_transaction_list')->with(['transaction_lists' => $transaction_lists]);
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
        //         return view('backend/reports/transaction_list')->with(['transaction_lists' => $transaction_lists]);
        //     }
        //     else{
        //         session()->flash("error", "No Item(s) found");
        //         return back();
        //      }

        // }






    }






}
