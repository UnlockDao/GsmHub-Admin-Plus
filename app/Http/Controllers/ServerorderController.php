<?php

namespace App\Http\Controllers;

use App\CUtil;
use App\Models\Serverservice;
use App\Models\Serverserviceorder;
use DateTime;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ServerorderController extends Controller
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

        $updateby = Serverserviceorder::groupBy('updated_by')->get();
        $cachesearch = $request;
        $groupsearch = Serverservice::get();
        if ($request->service_name == !null && is_numeric($request->service_name)) {
            $nameserver = Serverservice::find($request->service_name)->service_name;
        } else {
            $nameserver = '';
        }


        if ($datefilter == null) {
            $serverorder = Serverserviceorder::orderBy('id', 'desc')
                ->where('server_service_id', 'LIKE', $request->service_name)
                ->where('status', 'LIKE', $request->status)
                ->where('updated_by', 'LIKE', $request->updated_by)
                ->paginate($view);
        } else {
            $tg1 = CUtil::convertDateS($tg[0]);
            $tg2 = CUtil::convertDateS($tg[1]);

            if ($request->status == 'COMPLETED') {
                $serverorder = Serverserviceorder::orderBy('id', 'desc')
                    ->where('server_service_id', 'LIKE', $request->service_name)
                    ->where('status', 'LIKE', $request->status)
                    ->where('updated_by', 'LIKE', $request->updated_by)
                    ->whereBetween('completed_on', [$tg1, $tg2])
                    ->paginate($view);
            } else {
                $serverorder = Serverserviceorder::orderBy('id', 'desc')
                    ->where('server_service_id', 'LIKE', $request->service_name)
                    ->where('status', 'LIKE', $request->status)
                    ->where('updated_by', 'LIKE', $request->updated_by)
                    ->whereBetween('date_added', [$tg1, $tg2])
                    ->paginate($view);
            }

        }
        return view('serverorder', compact('serverorder', 'groupsearch', 'cachesearch', 'nameserver', 'updateby'));
    }

    public function show($id)
    {
        $serverorder = Serverserviceorder::find($id);
        return view('edit.editserverorder',compact('serverorder'));
    }

    public function edit($id,Request $request)
    {
        $serverorder = Serverserviceorder::find($id);
        $serverorder->status = $request->status;
        $serverorder->completed_on = new DateTime();
        $serverorder->save();
        return back();
    }


}
