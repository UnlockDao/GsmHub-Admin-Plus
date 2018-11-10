<?php

namespace App\Http\Controllers;

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
        $cron = new ProfitCronController();
        $cron->runcrontoday();

        $profit = AdminPlus_SiteProfitDetails::orderBy('id', 'desc')->paginate(10);
        return view('profitreport', compact('profit'));
    }

}
