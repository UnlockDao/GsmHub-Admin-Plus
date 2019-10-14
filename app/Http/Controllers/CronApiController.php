<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Functions\Gsm;
use App\Http\Controllers\Payment\PaypalNVP;

class CronApiController extends Controller
{
    public function __construct()
    {
        $this->gsm = new Gsm();
    }

    public function checkCreditSuppliers()
    {
        $balance_supplier = $this->gsm->getBalanceSupplier();
        foreach ($balance_supplier as $key => $i){
            echo ' <tr>
                  <th scope="row">'.$key.'</th>
                                            <th scope="row">'.$i['credit'].'</th>
                                            <th scope="row">'.$i['currency'].'</th>
                                        </tr>';
        }
    }

    public function massPay()
    {
        $paypalNvpService = new PaypalNVP('binhnguyen1998822-facilitator_api1.gmail.com', 'ATStKJ9pJM5svPDDbIdpkR0LsWfjACksLgcVs9jVnDCxoiX.WWfBga8v', 'NEDVG5KF6CDSTNFA');
       return $balance_payment = $paypalNvpService->MassPayUsingAPI();
    }

}
