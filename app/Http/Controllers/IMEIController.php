<?php

namespace App\Http\Controllers;

use App\Models\Clientgroup;
use App\Models\Clientgroupprice;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Imeiservice;
use App\Models\Imeiservicegroup;
use App\Models\Imeiservicepricing;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class IMEIController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        View::composer('services.imei.*', function ($view) {
            $defaultcurrency = Currenciepricing::where('type', '1')->first();
            $view->with('currenciesSite', Config::where('config_var', 'site_default_currency')->first());
            $view->with('imeiGroup', Imeiservicegroup::get());
            $view->with('userGroup', Clientgroup::where('status', 'active')->where('status', 'active')->orderBy('chietkhau')->get());
            $view->with('clienDefault', Clientgroup::where('status', 'active')->where('chietkhau', '0')->first());
            $view->with('supplier', Supplier::get());
            $view->with('allCurrencies', Currencie::get());
            $view->with('exchangeRate', Currencie::find($defaultcurrency->currency_id));
        });
    }

    public function index(Request $request)
    {
        $group = Imeiservicegroup::orderBy('display_order', 'desc')->where('id', 'LIKE', $request->group_name)->get();
        $service = Imeiservice::orderBy('service_name');
        $service->where('status', 'LIKE', $request->status ?? 'active');
        if ($request->type == 'api') {
            $service->where('api', '>', '0');
        }
        if ($request->type == 'manual') {
            $service->where('api', '');
        }
        if ($request->supplier) {
            $service->whereHas('imeipricing', function ($query) use ($request) {
                $query->where('id_supplier', 'LIKE', $request->supplier);
            });
        }
        $imeiService = $service->get();
        return $this->render('services.imei.index', [
            'imeiService' => $imeiService,
            'cachesearch' => $request,
            'group' => $group,
        ]);
    }

    public function edit($id)
    {
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $imeiService = Imeiservice::find($id);
        $imei = Imeiservicepricing::find($id);
        $pricegroup = Clientgroupprice::orderBy('group_id', 'desc')->where('currency', $currenciessite->config_value)->where('service_type', 'imei')->where('service_id', $id)->get();


        return $this->render('services.imei.edit', [
            'imeiService' => $imeiService,
            'imei' => $imei,
            'pricegroup' => $pricegroup,
        ]);
    }


    public function update($id, Request $request)
    {
        Imeiservice::findOrFail($id)->update($request->input());
        Imeiservicepricing::findOrFail($id)->update($request->input());

        $groupPrices = $request->input('groupPrice');
        $existsPriceIds = [];
        if (is_array($groupPrices)) {
            foreach ($groupPrices as $price) {
                if (@$price['id']) {
                    $existsPriceIds[] = $idPrice = $price['id'];
                    $modelOption = Clientgroupprice::findOrFail($idPrice);
                    $modelOption->update([
                        'discount' => $price['price'] - $request['credit'],
                    ]);
                }
            }
        }

        return back()->with('msg', 'Update Price Sucessfuly!');
    }

    public function runStart()
    {
        $this->checkNullUser();
        $this->checkdelete();
        $this->checkimei();
        $this->checkapi();
    }


    public function checkNullUser()
    {
        $cliengroup = Clientgroup::where('status', 'active')->get();
        $imeiservices = Imeiservice::get();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        foreach ($imeiservices as $imei) {
            foreach ($cliengroup as $cg) {
                foreach ($currencies as $cu) {
                    $check = Clientgroupprice::where('group_id', $cg->id)
                        ->where('service_id', $imei->id)
                        ->where('service_type', 'imei')
                        ->where('currency', $cu->currency_code)
                        ->first();
                    if ($check == null) {
                        $create = Clientgroupprice::firstOrCreate(['group_id' => $cg->id,
                            'service_id' => $imei->id,
                            'service_type' => 'imei',
                            'currency' => $cu->currency_code]);
                    }
                }
            }
        }
    }

    public function checkdelete()
    {
        $imei_services = Imeiservicepricing::get();
        foreach ($imei_services as $v) {
            $check = Imeiservice::where('id', $v->id)->first();
            if ($check == null) {
                Imeiservicepricing::where('id', $v->id)->delete();
            }
        }
    }

    public function checkimei()
    {
        $imei_services = Imeiservice::orderBy('id')->get();
        foreach ($imei_services as $v) {
            $add = Imeiservicepricing::firstOrCreate(['id' => $v->id]);
        }
    }

    public function checkapi()
    {
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $checkapi = Imeiservicepricing::get();
        foreach ($checkapi as $c) {
            if ($c->imei->api_id == !null) {
                if ($c->nhacungcap == !null) {
                    if ($c->imei->apiserverservices) {
                        $giatransactionfee = ($c->nhacungcap->exchangerate * $c->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($c->imei->apiserverservices->credits / 100) * $c->nhacungcap->transactionfee);
                        $updategiatransactionfee = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                }
            } elseif ($c->imei->purchasecost == !null) {
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->purchasecost) / $exchangerate->exchange_rate_static + (($c->purchasecost / 100) * $c->nhacungcap->transactionfee);
                    $updategiatransactionfee = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            }
        }
        $checkenableapi = Imeiservice::where('pricefromapi', '<>', '0')->get();
        foreach ($checkenableapi as $ci) {
            $updateenableapi = Imeiservice::where('id', $ci->id)->update(['pricefromapi' => '0']);
        }
    }



    public function updatesupplier($id, Request $request)
    {

        $imei = Imeiservicepricing::find($id);
        $imei->id_supplier = $request->id_supplier;
        $imei->save();

        //tính giá + phí
        $exchangerate = Currencie::where('currency_code', 'VND')->first();
        $imeiprice = Imeiservicepricing::where('id_supplier', $request->id_supplier)->find($id);
        if ($imeiprice->imei->api_id == !null && $imeiprice->imei->apiserverservices == !null) {
            $giatransactionfee = ($imeiprice->nhacungcap->exchangerate * $imeiprice->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($imeiprice->imei->apiserverservices->credits / 100) * $imeiprice->nhacungcap->transactionfee);
            $updategiatransactionfee = Imeiservice::where('id', $id)->update(['purchase_cost' => $giatransactionfee]);
        }


        return back();
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $imei = Imeiservice::find($id);
            $imei->status = $par2;
            $imei->save();
            if ($par2 == 'active') {
                echo 'Active IMEI Services';
            } else {
                echo 'Disable IMEI Services';
            }
            exit();
        }
        return;
    }

    public function delete($id)
    {
        Imeiservice::find($id)->delete();
        return back();
    }

}
