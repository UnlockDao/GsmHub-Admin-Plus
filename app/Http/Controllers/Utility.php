<?php

namespace App\Http\Controllers;


use App\Models\Clientgroup;
use App\Models\Clientgroupprice;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Imeiservice;
use App\Models\Imeiservicepricing;
use App\Models\Serverservice;
use App\Models\Serverserviceclientgroupcredit;
use App\Models\Serverservicetypewisegroupprice;
use App\Models\Serverservicetypewiseprice;
use App\Models\Serverservicepricing;
use App\Models\Supplier;
use Illuminate\Http\Request;

class Utility
{
    public function Request(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $this->Repricing($type, $id);
        return back();
    }

    public function Repricing($type, $id)
    {
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();

        if ($type == 'all' || $type == 'clientgroup') {
            if ($id == null) {
                $cliengroup = Clientgroup::get();
            } else {
                $cliengroup = Clientgroup::where('id', $id)->get();
            }
            //imei
            $this->imeicheckapi();
            if ($cliendefault == !null) {
                $imei = Imeiservice::get();
                foreach ($cliengroup as $clg) {
                    foreach ($imei as $i) {
                        $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id',
                            $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                        if ($imeiprice == !null) {
                            if ($i->purchase_cost > $i->credit + $imeiprice->discount) {
                                foreach ($currencies as $c) {
                                    Clientgroupprice::where('group_id', $cliendefault->id)
                                        ->where('service_type', 'imei')
                                        ->where('currency', $c->currency_code)
                                        ->where('service_id', $i->id)
                                        ->update(['discount' => ($i->purchase_cost - $i->credit) * $this->exchangeRateStatic($c->currency_code)]);
                                }
                            }

                            $giabanle = $i->credit + $imeiprice->discount;
                            $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                            $y = round($chietkhau, 10) - $i->credit;
                            foreach ($currencies as $c) {
                                $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $c->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => $y * $this->exchangeRateStatic($c->currency_code)]);
                            }
                        }
                    }
                }
            }

            //services
            $this->UpdatePurchaseCostVipServer();
            if ($cliendefault == !null) {
                foreach ($cliengroup as $clg) {
                    $range = Serverserviceclientgroupcredit::where('client_group_id', $clg->id)->where('currency',
                        $currenciessite->config_value)->get();
                    foreach ($range as $ra) {
                        $i = Serverserviceclientgroupcredit::where('client_group_id', $cliendefault->id)
                            ->where('currency', $currenciessite->config_value)
                            ->where('server_service_range_id', $ra->server_service_range_id)
                            ->first();
                        $giabanle = $i->credit;
                        if ($ra->serverservicequantityrange->serverservicequantityrange == !null) {
                            $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) * $clg->chietkhau));
                            foreach ($currencies as $cu) {
                                $update = Serverserviceclientgroupcredit::where('server_service_range_id',
                                    $ra->server_service_range_id)
                                    ->where('client_group_id', $clg->id)
                                    ->where('currency', $cu->currency_code)
                                    ->update(['credit' => $chietkhau * $this->exchangeRateStatic($cu->currency_code)]);
                            }
                        }
                    }
                }
            }
            //services wise
            if ($cliendefault == !null) {
                $wise = Serverservicetypewiseprice::get();
                foreach ($wise as $wi) {
                    echo '<hr>';
                    echo '---------------------services wise------------------------';
                    echo '<br>';
                    echo $wi->service_type;
                    echo '<br>';
                    echo 'Purchase Cost(Net) :' . $wi->purchase_cost;
                    if ($wi->adminplus_service == !null) {
                        echo '<br>';
                        echo 'Giá bán lẻ: ' . $wi->servicetypegroupprice->where('group_id',
                                $cliendefault->id)->first()->amount;
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        echo '<br>';
                        foreach ($wi->servicetypegroupprice as $groupprice) {
                            if ($groupprice->clientgroup == !null) {
                                echo 'Chiết khấu :' . $chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) * $groupprice->clientgroup->chietkhau));
                                echo '|' . $groupprice->clientgroup->group_name . '|' . $groupprice->id;
                                Serverservicetypewisegroupprice::where('id', $groupprice->id)
                                    ->update(['amount' => $chietkhau]);
                            }

                            echo '<br>';
                        }
                        echo '<hr>';
                    }
                }
            }
        }
        if ($type == 'imei') {
            $this->imeicheckapi();
            $cliengroup = Clientgroup::get();
            //imei
            if ($cliendefault == !null) {
                $imei = Imeiservice::get();
                foreach ($cliengroup as $clg) {
                    foreach ($imei as $i) {
                        $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id',
                            $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                        if ($imeiprice == !null) {
                            if ($i->purchase_cost > $i->credit + $imeiprice->discount) {
                                foreach ($currencies as $c) {
                                    Clientgroupprice::where('group_id', $cliendefault->id)
                                        ->where('service_type', 'imei')
                                        ->where('currency', $c->currency_code)
                                        ->where('service_id', $i->id)
                                        ->update(['discount' => ($i->purchase_cost - $i->credit) * $this->exchangeRateStatic($c->currency_code)]);
                                }
                            }

                            $giabanle = $i->credit + $imeiprice->discount;
                            $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                            $y = round($chietkhau, 10) - $i->credit;
                            foreach ($currencies as $c) {
                                Clientgroupprice::where('group_id', $clg->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $c->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => $y * $this->exchangeRateStatic($c->currency_code)]);
                            }
                        }
                    }
                }
            }
        }


        if ($type == 'server') {
            $cliengroup = Clientgroup::get();
            //services
            $this->UpdatePurchaseCostVipServer();
            if ($cliendefault == !null) {
                foreach ($cliengroup as $clg) {
                    $range = Serverserviceclientgroupcredit::where('client_group_id', $clg->id)->where('currency',
                        $currenciessite->config_value)->get();
                    foreach ($range as $ra) {
                        $i = Serverserviceclientgroupcredit::where('client_group_id', $cliendefault->id)
                            ->where('currency', $currenciessite->config_value)
                            ->where('server_service_range_id', $ra->server_service_range_id)
                            ->first();
                        $giabanle = $i->credit;
                        if ($ra->serverservicequantityrange->serverservicequantityrange == !null) {
                            $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) * $clg->chietkhau));
                            foreach ($currencies as $cu) {
                                $update = Serverserviceclientgroupcredit::where('server_service_range_id',
                                    $ra->server_service_range_id)
                                    ->where('client_group_id', $clg->id)
                                    ->where('currency', $cu->currency_code)
                                    ->update(['credit' => $chietkhau * $this->exchangeRateStatic($cu->currency_code)]);
                            }
                        }
                    }
                }
            }
            //services wise
            if ($cliendefault == !null) {
                $wise = Serverservicetypewiseprice::get();
                foreach ($wise as $wi) {
                    echo '<hr>';
                    echo '---------------------services wise------------------------';
                    echo '<br>';
                    echo $wi->service_type;
                    echo '<br>';
                    echo 'Purchase Cost(Net) :' . $wi->purchase_cost;
                    if ($wi->adminplus_service == !null) {
                        echo '<br>';
                        echo 'Giá bán lẻ: ' . $wi->servicetypegroupprice->where('group_id',
                                $cliendefault->id)->first()->amount;
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        echo '<br>';
                        foreach ($wi->servicetypegroupprice as $groupprice) {
                            if ($groupprice->clientgroup == !null) {
                                echo 'Chiết khấu :' . $chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) * $groupprice->clientgroup->chietkhau));
                                echo '|' . $groupprice->clientgroup->group_name . '|' . $groupprice->id;
                                Serverservicetypewisegroupprice::where('id', $groupprice->id)
                                    ->update(['amount' => $chietkhau]);
                            }

                            echo '<br>';
                        }
                        echo '<hr>';
                    }
                }
            }
        }
        if ($type == 'supplier' && $id == !null) {
            //cập nhập phí+ tỉ giá
            $imeiprice = Imeiservicepricing::where('id_supplier', $id)->get();
            $supplier = Supplier::find($id);
            foreach ($imeiprice as $i) {
                if ($i->purchasecost == !null) {
                    $giatransactionfee = ($supplier->exchangerate * $i->purchasecost) / $this->exchangeRateStatic($exchangerate->currency_code) + (($i->purchasecost / 100) * $supplier->transactionfee);
                    Imeiservice::where('id', $i->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            }
            // cập nhập lại giá user
            $cliengroup = Clientgroup::get();
            //imei
            if ($cliendefault == !null) {
                $imei = Imeiservice::whereHas('imeipricing', function ($query) use ($id) {
                    $query->where('id_supplier', $id);
                })->get();
                foreach ($cliengroup as $clg) {
                    foreach ($imei as $i) {
                        $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id',
                            $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                        if ($imeiprice == !null) {
                            if ($i->purchase_cost > $i->credit + $imeiprice->discount) {
                                foreach ($currencies as $c) {
                                    Clientgroupprice::where('group_id', $cliendefault->id)
                                        ->where('service_type', 'imei')
                                        ->where('currency', $c->currency_code)
                                        ->where('service_id', $i->id)
                                        ->update(['discount' => ($i->purchase_cost - $i->credit) * $this->exchangeRateStatic($c->currency_code)]);
                                }
                            }

                            $giabanle = $i->credit + $imeiprice->discount;
                            $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                            $y = $chietkhau - $i->credit;
                            foreach ($currencies as $c) {
                                $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $c->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => $y * $this->exchangeRateStatic($c->currency_code)]);
                            }
                        }
                    }
                }
            }

            //services
            $this->UpdatePurchaseCostVipServer();
            if ($cliendefault == !null) {
                foreach ($cliengroup as $clg) {
                    $range = Serverserviceclientgroupcredit::where('client_group_id', $clg->id)->where('currency',
                        $currenciessite->config_value)->get();
                    foreach ($range as $ra) {
                        $i = Serverserviceclientgroupcredit::where('client_group_id', $cliendefault->id)
                            ->where('currency', $currenciessite->config_value)
                            ->where('server_service_range_id', $ra->server_service_range_id)
                            ->first();
                        $giabanle = $i->credit;
                        if ($ra->serverservicequantityrange->serverservicequantityrange == !null) {
                            $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) * $clg->chietkhau));
                            foreach ($currencies as $cu) {
                                $update = Serverserviceclientgroupcredit::where('server_service_range_id',
                                    $ra->server_service_range_id)
                                    ->where('client_group_id', $clg->id)
                                    ->where('currency', $cu->currency_code)
                                    ->update(['credit' => $chietkhau * $this->exchangeRateStatic($cu->currency_code)]);
                            }
                        }
                    }
                }
            }
            //services wise
            if ($cliendefault == !null) {
                $wise = Serverservicetypewiseprice::get();
                foreach ($wise as $wi) {
                    echo '<hr>';
                    echo '---------------------services wise------------------------';
                    echo '<br>';
                    echo $wi->service_type;
                    echo '<br>';
                    echo 'Purchase Cost(Net) :' . $wi->purchase_cost;
                    if ($wi->adminplus_service == !null) {
                        echo '<br>';
                        echo 'Giá bán lẻ: ' . $wi->servicetypegroupprice->where('group_id',
                                $cliendefault->id)->first()->amount;
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        echo '<br>';
                        foreach ($wi->servicetypegroupprice as $groupprice) {
                            if ($groupprice->clientgroup == !null) {
                                echo 'Chiết khấu :' . $chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) * $groupprice->clientgroup->chietkhau));
                                echo '|' . $groupprice->clientgroup->group_name . '|' . $groupprice->id;
                                Serverservicetypewisegroupprice::where('id', $groupprice->id)
                                    ->update(['amount' => $chietkhau]);
                            }

                            echo '<br>';
                        }
                        echo '<hr>';
                    }
                }
            }
        }
    }

    public function UpdatePurchaseCostVipServer()
    {
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $serverservicepricing = Serverservicepricing::get();
        foreach ($serverservicepricing as $sr) {
            if ($sr->id_supplier == !null) {
                $serverservice = Serverservice::find($sr->id);
                $tipurchasecost = $serverservice->servicepricing->nhacungcap->exchangerate;
                $transactionfeegd = $serverservice->servicepricing->nhacungcap->transactionfee;
                $exchangerategoc = $this->exchangeRateStatic($exchangerate->currency_code);
                if (!$serverservice->serverservicequantityrange->isEmpty()) {
                    if ($serverservice->api_id == !null) {
                        $purchasecost = $serverservice->apiserverservices->credits;
                        $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                        Serverservice::where('id', $sr->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                }
                if (!$serverservice->serverservicetypewiseprice->isEmpty()) {
                    if ($serverservice->api_id == !null) {
                        foreach ($serverservice->serverservicetypewiseprice as $a) {
                            foreach ($a->apiservicetypewisepriceid as $apiserverservicetypeprice) {
                                if ($apiserverservicetypeprice->id == $a->api_service_type_wise_price_id) {
                                    $purchasecost = $apiserverservicetypeprice->api_price;
                                }
                            }
                            $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                            Serverservicetypewiseprice::where('id',
                                $a->id)->update(['purchase_cost' => $giatransactionfee]);
                        }
                    }
                }
            }
        }
    }

    public function imeicheckapi()
    {
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $checkapi = Imeiservicepricing::get();
        foreach ($checkapi as $c) {
            if ($c->imei->api_id == !null) {
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->imei->apiserverservices->credits) / $this->exchangeRateStatic($exchangerate->currency_code) + (($c->imei->apiserverservices->credits / 100) * $c->nhacungcap->transactionfee);
                    $updategiatransactionfee = Imeiservice::where('id',
                        $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            } elseif ($c->imei->purchasecost == !null) {
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->purchasecost) / $this->exchangeRateStatic($exchangerate->currency_code) + (($c->purchasecost / 100) * $c->nhacungcap->transactionfee);
                    $updategiatransactionfee = Imeiservice::where('id',
                        $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            }
        }
        $checkenableapi = Imeiservice::where('pricefromapi', '<>', '0')->get();
        foreach ($checkenableapi as $ci) {
            $updateenableapi = Imeiservice::where('id', $ci->id)->update(['pricefromapi' => '0']);
        }
    }

    public static function exchangeRateStatic($currency_code)
    {
        $currency = Currencie::where('currency_code', $currency_code)->first();
        $default_currency = Config::where('config_var', 'site_default_currency')->first();

        $exchangeRateStatic = (double)($currency->exchange_rate_static);

        if ($currency_code == $default_currency->config_value) {
            $exchangeRateStatic = 1;
        }

        return $exchangeRateStatic;
    }

}
