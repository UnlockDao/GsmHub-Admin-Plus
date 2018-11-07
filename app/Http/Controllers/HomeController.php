<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Models\Imeiserviceorder;
use App\Models\Invoice;
use App\Models\Serverserviceorder;
use DateInterval;
use DatePeriod;
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
            $datefilter = date("Y/m/01 0:00:00") . ' - ' . date("Y/m/30 23:59:59");
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
        $invoice = Invoice::whereBetween('date_added', [$tg1, $tg2])
            ->where('created_by', '=', 0)
            ->where('invoice_status', 'paid')
            ->select(DB::raw('count(id) as icount, sum(invoice_amount) as amt, currency'))
            ->groupBy('currency')
            ->get();
        //get day in month
        $date = DateTime::createFromFormat("Y-n", "2018-11");
        $datesArray = array();
        for ($i = 1; $i <= $date->format("t"); $i++) {
            $datesArray[] = DateTime::createFromFormat("Y-n-d", "2018-11-$i")->format('d-n-Y');
        }
        //old version
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

        $pendingoder = $this->pendingoder();;
        $topservice = $this->topserviceordercount();
        $profitchart = $this->profitchart($datefilter);
        $ordercountchart = $this->ordercountchart($datefilter);
        $incomechart = $this->incomechart($datefilter);

        return view('home', compact( 'serveroder', 'imeioder', 'invoice', 'pendingoder', 'topservice','profitchart','ordercountchart','incomechart'));
    }

    public function pendingoder()
    {
        $return_arr = [];
        $arr = [];
        $stat_arr = ['today_new', 'today_accepted'];
        foreach ($stat_arr as $fld) {
            $arr[$fld] = 0;
        }
        $stat_qryi = ImeiServiceOrder::SELECTRAW("sum(IF ((status = '' AND ( api_submit_status = 'pending_activation' OR api_submit_status = 'resubmit') ), 1, 0 )) as today_new, " . "sum(IF ((status = 'ACTIVE' AND  api_submit_status = 'submitted'), 1, 0 )) as today_accepted")->first();
        $stat_qrys = ServerServiceOrder::SELECTRAW("sum(IF ((status = '' AND ( api_submit_status = 'pending_activation' OR api_submit_status = 'resubmit') ), 1, 0 )) as today_new, " . "sum(IF ((status = 'ACTIVE' AND  api_submit_status = 'submitted'), 1, 0 )) as today_accepted")->first();
        if ($stat_qryi) {
            foreach ($stat_arr as $fld) {
                $arri[$fld] = (isset($stat_qryi[$fld]) && $stat_qryi[$fld] ? (int)($stat_qryi[$fld]) : 0);
            }
        }
        if ($stat_qrys) {
            foreach ($stat_arr as $fld) {
                $arrs[$fld] = (isset($stat_qrys[$fld]) && $stat_qrys[$fld] ? (int)($stat_qrys[$fld]) : 0);
            }
        }

        $return_arr['ImeiServiceOrder'] = $arri;
        $return_arr['ServerServiceOrder'] = $arrs;
        return $return_arr;
    }

    public function income(){
        $return_arr = [];
        $return_arr['income'] = '';
        $year = date('Y');
        $month = date('m');
        $income_recds = Invoice::whereRaw('YEAR(convert_tz(date_added,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(date_added,"+00:00","+07:00")) = ' . $month)
            ->where('created_by', '=', 0)
            ->where('invoice_status', 'paid')
            ->selectRaw('day(convert_tz(date_added,"+00:00","+07:00")) as day, sum(invoice_amount) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        for ($month = 1; $month <= 31; ++$month) {
            $income_cnt = (isset($income_recds[$month]) ? $income_recds[$month] : 0);
            $return_arr['income'] .= $income_cnt. ',';
        }
        $return_arr['income'] = rtrim($return_arr['income'], ',');
        $return_arr['date'] = '1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31';

        return $return_arr;
    }
    public function incomechart($datefilter){
        $tg = explode(" - ", $datefilter);
        $tg1 = CUtil::convertDateS($tg[0]);
        $tg2 = CUtil::convertDateS($tg[1]);

        $begin = new DateTime( $tg[0]);
        $end = new DateTime( $tg[1] );

        $return_arr['income'] = '';
        $return_arr['date']='';
        $income_recds = Invoice::whereBetween('date_added', [$tg1, $tg2])
            ->where('created_by', '=', 0)
            ->where('invoice_status', 'paid')
            ->selectRaw('date(convert_tz(date_added,"+00:00","+07:00")) as day, sum(invoice_amount) as total')
            ->groupby('day')
            ->pluck('total', 'day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        foreach($daterange as $date){
            $month= $date->format("Y-m-d");
            $income_cnt = (isset($income_recds[$month]) ? $income_recds[$month] : 'null');
            $return_arr['income'] .= $income_cnt. ',';
            $return_arr['date'] .='"'.($month) . '",';
        }
        $return_arr['income'] = '['.rtrim($return_arr['income'],  ',').']';
        $return_arr['date'] = '['.rtrim($return_arr['date'], ',').']';

        return $return_arr;
    }


    public function thismonthorderprofit()
    {
        $return_arr = [];
        $return_arr['imei'] = '';
        $return_arr['server'] = '';
        $year = date('Y');
        $month = date('m');
        $imei_recds = ImeiServiceOrder::whereRaw('YEAR(convert_tz(completed_on,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(completed_on,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'COMPLETED')
            ->selectRaw('day(convert_tz(completed_on,"+00:00","+07:00")) as day, sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $server_recds = ServerServiceOrder::whereRaw('YEAR(convert_tz(completed_on,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(completed_on,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'COMPLETED')
            ->selectraw('day(convert_tz(completed_on,"+00:00","+07:00")) as day, sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as total')
            ->groupby('day')
            ->pluck('total', 'day');
       for ($month = 1; $month <= 31; ++$month) {
            $imei_cnt = (isset($imei_recds[$month]) ? $imei_recds[$month] : 'null');

            $server_cnt = (isset($server_recds[$month]) ? $server_recds[$month] : 'null');

            $return_arr['imei'] .= round($imei_cnt,2) . ',';

            $return_arr['server'] .= round($server_cnt,2) . ',';
        }
        $return_arr['imei'] = rtrim($return_arr['imei'], ',');

        $return_arr['server'] = rtrim($return_arr['server'], ',');
        $return_arr['date'] = '[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]';

        return $return_arr;
    }

    public function topserviceordercount()
    {
        $return_arr = [];
        $return_arr['imei'] = '';
        $return_arr['server'] = '';
        $return_arr['imei'] = ImeiServiceOrder::leftjoin('imei_service', 'imei_service_id', '=', 'imei_service.id')
            ->where('imei_service_order.status', 'COMPLETED')
            ->where('api_submit_status', 'submitted')
            ->select('imei_service.id', 'imei_service.service_name',
                DB::raw("count('imei_service_order.id') as cnt"),
                DB::raw('sum(if(link_order_id != 0, imei_service_order.credit_default_currency, imei_service_order.credit_default_currency - imei_service_order.purchase_cost)) as profit, sum(if(link_order_id != 0, credit_default_currency, 0 )) as linked_profit'))
            ->groupBy('imei_service_id')
            ->orderBy('cnt', 'DESC')
            ->take(10)
            ->get();
        $return_arr['server'] = ServerServiceOrder::leftjoin('server_service', 'server_service_id', '=', 'server_service.id')
            ->where('server_service_order.status', 'COMPLETED')->where('api_submit_status', 'submitted')
            ->select('server_service.id', 'server_service.service_name',
                DB::raw("count('server_service_order.id') as cnt"),
                DB::raw("sum(server_service_order.credit_default_currency - ( server_service_order.purchase_cost * IF( quantity >0, server_service_order.quantity, 1 ) ) ) as profit"))
            ->groupBy('server_service_id')
            ->orderBy('cnt', 'DESC')
            ->take(10)
            ->get();
        return $return_arr;
    }

    public function profitchart($datefilter){
        $tg = explode(" - ", $datefilter);
        $tg1 = CUtil::convertDateS($tg[0]);
        $tg2 = CUtil::convertDateS($tg[1]);

        $begin = new DateTime( $tg[0]);
        $end = new DateTime( $tg[1] );

        $return_arr = [];
        $return_arr['imei'] = '';
        $return_arr['server'] = '';
        $return_arr['date']='';

        $imei_recds = ImeiServiceOrder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('status', '=', 'COMPLETED')
            ->selectRaw('date(convert_tz(completed_on,"+00:00","+07:00")) as day, sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $server_recds = ServerServiceOrder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('status', '=', 'COMPLETED')
            ->selectraw('date(convert_tz(completed_on,"+00:00","+07:00")) as day, sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as total')
            ->groupby('day')
            ->pluck('total', 'day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

        foreach($daterange as $date){
            $month= $date->format("Y-m-d");
            $imei_cnt = (isset($imei_recds[$month]) ? $imei_recds[$month] : 'null');
            $server_cnt = (isset($server_recds[$month]) ? $server_recds[$month] : 'null');

            $return_arr['imei'] .= $imei_cnt. ',';
            $return_arr['server'] .= $server_cnt . ',';
            $return_arr['date'] .='"'.($month) . '",';

        }
        $return_arr['imei'] = '['.rtrim($return_arr['imei'], ',').']';
        $return_arr['server'] = '['.rtrim($return_arr['server'], ',').']';
        $return_arr['date'] = '['.rtrim($return_arr['date'], ',').']';
        return $return_arr;
    }

    public function ordercountchart($datefilter){
        $tg = explode(" - ", $datefilter);
        $tg1 = CUtil::convertDateS($tg[0]);
        $tg2 = CUtil::convertDateS($tg[1]);

        $begin = new DateTime( $tg[0]);
        $end = new DateTime( $tg[1] );

        $return_arr = [];
        $return_arr['imei'] = '';
        $return_arr['server'] = '';
        $return_arr['imeiREJECTED'] = '';
        $return_arr['serverREJECTED'] = '';
        $return_arr['date']='';

        //
        $imei_recds = ImeiServiceOrder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('status', '=', 'COMPLETED')
            ->selectRaw('date(convert_tz(completed_on,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $imei_recdsREJECTED = ImeiServiceOrder::whereBetween('date_rejected', [$tg1, $tg2])
            ->where('status', '=', 'REJECTED')
            ->selectRaw('date(convert_tz(date_rejected,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $server_recds = ServerServiceOrder::whereBetween('completed_on', [$tg1, $tg2])
            ->where('status', '=', 'COMPLETED')
            ->selectraw('date(convert_tz(completed_on,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')->pluck('total', 'day');
        $server_recdsREJECTED = ServerServiceOrder::whereBetween('date_rejected', [$tg1, $tg2])
            ->where('status', '=', 'REJECTED')
            ->selectraw('date(convert_tz(date_rejected,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')->pluck('total', 'day');
        //


        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

        foreach($daterange as $date){
            $month= $date->format("Y-m-d");
            $imei_cnt = (isset($imei_recds[$month]) ? $imei_recds[$month] : 'null');
            $imei_cntREJECTED = (isset($imei_recdsREJECTED[$month]) ? $imei_recdsREJECTED[$month] : 'null');

            $server_cnt = (isset($server_recds[$month]) ? $server_recds[$month] : 'null');
            $server_cntREJECTED = (isset($server_recdsREJECTED[$month]) ? $server_recdsREJECTED[$month] : 'null');

            $return_arr['imei'] .= $imei_cnt . ',';
            $return_arr['imeiREJECTED'] .= $imei_cntREJECTED . ',';

            $return_arr['server'] .= $server_cnt . ',';
            $return_arr['serverREJECTED'] .= $server_cntREJECTED . ',';
            $return_arr['date'] .='"'.($month) . '",';

        }
        $return_arr['imei'] = '['.rtrim($return_arr['imei'], ',').']';
        $return_arr['imeiREJECTED'] = '['.rtrim($return_arr['imeiREJECTED'], ',').']';

        $return_arr['server'] = '['.rtrim($return_arr['server'], ',').']';
        $return_arr['serverREJECTED'] = '['.rtrim($return_arr['serverREJECTED'], ',').']';

        $return_arr['date'] = '['.rtrim($return_arr['date'], ',').']';

        return $return_arr;
    }

    public function thismonthordercount()
    {
        $return_arr = [];
        $return_arr['imei'] = '';
        $return_arr['server'] = '';
        $return_arr['imeiREJECTED'] = '';
        $return_arr['serverREJECTED'] = '';
        $year = date('Y');
        $month = date('m');
        $imei_recds = ImeiServiceOrder::whereRaw('YEAR(convert_tz(completed_on,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(completed_on,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'COMPLETED')
            ->selectRaw('day(convert_tz(completed_on,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $imei_recdsREJECTED = ImeiServiceOrder::whereRaw('YEAR(convert_tz(date_rejected,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(date_rejected,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'REJECTED')
            ->selectRaw('day(convert_tz(date_rejected,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')
            ->pluck('total', 'day');
        $server_recds = ServerServiceOrder::whereRaw('YEAR(convert_tz(completed_on,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(completed_on,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'COMPLETED')
            ->selectraw('day(convert_tz(completed_on,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')->pluck('total', 'day');
        $server_recdsREJECTED = ServerServiceOrder::whereRaw('YEAR(convert_tz(date_rejected,"+00:00","+07:00")) = ' . $year)
            ->whereRaw('MONTH(convert_tz(date_rejected,"+00:00","+07:00")) = ' . $month)
            ->where('status', '=', 'REJECTED')
            ->selectraw('day(convert_tz(date_rejected,"+00:00","+07:00")) as day, count(id) as total')
            ->groupby('day')->pluck('total', 'day');
        for ($month = 1; $month <= 31; ++$month) {
            $imei_cnt = (isset($imei_recds[$month]) ? $imei_recds[$month] : 'null');
            $imei_cntREJECTED = (isset($imei_recdsREJECTED[$month]) ? $imei_recdsREJECTED[$month] : 'null');

            $server_cnt = (isset($server_recds[$month]) ? $server_recds[$month] : 'null');
            $server_cntREJECTED = (isset($server_recdsREJECTED[$month]) ? $server_recdsREJECTED[$month] : 'null');

            $return_arr['imei'] .= $imei_cnt . ',';
            $return_arr['imeiREJECTED'] .= $imei_cntREJECTED . ',';

            $return_arr['server'] .= $server_cnt . ',';
            $return_arr['serverREJECTED'] .= $server_cntREJECTED . ',';
        }
        $return_arr['imei'] = rtrim($return_arr['imei'], ',');
        $return_arr['imeiREJECTED'] = rtrim($return_arr['imeiREJECTED'], ',');

        $return_arr['server'] = rtrim($return_arr['server'], ',');
        $return_arr['serverREJECTED'] = rtrim($return_arr['serverREJECTED'], ',');
        $return_arr['date'] = '1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31';

        return $return_arr;
    }

}
