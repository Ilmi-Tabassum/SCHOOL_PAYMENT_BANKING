<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\agentpanel\AgentPanel;
use App\Models\User;
use App\Models\UserProfile;
use DB;
use Auth;
use smasif\ShurjopayLaravelPackage\ShurjopayService;
use Intervention\Image\Facades\Image;
use App\Models\FeesCollection;
use App\Models\TransactionList;

class Return extends Controller
{
    
	public function index(Request $request)
	{
		return 0;
	}
}