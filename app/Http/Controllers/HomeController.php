<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Imeiserviceorder;
use App\Invoice;
use App\Serverserviceorder;
use DateTime;
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
        //get day in month
        $date = DateTime::createFromFormat("Y-n", "2018-11");
        $datesArray = array();
        for ($i = 1; $i <= $date->format("t"); $i++) {
            $datesArray[] = DateTime::createFromFormat("Y-n-d", "2018-11-$i")->format('d-n-Y');
        }
        //server
        $serverchart = Serverserviceorder::where('ignore_profit', 0)
            ->orderBy('completed_on')
            ->where('credit_default_currency', '!=', 0)
            ->where('status', '=', 'Completed')
            ->where('amount_debitted', '=', 1)
            ->whereBetween('completed_on', [$tg1, $tg2])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('date(convert_tz(completed_on,"+00:00","+07:00")) as date'),
                DB::raw('COUNT(*) as value'),
                DB::raw('sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as profit')
            ]);

        $imeichart = Imeiserviceorder::where('ignore_profit', 0)
            ->where('credit_default_currency', '!=', 0)
            ->where('status', '=', 'Completed')
            ->where('amount_debitted', '=', 1)
            ->whereBetween('completed_on', [$tg1, $tg2])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('date(convert_tz(completed_on,"+00:00","+07:00")) as date'),
                DB::raw('COUNT(*) as value'),
                DB::raw('sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as profit, sum(if(link_order_id != 0, credit_default_currency, 0 )) as linked_profit')
            ]);
        $invoicechart = Invoice::whereBetween('date_added', [$tg1, $tg2])
                        ->where('created_by', '=', 0)
                        ->where('invoice_status', 'paid')
                        ->groupBy('date')
                        ->orderBy('date', 'ASC')
                        ->get([
                            DB::raw('date(convert_tz(date_added,"+00:00","+07:00")) as date'),
                            DB::raw('COUNT(id) as icount'),
                            DB::raw('sum(invoice_amount) as amt')
                        ]);

        return view('home', compact('serverchart','imeichart','serveroder','imeioder','invoicechart'));
    }
}
