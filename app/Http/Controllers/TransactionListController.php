<?php

namespace App\Http\Controllers;

use App\Models\SchoolInfo;
use App\Models\TransactionList;
use Illuminate\Http\Request;
use DB;

class TransactionListController extends Controller
{

    public function index()
    {
        $school_list = SchoolInfo::orderBy("school_name","asc")->get();
        $transaction_lists = DB::table('transaction_lists')
            ->join('students', 'transaction_lists.student_id', '=', 'students.id')
            ->select('transaction_lists.*', 'students.student_id as sid')
            ->get();

        return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);
    }

    public function show_details($id){
        $trn_details = DB::table('transaction_lists')
            ->join('students', 'transaction_lists.student_id', '=', 'students.id')
            ->select('transaction_lists.*', 'students.student_id')
            ->where('transaction_lists.id',$id)
            ->get();
        return response($trn_details);
    }

    public function edit_trxn($id)
    {
        $data = DB::table('transaction_lists')
            ->join('students', 'transaction_lists.student_id', '=', 'students.id')
            ->select('transaction_lists.*', 'students.student_id as s_id')
            ->where('transaction_lists.id',$id)
            ->get();
        return $data;
    }

    public function update_trxn(Request $request)
    {
        $id = $request->post("item_id_trxn");
        $data = TransactionList::find($id);
        $data->student_id = $request->studentID;
        $data->amount = $request->amount;
        $data->order_id = $request->trx_id;
        $data->trx_id = $request->trx_id;
        $data->bank_trx_id = $request->bank_trx_id;
        $data->return_code = $request->return_code;
        $data->status = $request->status;
        $data->method = $request->method;
        $data->trn_date = $request->trn_date;
        $data->invoice_no = $request->invoice_no;
        try {
            $data->save();
            session()->flash("success", "Transaction is updated successfully!");
        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                session()->flash("error", "Transaction is not updated for duplicate entry.");
            }
        }
        return back();
    }


    public function search_trxn(Request $request)
    {
        $student_id = $request->student_id;
        $school = $request->schoolid;
        $start_date = ($request->start_date);
        $end_date = ($request->end_date);

        if (!empty($student_id) && !empty($start_date) && !empty($end_date)  && !empty($school))
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE t.trn_date BETWEEN '$start_date' AND '$end_date' AND t.student_id=$student_id AND sa.school_id=$school"));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);

        }
        elseif (!empty($student_id) && !empty($start_date) && !empty($end_date))
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE t.trn_date BETWEEN '$start_date' AND '$end_date' AND t.student_id=$student_id"));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);

        }
        elseif (!empty($start_date) && !empty($end_date) && !empty($school))
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE t.trn_date BETWEEN '$start_date' AND '$end_date' AND sa.school_id=$school"));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);
        }
        elseif (!empty($start_date) && !empty($end_date))
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE t.trn_date BETWEEN '$start_date' AND '$end_date' "));
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);
        }
        elseif (!empty($school) && $school!=0)
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE sa.school_id=$school "));

            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);
        }
        elseif (!empty($student_id))
        {
            $transaction_lists = DB::select( DB::raw("SELECT t.*,s.student_id sid
                                FROM transaction_lists t
                                INNER JOIN students s ON t.student_id = s.id
                                INNER JOIN student_academics sa ON t.student_id = sa.student_id
                                WHERE s.student_id=$student_id "));

            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("success", "Your search result are");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);
        }
        else
        {
            $transaction_lists="";
            $school_list = SchoolInfo::orderBy("school_name","asc")->get();
            session()->flash("error", "No data found");
            return view('backend/Reports/transaction_list')->with(['school_list' => $school_list,'transaction_lists' => $transaction_lists]);

        }


    }













}
