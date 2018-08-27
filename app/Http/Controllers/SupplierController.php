<?php

namespace App\Http\Controllers;


use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Http\Requests;
use App\Imeiservice;
use App\Imeiservicepricing;
use App\Supplier;
use Excel;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $supplier = Supplier::get();
        return view('supplier', compact('supplier'));
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return view('edit.editsupplier', compact('supplier'));
    }

    public function edit($id, Request $request)
    {
        $exchangerate = Currencie::where('currency_code','VND')->first();

        $supplier = Supplier::find($id);
        $supplier->transactionfee = $request->transactionfee;
        $supplier->exchangerate = $request->exchangerate;
        $supplier->save();
        //cập nhập phí+ tỉ giá
        $imeiprice = Imeiservicepricing::where('id_supplier',$id)->get();
        foreach ($imeiprice as $i){
            if($i->purchasecost ==! null){
                $giatransactionfee = ($request->exchangerate * $i->purchasecost) / $exchangerate->exchange_rate_static + (($i->purchasecost / 100) * $request->transactionfee);
                $updategiatransactionfee = Imeiservice::where('id', $i->id)->update(['purchase_cost' => $giatransactionfee]);
            }
        }
        $this->update();
        return redirect('/supplier');
    }

    public function add(Request $request){
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->transactionfee = $request->transactionfee;
        $supplier->exchangerate = $request->exchangerate;
        $supplier->save();
        return back();
    }

    public function update(){
        // cập nhập giá theo user
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $imei = Imeiservice::get();
        $client = Clientgroup::get();

        foreach ($imei as $i){
            $imeiprices = Clientgroupprice::where('service_id',$i->id)->where('group_id','19')->where('currency','USD')->first();
            if($imeiprices ==! null){
                $giabanle = $i->credit+$imeiprices->discount;
                foreach ($client as $c){
                    $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) *$c->chietkhau));
                    $y = $chietkhau-$i->credit;
                    foreach ($currencies as $cu) {
                        $b= $y*$cu->exchange_rate_static;
                        $updatepriceuse = Clientgroupprice::where('group_id', $c->id)
                            ->where('service_type', 'imei')
                            ->where('currency', $cu->currency_code)
                            ->where('service_id', $i->id)
                            ->update(['discount' => $b]);
                    }
                }
            }
        }
        return;
    }
}
