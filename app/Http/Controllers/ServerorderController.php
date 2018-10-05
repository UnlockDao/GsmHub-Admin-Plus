<?php

namespace App\Http\Controllers;

use App\Serverservice;
use App\Serverservicegroup;
use App\Serverserviceorder;
use Excel;
use Illuminate\Http\Request;

class ServerorderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $datefilter = $request->timein .' - '.$request->timeto;
        $tg=explode(" - ", $datefilter);

        $cachesearch =$request;
        $groupsearch = Serverservice::get();
        if($request->timein == null && $request->timeto == null){
        $serverorder = Serverserviceorder::orderBy('id','desc')
                                            ->where('server_service_id','LIKE',$request->service_name)
                                            ->where('status','LIKE',$request->status)
                                            ->paginate(15);
        }else{
            $serverorder = Serverserviceorder::orderBy('id','desc')
                ->where('server_service_id','LIKE',$request->service_name)
                ->where('status','LIKE',$request->status)
                ->whereBetween('date_added',$tg)
                ->paginate(5000);
        }
        return view('serverorder', compact('serverorder','groupsearch','cachesearch'));
    }


}
