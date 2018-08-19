<?php

namespace App\Http\Controllers;

use App\Clientgroup;
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
        return redirect('/clientgroup');
    }
}
