<?php

namespace App\Http\Controllers\Functions;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use IMEIFusion;

require_once "gsmhub.class.php";

class Gsm extends Controller
{

    public function __construct()
    {
        //
    }

    public function getBalanceSupplier()
    {
        $supplier = Supplier::get();
        foreach ($supplier as $s) {
            if ($s->api_key) {
                $api = new IMEIFusion($s->site_username, $s->api_key, $s->site_url);
                $api->debug = false;
                $para = array();
                $request = $api->action('accountinfo', $para);
                if (isset($request['SUCCESS'])) {
                    $arr[$s->name] = $request['SUCCESS'][0]['AccoutInfo'];
                }
            }
        }
        return $arr;

    }
}

?>
