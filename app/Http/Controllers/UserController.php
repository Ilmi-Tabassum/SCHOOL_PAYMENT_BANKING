<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use PDF;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user()->school_id;

        if($request->has('download'))
        {
            $pdf = PDF::loadView('backend/invoice/show',compact('user'));
            return $pdf->download('pdfview.pdf');
        }

        return view('backend/invoice/show',compact('user'));
    }
}
