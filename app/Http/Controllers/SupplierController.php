<?php

namespace App\Http\Controllers;

use App\Models\Imeiservicepricing;
use App\Models\Serviceservicepricing;
use App\Models\Supplier;
use App\User;
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
        $supplieruser = User::where('supplier_code','<>','0')->get();
        return view('supplier', compact('supplier','supplieruser'));
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return view('edit.editsupplier', compact('supplier'));
    }

    public function edit($id, Request $request)
    {
        $supplier = Supplier::find($id);
        $supplier->transactionfee = $request->transactionfee;
        $supplier->exchangerate = $request->exchangerate;
        if($request->site_username){
         $supplier->site_username = $request->site_username;
        }
        if($request->site_password) {
            $supplier->site_password = $request->site_password;
        }
        if($request->site_url) {
            $supplier->site_url = $request->site_url;
        }
        $supplier->save();
        //cập nhập phí+ tỉ giá
        $utility = new Utility();
        $utility->Repricing($type = 'supplier', $id);
        return redirect('/supplier');
    }

    public function add(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->transactionfee = $request->transactionfee;
        $supplier->exchangerate = $request->exchangerate;
        $supplier->save();
        return back();
    }


    public function delete($id)
    {
        Supplier::find($id)->delete();
        $imei = Imeiservicepricing::where('id_supplier', $id)->update(['id_supplier' => null]);
        $server = Serviceservicepricing::where('id_supplier', $id)->update(['id_supplier' => null]);
        return back();
    }
}
