<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Imeiservice;
use App\Nhanvien;
use App\Phongban;
use App\Serverserviceclientgroupcredit;
use Excel;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $client = Clientgroup::get();
        return view('clientgroup', compact('client'));
    }

    public function show($id)
    {
        $client = Clientgroup::find($id);
        return view('edit.editclientgroup',compact('client'));
    }

    public function edit($id,Request $request)
    {

        $client = Clientgroup::find($id);
        $client->chietkhau= $request->chietkhau;
        $client->save();

        //cập nhập tất cả chiết khấu
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $imei = Imeiservice::get();
        $cliendefault = Clientgroup::where('chietkhau', '0')->first();
        if ($cliendefault==! null){
            foreach ($imei as $i){
                $imeiprice = Clientgroupprice::where('service_id',$i->id)->where('group_id',$cliendefault->id)->where('currency','USD')->first();
                if($imeiprice ==! null){
                $giabanle = $i->credit+$imeiprice->discount;
                $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) *$request->chietkhau));
                $y = $chietkhau-$i->credit;
                    foreach ($currencies as $c) {
                        $updatepriceuse = Clientgroupprice::where('group_id', $id)
                            ->where('service_type', 'imei')
                            ->where('currency', $c->currency_code)
                            ->where('service_id', $i->id)
                            ->update(['discount' => $y * $c->exchange_rate_static]);
                    }
                }

            }
        }
        $cliendefault = Clientgroup::where('chietkhau', '0')->first();
        if($cliendefault ==! null){
            $range = Serverserviceclientgroupcredit::where('client_group_id',$id)->where('currency','USD')->get();
            $currencies = Currencie::where('display_currency', 'Yes')->get();
            foreach ($range as $ra) {
                $i = Serverserviceclientgroupcredit::where('client_group_id',$cliendefault->id)
                    ->where('currency','USD')
                    ->where('server_service_range_id',$ra->server_service_range_id)
                    ->first();
                $giabanle = $i->credit;
                $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) *$request->chietkhau));
                foreach ($currencies as $cu) {
                        $update = Serverserviceclientgroupcredit::where('server_service_range_id',$ra->server_service_range_id)
                            ->where('client_group_id', $id)
                            ->where('currency',$cu->currency_code)
                            ->update(['credit' => $chietkhau* $cu->exchange_rate_static]);
                }

            }
        }

        return redirect('/clientgroup');
    }
}
