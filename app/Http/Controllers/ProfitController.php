<?php

namespace App\Http\Controllers;

use App\Models\AdminPlus_SiteProfitDetails;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $profit = AdminPlus_SiteProfitDetails::orderBy('id','desc')->paginate(10);
        return view('profitreport',compact('profit'));
    }

}
