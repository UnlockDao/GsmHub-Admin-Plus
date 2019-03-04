<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Models\Imeiserviceorder;
use App\Models\Imeiservice;
use Illuminate\Http\Request;

class ImeiorderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $datefilter = $request->datefilter;
        $tg = explode(" - ", $datefilter);
        if ($request->view == null) {
            $view = 100;
        } else {
            $view = $request->view;
        }

        $updateby = Imeiserviceorder::groupBy('updated_by')->get();
        $cachesearch = $request;
        $groupsearch = Imeiservice::get();
        if ($request->service_name == !null && is_numeric($request->service_name)) {
            $nameimei = Imeiservice::find($request->service_name)->service_name;
        } else {
            $nameimei = '';
        }


        if ($datefilter == null) {
            $imeiorder = Imeiserviceorder::orderBy('id', 'desc')
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('user_name','LIKE', $request->user_name);
                })
                ->where('imei_service_id', 'LIKE', $request->service_name)
                ->where('status', 'LIKE', $request->status)
                ->where('updated_by', 'LIKE', $request->updated_by)
                ->paginate($view);
        } else {

            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);

            if($request->status == 'COMPLETED'){
                $imeiorder = Imeiserviceorder::orderBy('id', 'desc')
                    ->whereHas('user', function ($query) use ($request) {
                        $query->where('user_name','LIKE', $request->user_name);
                    })
                    ->where('imei_service_id', 'LIKE', $request->service_name)
                    ->where('status', 'LIKE', $request->status)
                    ->where('updated_by', 'LIKE', $request->updated_by)
                    ->whereBetween('completed_on', [$tg1, $tg2])
                    ->paginate($view);
            }else{
                $imeiorder = Imeiserviceorder::orderBy('id', 'desc')
                    ->whereHas('user', function ($query) use ($request) {
                        $query->where('user_name','LIKE', $request->user_name);
                    })
                    ->where('imei_service_id', 'LIKE', $request->service_name)
                    ->where('status', 'LIKE', $request->status)
                    ->where('updated_by', 'LIKE', $request->updated_by)
                    ->whereBetween('date_added', [$tg1, $tg2])
                    ->paginate($view);
            }


        }
        return view('order.imeiorder', compact('imeiorder', 'groupsearch', 'cachesearch', 'nameimei', 'updateby'));
    }


}
