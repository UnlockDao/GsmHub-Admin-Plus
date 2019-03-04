<?php

namespace App\Http\Controllers;

use App\Models\Clientgroup;
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
        return view('setting.clientgroup', compact('client'));
    }

    public function show($id)
    {
        $client = Clientgroup::find($id);
        return view('edit.editclientgroup', compact('client'));
    }

    public function edit($id, Request $request)
    {

        $client = Clientgroup::find($id);
        $client->chietkhau = $request->chietkhau;
        $client->save();

        //cập nhập tất cả chiết khấu
        $utility = new Utility();
        $utility->Repricing($type = 'clientgroup', $id);

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
