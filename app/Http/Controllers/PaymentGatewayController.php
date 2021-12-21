<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use App\Models\SchoolInfo;
use App\Models\Section;
use Illuminate\Http\Request;
use DB;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schools = SchoolInfo::orderBy("school_name", "asc")->get();

        return view('backend/payment_gateway/add')->with(['schools' => $schools]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $list = PaymentGateway::orderBy("school_id", "asc")->get();
       // return $list;

        return view('backend/payment_gateway/list')->with(['list' => $list]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $school_id=$request->school_id;
        $user=$request->user;
        $pass=$request->pass;
        $s_code=$request->s_code;
        $ipn=$request->ipn;
        $success=$request->success;
        $fail=$request->fail;
        $cancel=$request->cancel;
        $object = new PaymentGateway;
        $object->school_id=$school_id;
        $object->user=$user;
        $object->pass=$pass;
        $object->s_code=$s_code;
        $object->ipn=$ipn;
        $object->success=$success;
        $object->fail=$fail;
        $object->cancel=$cancel;
        $obj=DB::select(DB::raw("SELECT * FROM payment_gateways WHERE school_id=$school_id"));

 /*      print_r($e);
        exit();*/

        if(!empty($obj))
        {
            session()->flash("error", "Already exixts!");
        }
        else{
            if($object->save())
            {
                session()->flash("success", "saved successfully!");

            }else{
                session()->flash("error", "Please try again!");

            }
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentGateway $paymentGateway)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        //
    }
}
