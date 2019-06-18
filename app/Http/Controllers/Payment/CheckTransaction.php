<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckTransaction extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $paypal_receiver_details = DB::table('paypal_receiver_details')->get();
        $receiver = DB::table('paypal_receiver_details')->where('receiver_email', $request->receiver_email)->first();
        if ($receiver) {
            $paypalNvpService = new PaypalNVP($receiver->api_user_name, $receiver->api_signature, $receiver->api_password);
            $data = $paypalNvpService->fetchPaypalNVPTransactionDetails($request->idTransaction);
        } else {
            $data = '';
        }
        $cache = $request;

        return view('report.check-transaction', compact('data', 'cache', 'paypal_receiver_details'));
    }
}

?>
