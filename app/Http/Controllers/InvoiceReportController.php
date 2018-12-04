<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Models\Currencie;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Serverservice;
use App\Models\Serverserviceorder;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $datefilter = $request->datefilter;
        $tg = explode(" - ", $datefilter);
        if ($datefilter == null) {
            $datefilter = date("1998/12/01 0:00:00") . ' - ' . date("Y/m/31 23:59:59");
            $tg = explode(" - ", $datefilter);
            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);
        } else {
            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);
        }

        if ($request->view == null) {
            $view = 100;
        } else {
            $view = $request->view;
        }
        $payment = Payment::get();
        $currency = Invoice::groupBy('currency')->get();

        $cachesearch = $request;


            if($request->status == 'paid'){
                $serverorder = Invoice::orderBy('id', 'desc')
                    ->where('invoice_status', 'LIKE', $request->status)
                    ->where('payment_gateway', 'LIKE', $request->payment)
                    ->where('payment_gateway_ref_id', 'LIKE', $request->payment_gateway_ref_id)
                    ->where('currency', 'LIKE', $request->currency)
                    ->whereBetween('date_paid', [$tg1,$tg2])
                    ->paginate($view);
            }else{
                $serverorder = Invoice::orderBy('id', 'desc')
                    ->where('invoice_status', 'LIKE', $request->status)
                    ->where('payment_gateway', 'LIKE', $request->payment)
                    ->where('payment_gateway_ref_id', 'LIKE', $request->payment_gateway_ref_id)
                    ->where('currency', 'LIKE', $request->currency)
                    ->whereBetween('date_added', [$tg1,$tg2])
                    ->paginate($view);
            }


        return view('invoicereport', compact('serverorder',  'cachesearch','payment','currency'));
    }


}