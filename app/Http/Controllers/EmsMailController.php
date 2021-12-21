<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;




class EmsMailController extends Controller
{
	public function index ($value='')
	{
		// code...
		return 'test';
	}

	public function Sendmail($value='')
	{
		$data = array('company_name'=>'', 'name'=>'', 'email'=>'', 'number'=>'');
			Mail::send('emails.merchantregistration', $data, function($message) {
			$message->to("nazmus.shahadat@shurjomukhi.com.bd", 'shurjoPay')->subject('AB Bank Email Test');
			$message->from('no-reply@abbankems.com','AB Bank EMS');
		});
	}

}