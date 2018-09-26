<?php

namespace App\Http\Controllers;


use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Currenciepricing;
use App\Imeiservice;
use App\Serverservice;
use App\Serverserviceclientgroupcredit;
use App\Serverservicetypewisegroupprice;
use App\Serverservicetypewiseprice;
use App\Serviceservicepricing;
use App\Supplier;
use Illuminate\Http\Request;

class Utility
{
    public function __construct($type,$cliengroup,$supplier)
    {
        $this->type = $type;
        $this->cliengroup = $cliengroup;
        $this->supplier = $supplier;

        $this->Reload($type,$cliengroup,$supplier);
    }
    public function Reload($type,$cliengroup,$supplier)
    {

        if($cliengroup == null){
            $cliengroup =Clientgroup::get();
        }else{
            $cliengroup =Clientgroup::where('id',$cliengroup)->get();
        }
        if($supplier == null){
            $supplier =Supplier::get();
        }else{
            $supplier =Supplier::where('id',$supplier)->get();
        }

        $cliendefault = Clientgroup::where('status','active')->where('chietkhau', '0')->first();
        $currencies = Currencie::where('display_currency', 'Yes')->get();

        if($type == 'all'){
            //imei
            if ($cliendefault==! null) {
                $imei = Imeiservice::get();
                foreach ($cliengroup as $clg){
                    foreach ($imei as $i) {
                        $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id', $cliendefault->id)->where('currency', 'USD')->first();
                        if ($imeiprice == !null) {
                            $giabanle = $i->credit + $imeiprice->discount;
                            $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                            $y = $chietkhau - $i->credit;
                            foreach ($currencies as $c) {
                                $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $c->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => $y * $c->exchange_rate_static]);
                            }
                        }
                    }
                }
            }
            //services
            if($cliendefault ==! null){
                foreach ($cliengroup as $clg){
                    $range = Serverserviceclientgroupcredit::where('client_group_id',$clg->id)->where('currency','USD')->get();
                    foreach ($range as $ra) {
                        $i = Serverserviceclientgroupcredit::where('client_group_id',$cliendefault->id)
                            ->where('currency','USD')
                            ->where('server_service_range_id',$ra->server_service_range_id)
                            ->first();
                        $giabanle = $i->credit;
                        if($ra->serverservicequantityrange->serverservicequantityrange ==! null){
                            $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) *$clg->chietkhau));
                            foreach ($currencies as $cu) {
                                $update = Serverserviceclientgroupcredit::where('server_service_range_id',$ra->server_service_range_id)
                                    ->where('client_group_id', $clg->id)
                                    ->where('currency',$cu->currency_code)
                                    ->update(['credit' => $chietkhau* $cu->exchange_rate_static]);
                            }
                        }
                    }
                }
            }
        }
        if($type == 'imei'){
            //imei
            if ($cliendefault==! null) {
                $imei = Imeiservice::get();
                foreach ($cliengroup as $clg){
                    foreach ($imei as $i) {
                        $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id', $cliendefault->id)->where('currency', 'USD')->first();
                        if ($imeiprice == !null) {
                            $giabanle = $i->credit + $imeiprice->discount;
                            $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                            $y = $chietkhau - $i->credit;
                            foreach ($currencies as $c) {
                                $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $c->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => $y * $c->exchange_rate_static]);
                            }
                        }
                    }
                }
            }
        }
        if($type == 'server'){
            //services
            $this->UpdatePurchaseCostVipServer();
            if($cliendefault ==! null){
                foreach ($cliengroup as $clg){
                    $range = Serverserviceclientgroupcredit::where('client_group_id',$clg->id)->where('currency','USD')->get();
                    foreach ($range as $ra) {
                        $i = Serverserviceclientgroupcredit::where('client_group_id',$cliendefault->id)
                            ->where('currency','USD')
                            ->where('server_service_range_id',$ra->server_service_range_id)
                            ->first();
                        $giabanle = $i->credit;
                        if($ra->serverservicequantityrange->serverservicequantityrange ==! null){
                            $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) *$clg->chietkhau));
                            foreach ($currencies as $cu) {
                                $update = Serverserviceclientgroupcredit::where('server_service_range_id',$ra->server_service_range_id)
                                    ->where('client_group_id', $clg->id)
                                    ->where('currency',$cu->currency_code)
                                    ->update(['credit' => $chietkhau* $cu->exchange_rate_static]);
                            }
                        }
                    }
                }
            }
            //services wise
            if($cliendefault ==! null){
                $wise = Serverservicetypewiseprice::get();
                foreach($wise as $wi) {
                    echo '<hr>';
                    echo '---------------------services wise------------------------';
                    echo '<br>';
                    echo $wi->service_type;
                    echo '<br>';
                    echo 'Purchase Cost(Net) :'.$wi->purchase_cost;
                    if($wi->adminplus_service ==! null){
                        echo '<br>';
                        echo 'Giá bán lẻ: '.$wi->servicetypegroupprice->where('group_id',$cliendefault->id)->first()->amount;
                        $giabanle =$wi->servicetypegroupprice->where('group_id',$cliendefault->id)->first()->amount;
                        echo '<br>';
                        foreach ($wi->servicetypegroupprice as $groupprice){
                            if($groupprice->clientgroup ==! null){
                                echo 'Chiết khấu :'.$chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) *$groupprice->clientgroup->chietkhau));
                                echo '|'.$groupprice->clientgroup->group_name. '|'.$groupprice->id;
                                Serverservicetypewisegroupprice::where('id',$groupprice->id)
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
    public function UpdatePurchaseCostVipServer(){
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $serviceservicepricing = Serviceservicepricing::get();
        foreach ($serviceservicepricing as $sr){
            if($sr->id_supplier ==!null){
                $serverservice= Serverservice::find($sr->id);
                $tipurchasecost = $serverservice->servicepricing->nhacungcap->exchangerate;
                $transactionfeegd = $serverservice->servicepricing->nhacungcap->transactionfee;
                $exchangerategoc = $exchangerate->exchange_rate_static;
                if(!$serverservice->serverservicequantityrange->isEmpty()){
                    if($serverservice->api_id ==! null){
                        $purchasecost = $serverservice->apiserverservices->credits;
                        $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                        Serverservice::where('id', $sr->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                if(!$serverservice->serverservicetypewiseprice->isEmpty())
                    if($serverservice->api_id ==! null){
                        foreach($serverservice->serverservicetypewiseprice as $a){
                            foreach($serverservice->apiserverservicetypeprice as $apiserverservicetypeprice){
                                if($apiserverservicetypeprice->service_type==$a->service_type){
                                    $purchasecost=  $apiserverservicetypeprice->api_price;
                                }}
                            $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                            Serverservicetypewiseprice::where('id', $a->id)->update(['purchase_cost' => $giatransactionfee]);
                        }
                    }
                }
            }
        }
    }
}