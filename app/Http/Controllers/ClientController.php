<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Imeiservice;
use App\Nhanvien;
use App\Phongban;
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
        foreach ($imei as $i){
            $imeiprice = Clientgroupprice::where('service_id',$i->id)->where('group_id','19')->where('currency','USD')->first();
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
        return redirect('/clientgroup');
    }
}
