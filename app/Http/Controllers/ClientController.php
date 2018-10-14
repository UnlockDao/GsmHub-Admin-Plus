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

        $type ='all';
        $cliengroup= $id;
        $supplier='';
        $utility = new Utility();
        $utility->Reload($type,$cliengroup,$supplier);

        return redirect('/clientgroup');
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $imei = Clientgroup::find($id);
            $imei->status = $par2;
            $imei->save();
            if ($par2 == 'active') {
                echo 'Active ';
            } else {
                echo 'Disable';
            }
            exit();
        }
        return;
    }
    public function delete($id)
    {
        Clientgroup::find($id)->delete();
        return back();
    }
}
