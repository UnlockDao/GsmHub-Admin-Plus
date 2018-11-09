<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitCronController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

    }

    public function updateDailyProfit()
    {
        $date = "2018/11/07";
        $service_type="server";
        if ($date) {
            $profit = $linked_profit = 0;
            if ('imei' === $service_type) {
                $record = Db::table('imei_service_order')->whereraw("date(completed_on) = '".$date."'")->where('ignore_profit', 0)->where('purchase_cost', '>', 0)->where('credit_default_currency', '!=', 0)->where('status', '=', 'Completed')->where('amount_debitted', '=', 1)->selectRaw('sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as profit, sum(if(link_order_id != 0, credit_default_currency, 0 )) as linked_profit')->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
                $linked_profit = (null === $record->linked_profit ? 0 : $record->linked_profit);
            }

            if ('file' === $service_type) {
                $record = Db::table('file_service_order')->whereraw("date(date_replied)  = '".$date."'")->where('ignore_profit', 0)->where('purchase_cost', '>', 0)->where('credit_default_currency', '!=', 0)->where('status', '=', 'Completed')->selectRaw('sum(credit_default_currency - purchase_cost) as profit')->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
            }

            if ('server' === $service_type) {
                $record = Db::table('server_service_order')->whereraw("date(completed_on) = '".$date."'")->where('ignore_profit', 0)->where('purchase_cost', '>', 0)->where('status', '=', 'Completed')->where('credit_default_currency', '!=', 0)->selectRaw('sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as profit')->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
            }

        }
        return $profit;
    }
}
