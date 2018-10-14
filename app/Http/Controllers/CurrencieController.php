<?php

namespace App\Http\Controllers;


use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Currenciepricing;
use App\Http\Requests;
use App\Imeiservice;
use App\Imeiservicepricing;
use App\Supplier;
use Excel;
use Illuminate\Http\Request;

class CurrencieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currencie = Currencie::orderBy('display_currency')->get();
        return view('currency', compact('currencie'));
    }

    public function show($id)
    {
        $currencie = Currencie::find($id);
        return view('edit.editcurrencie', compact('currencie'));
    }

    public function edit($id, Request $request)
    {

        $currencie = Currencie::find($id);
        $currencie->exchange_rate_static = $request->exchange_rate_static;
        $currencie->save();
        return redirect('/currencie');
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $currencie = Currencie::find($id);
            $currencie->display_currency = $par2;
            $currencie->save();
            if ($par2 == 'Yes') {
                echo 'Active Currency ';
            } else {
                echo 'Disable Currency ';
            }
            exit();
        }
        return;
    }

    public function defaultcurrency($par = NULL, $par2 = NULL)
    {
        $id = $_GET['id'];
        if ($par2 == 'yes') {
            $defaultcurrency = Currenciepricing::where('type','1')->update(['currency_id' => $id]);
            echo 'Change Default Currency';
        }

        exit();
        return;
    }
}
