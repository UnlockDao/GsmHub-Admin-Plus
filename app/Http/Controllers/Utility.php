<?php

namespace App\Http\Controllers;


use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Imeiservice;
use App\Serverserviceclientgroupcredit;
use App\Serverservicetypewisegroupprice;
use App\Serverservicetypewiseprice;
use App\Supplier;

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
}