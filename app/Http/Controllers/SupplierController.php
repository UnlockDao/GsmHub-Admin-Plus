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

    public function checkSupplier()
    {
        $usersupplier = User::where('supplier_code','<>','0')->get();
        foreach ($usersupplier as $v) {
            $add = Supplier::firstOrCreate(['name'=>$v->user_id,'type'=>1]);

        }
        $usersupplierdel = Supplier::where('type', 1)->get();
        foreach ($usersupplierdel as $v) {
            $check = User::where('supplier_code','<>','0')->where('user_id', $v->name)->first();
            if ($check == null) {
               $del= Supplier::where('name', $v->id)->where('type', 1)->delete();
            }
        }
    }

    public function index(Request $request)
    {
//        $this->checkSupplier();
        $supplier = Supplier::get();
        $supplieruser = User::where('supplier_code','<>','0')->get();
        return view('setting.supplier', compact('supplier','supplieruser'));
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
        if($request->info) {
            $supplier->info = $request->info;
        }
        if($request->api_key) {
            $supplier->api_key = $request->api_key;
        }
        $supplier->save();
        //cập nhập phí+ tỉ giá
        $utility = new Utility();
        $utility->Repricing($type = 'supplier', $id);
        return redirect('/supplier');
    }

    public function quickedit(Request $request)
    {
        $supplier = Supplier::find($request->id);
        if($request->column =='name' && $supplier->type ==! 1) {
            $supplier->name = $request->editval;
        }
        if($request->column =='transactionfee') {
            $supplier->transactionfee = $request->editval;
            $utility = new Utility();
            $utility->Repricing($type = 'supplier', $request->id);
        }
        if($request->column =='exchangerate') {
            $supplier->exchangerate = $request->editval;
            $utility = new Utility();
            $utility->Repricing($type = 'supplier', $request->id);
        }
        if($request->column =='site_username'){
            $supplier->site_username = $request->editval;
        }
        if($request->column =='site_password') {
            $supplier->site_password = $request->editval;
        }
        if($request->column =='site_url') {
            $supplier->site_url = $request->editval;
        }
        if($request->column =='info') {
            $supplier->info = $request->editval;
        }
        if($request->column =='api_key') {
            $supplier->api_key = $request->editval;
        }
        $supplier->save();
        return $request;
    }

    public function add(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->transactionfee = $request->transactionfee;
        $supplier->exchangerate = $request->exchangerate;
        $supplier->site_username = $request->site_username;
        $supplier->site_password = $request->site_password;
        $supplier->site_url = $request->site_url;
        $supplier->info = $request->info;
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
