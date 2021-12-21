<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;



class SmsController extends Controller
{
	public function index ($value='')
	{
		// code...
		return 'test';
	}

	public function sendSms($cell,$body,$mask)
	{

	 $url="http://smpp.ajuratech.com:7788/sendtext?apikey=9f74481c10fc198d&secretkey=c5011013&callerID=".$mask."&toUser=".$cell."&messageContent=".urlencode($body);
		
		$headers = [
            'Content-Type: application/json'
        ];

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec ($ch);
        $err = curl_error($ch);  //if you need
        curl_close ($ch);
        
       // echo $response;exit;
		return $response;
		//$this->smslog($data);
	}

	public function smslog($data='')
	{
		// code...
	}
}


