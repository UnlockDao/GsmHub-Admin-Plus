<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Imeiserviceorder;
use App\Serverservice;
use App\Serverserviceorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $day1= CUtil::convertDateS(date('Y-m-d'));
        $day2 = CUtil::convertDateS(date('Y-m-d H:i:s'));

        $yesterday1= CUtil::convertDateS(date('Y-m-d',strtotime('-1 days')));
        $yesterday2= CUtil::convertDateS(date('Y-m-d 23:59:59',strtotime('-1 days')));

        $week1= CUtil::convertDateS(date('Y-m-d',strtotime('-7 days')));
        $week2= CUtil::convertDateS(date('Y-m-d 23:59:59',strtotime('-7 days')));

        $month1= CUtil::convertDateS(date('Y-m-1'));
        $month2= CUtil::convertDateS(date('Y-m-d 23:59:59'));

        $serveroder =Serverserviceorder::where('status','COMPLETED')->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))->first();
        $serveroderday =Serverserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$day1, $day2])
                                            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
                                            ->first();
        $serveroderyesterday =Serverserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$yesterday1, $yesterday2])
                                            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
                                            ->first();
        $serveroderweek =Serverserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$week1, $week2])
                                            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
                                            ->first();
        $serverodermonth =Serverserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$month1, $month2])
                                            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
                                            ->first();

        $imeioder =Imeiserviceorder::where('status','COMPLETED')->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))->first();
        $imeioderday =Imeiserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$day1, $day2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeioderyesterday =Imeiserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$yesterday1, $yesterday2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeioderweek =Imeiserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$week1, $week2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeiodermonth =Imeiserviceorder::where('status','COMPLETED')->whereBetween('completed_on', [$month1, $month2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();

        return view('home',compact('serveroder','imeioder','serveroderday','serveroderyesterday','serverodermonth','serveroderweek','imeioderday','imeioderyesterday','imeioderweek','imeiodermonth'));
    }
}
