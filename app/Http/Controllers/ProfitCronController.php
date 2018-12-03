<?php

namespace App\Http\Controllers;

use App\Models\AdminPlus_SiteProfitDetails;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitCronController extends Controller
{
    public function runcron()
    {
        date_default_timezone_set("asia/ho_chi_minh");
        $yesterday = date('Y-m-d', strtotime('-1 days'));
        $cron_type_arr = ['imei', 'server', 'imei_service', 'file_service', 'server_service', 'balance_summary'];
        foreach ($cron_type_arr as $cron_type) {
            if ('imei' === $cron_type || 'file' === $cron_type || 'server' === $cron_type) {
                $this->updateDailyProfit($yesterday, $cron_type);
            }
        }
    }

    public function updateDailyProfit($date, $service_type)
    {
        if ($date) {
            $profit = $linked_profit = 0;
            if ('imei' === $service_type) {
                $record = Db::table('imei_service_order')
                    ->whereraw("date(convert_tz(completed_on,'+00:00','+07:00')) = '" . $date . "'")
                    ->where('ignore_profit', 0)
                    ->where('credit_default_currency', '!=', 0)
                    ->where('status', '=', 'Completed')
                    ->where('amount_debitted', '=', 1)
                    ->selectRaw('sum(if(link_order_id != 0, credit_default_currency, credit_default_currency - purchase_cost)) as profit, sum(if(link_order_id != 0, credit_default_currency, 0 )) as linked_profit')
                    ->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
                $linked_profit = (null === $record->linked_profit ? 0 : $record->linked_profit);
            }

            if ('file' === $service_type) {
                $record = Db::table('file_service_order')
                    ->whereraw("date(convert_tz(completed_on,'+00:00','+07:00')) = '" . $date . "'")
                    ->where('ignore_profit', 0)
                    ->where('credit_default_currency', '!=', 0)
                    ->where('status', '=', 'Completed')
                    ->selectRaw('sum(credit_default_currency - purchase_cost) as profit')
                    ->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
            }

            if ('server' === $service_type) {
                $record = Db::table('server_service_order')
                    ->whereraw("date(convert_tz(completed_on,'+00:00','+07:00')) = '" . $date . "'")
                    ->where('ignore_profit', 0)
                    ->where('status', '=', 'Completed')
                    ->where('credit_default_currency', '!=', 0)
                    ->selectRaw('sum(credit_default_currency - ( purchase_cost * IF( quantity >0, quantity, 1 ) ) ) as profit')
                    ->first();
                $profit = (null === $record->profit ? 0 : $record->profit);
            }

            $exists = AdminPlus_SiteProfitDetails::where('date_profit', $date)->count();
            $data_arr['date_updated'] = new DateTime();
            $data_arr[$service_type . '_profit_amount'] = $profit;
            if ('imei' === $service_type) {
                $data_arr[$service_type . '_linked_profit'] = $linked_profit;
            }

            if ($exists) {
                AdminPlus_SiteProfitDetails::where('date_profit', $date)->update($data_arr);
            } else {
                $obj = new AdminPlus_SiteProfitDetails();
                $data_arr['date_added'] = new DateTime();
                $data_arr['date_profit'] = $date;
                $obj->addNew($data_arr);
            }

        }
    }

    public function runcrontoday()
    {
        date_default_timezone_set("asia/ho_chi_minh");
        $yesterday = date('Y-m-d');
        $cron_type_arr = ['imei', 'server', 'imei_service', 'file_service', 'server_service', 'balance_summary'];
        foreach ($cron_type_arr as $cron_type) {
            if ('imei' === $cron_type || 'file' === $cron_type || 'server' === $cron_type) {
                $this->updateDailyProfit($yesterday, $cron_type);
            }
        }
    }

    public function reloadprofit(Request $request)
    {
        $yesterday = $request->date;
        $cron_type_arr = ['imei', 'server', 'imei_service', 'file_service', 'server_service', 'balance_summary'];
        foreach ($cron_type_arr as $cron_type) {
            if ('imei' === $cron_type || 'file' === $cron_type || 'server' === $cron_type) {
                $this->updateDailyProfit($yesterday, $cron_type);
            }
        }
        return back();
    }

    public function runcronrange(Request $request)
    {
        if ($request->datefilter == !null) {
            $tg = explode(" - ", $request->datefilter);
            date_default_timezone_set("asia/ho_chi_minh");
            $begin = new DateTime($tg[0]);
            $end = new DateTime($tg[1]);
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);

            foreach ($daterange as $date) {

                $yesterday = $date->format("Y-m-d");
                $cron_type_arr = ['imei', 'server', 'imei_service', 'file_service', 'server_service', 'balance_summary'];
                foreach ($cron_type_arr as $cron_type) {
                    if ('imei' === $cron_type || 'file' === $cron_type || 'server' === $cron_type) {
                        $this->updateDailyProfit($yesterday, $cron_type);
                    }
                }
            }
        }
        return back();
    }
}
