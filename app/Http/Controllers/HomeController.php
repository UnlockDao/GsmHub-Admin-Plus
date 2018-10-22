<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Imeiserviceorder;
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
    public function index(Request $request)
    {
        $day1 = CUtil::convertDateS(date('Y-m-d'));
        $day2 = CUtil::convertDateS(date('Y-m-d H:i:s'));

        $yesterday1 = CUtil::convertDateS(date('Y-m-d', strtotime('-1 days')));
        $yesterday2 = CUtil::convertDateS(date('Y-m-d 23:59:59', strtotime('-1 days')));

        $week1 = CUtil::convertDateS(date('Y-m-d', strtotime('-7 days')));
        $week2 = CUtil::convertDateS(date('Y-m-d 23:59:59', strtotime('-7 days')));

        $month1 = CUtil::convertDateS(date('Y-m-1'));
        $month2 = CUtil::convertDateS(date('Y-m-d 23:59:59'));


        $serveroderday = Serverserviceorder::where('status', 'COMPLETED')->whereBetween('completed_on', [$day1, $day2])
            ->select(DB::raw('SUM(credit_default_currency-(purchase_cost*quantity)) as profit'))
            ->first();
        $serveroderyesterday = Serverserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$yesterday1, $yesterday2])
            ->select(DB::raw('SUM(credit_default_currency-(purchase_cost*quantity)) as profit'))
            ->first();
        $serveroderweek = Serverserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$week1, $week2])
            ->select(DB::raw('SUM(credit_default_currency-(purchase_cost*quantity)) as profit'))
            ->first();
        $serverodermonth = Serverserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$month1, $month2])
            ->select(DB::raw('SUM(credit_default_currency-(purchase_cost*quantity)) as profit'))
            ->first();


        $imeioderday = Imeiserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$day1, $day2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeioderyesterday = Imeiserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$yesterday1, $yesterday2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeioderweek = Imeiserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$week1, $week2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();
        $imeiodermonth = Imeiserviceorder::where('ignore_profit', 0)->where('status', 'COMPLETED')->whereBetween('completed_on', [$month1, $month2])
            ->select(DB::raw('SUM(credit_default_currency-purchase_cost) as profit'))
            ->first();

        $datefilter = $request->datefilter;
        $tg = explode(" - ", $datefilter);
        if ($datefilter == null) {
            $datefilter = date("Y/m/01 0:00:00") . ' - ' . date("Y/m/d 23:59:59");
            $tg = explode(" - ", $datefilter);
            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);
        } else {
            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);
        }

        $imeioder = Imeiserviceorder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('ignore_profit', 0)
            //->where('purchase_cost', '>', 0) // wrong condition
            ->where('credit_default_currency', '!=', 0)
            ->where('status', '=', 'Completed')
            ->where('amount_debitted', '=', 1)
            ->selectRaw('sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as profit, sum(if(link_order_id != 0, credit_default_currency, 0 )) as linked_profit')
            ->first();
        $serveroder = Serverserviceorder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('ignore_profit', 0)
            //->where('purchase_cost', '>', 0) // wrong condition
            ->where('status', '=', 'Completed')
            ->where('credit_default_currency', '!=', 0)
            ->selectRaw('sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as profit')
            ->first();


        //server
        $serverchart2 = Serverserviceorder::where('ignore_profit', 0)
            // ->where('purchase_cost', '>', 0) // wrong condition
            ->where('credit_default_currency', '!=', 0)
            ->where('status', '=', 'Completed')
            ->where('amount_debitted', '=', 1)
            ->whereBetween('completed_on', [$tg1, $tg2])
            ->get();
        foreach ($serverchart2 as $s) {
            $data_array[] =
                array(
                    'completed_on' => CUtil::convertDate($s->completed_on, 'd'),
                    'credit_default_currency' => $s->credit_default_currency,
                    'purchase_cost' => $s->purchase_cost,
                    'quantity' => $s->quantity,
                    'profit' => $s->credit_default_currency - ($s->purchase_cost * $s->quantity)
                );
        }
        $collectserver = collect($data_array)->sortBy('completed_on');
        $chartserver = $collectserver->groupBy('completed_on')->map(function ($item) {
            return $item->sum(function ($item) {
                return (number_format($item['profit']));
            });
        })
            ->toArray();
        //imei
        $imeichart2 = Imeiserviceorder::where('ignore_profit', 0)
            // ->where('purchase_cost', '>', 0) // wrong condition
            ->where('credit_default_currency', '!=', 0)
            ->where('status', '=', 'Completed')
            ->where('amount_debitted', '=', 1)
            ->whereBetween('completed_on', [$tg1, $tg2])
            ->get();
        foreach ($imeichart2 as $s) {
            $data_arrayi[] =
                array(
                    'completed_on' => CUtil::convertDate($s->completed_on, 'd-m-Y'),
                    'credit_default_currency' => $s->credit_default_currency,
                    'purchase_cost' => $s->purchase_cost,
                    'profit' => $s->credit_default_currency - $s->purchase_cost
                );
        }
        $collectimei = collect($data_arrayi)->sortBy('completed_on');
        $chartimei = $collectimei->groupBy('completed_on')->map(function ($item) {
            return $item->sum(function ($item) {
                return (number_format($item['profit']));
            });
        })
            ->toArray();


        $chartserverdate = json_encode(array_keys($chartserver));

        $chartservervalue = json_encode(array_values($chartserver));
        $chartimeivalue = json_encode(array_values($chartimei));

        //count
        //server
        $chartservercount = $collectserver->groupBy('completed_on')->map(function ($item) {
            return $item->count(function ($item) {
                return (number_format($item['profit']));
            });
        })
            ->toArray();
        //imei
        $chartimeicount = $collectimei->groupBy('completed_on')->map(function ($item) {
            return $item->count(function ($item) {
                return (number_format($item['profit']));
            });
        })
            ->toArray();
        $chartcountservervalue = json_encode(array_values($chartservercount));
        $chartcountimeivalue = json_encode(array_values($chartimeicount));


        return view('home', compact('chartcountservervalue', 'chartcountimeivalue', 'chartimeivalue', 'chartserverdate', 'chartservervalue', 'serveroder', 'imeioder', 'serveroderday', 'serveroderyesterday', 'serverodermonth', 'serveroderweek', 'imeioderday', 'imeioderyesterday', 'imeioderweek', 'imeiodermonth'));
    }
}
