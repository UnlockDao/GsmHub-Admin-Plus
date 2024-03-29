<?php

namespace App\Http\Controllers;

use App\Models\AdminPlus_SiteProfitDetails;
use Illuminate\Http\Request;

class ProfitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $cachesearch = $request;
        $cron = new ProfitCronController();
        $cron->runcrontoday();

        $profit = AdminPlus_SiteProfitDetails::orderBy('date_profit', 'desc')->paginate(30);
        return view('report.profitreport', compact('profit', 'cachesearch'));
    }

}
