<?php

namespace App\Http\Controllers;


use App\Clientgroup;
use App\Currencie;
use App\Currenciepricing;
use App\Imeiservice;
use App\Imeiservicegroup;
use App\Supplier;
use Illuminate\Http\Request;

class Export
{
    public function exportimei(Request $request){
        //
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //

        $groupsearch = Imeiservicegroup::get();

        $usergroup = Clientgroup::where('status','active')->where('status', 'active')->orderBy('chietkhau')->get();
        $supplier =Supplier::get();
        $suppliersearch =$request->supplier;
        $cachesearch = $request;
        $currencies = Currencie::where('display_currency', 'Yes')->get();

        $group = Imeiservicegroup::orderBy('display_order','desc')->where('id','LIKE',$request->group_name)->get();
        if($request->type == 'api'){
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('imeipricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->where('api','>','0')
                ->get();
        }
        elseif($request->type == 'manual'){
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('imeipricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->where('api','')
                ->get();
        }else{
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('imeipricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->get();
        }
        return view('export.imeiexport', compact('imei_service', 'group', 'usergroup', 'exchangerate','groupsearch','supplier','cachesearch','currencies'));
    }
}