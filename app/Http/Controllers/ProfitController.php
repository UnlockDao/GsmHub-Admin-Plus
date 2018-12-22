<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Models\AdminPlus_SiteProfitDetails;
use Illuminate\Http\Request;

class ProfitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cachesearch = $request;
        $cron = new ProfitCronController();
        $cron->runcrontoday();

        $profit = AdminPlus_SiteProfitDetails::orderBy('date_profit', 'desc')->paginate(30);
        if (CUtil::checkauth()){
            return view('profitreport', compact('profit','cachesearch'));
        }else{
            return redirect('home');
        }

    }

}
