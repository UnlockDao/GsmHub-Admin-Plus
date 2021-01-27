<?php

namespace App\Http\Controllers;

use App\Models\Imeiservice;
use App\Models\Imeiservicepricing;
use App\Models\Serverservice;
use App\Models\Serverservicepricing;
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
        $usersupplier = User::where('supplier_code', '<>', '0')->get();
        foreach ($usersupplier as $v) {
            $add = Supplier::firstOrCreate(['name' => $v->user_id, 'type' => 1]);
        }
        $usersupplierdel = Supplier::where('type', 1)->get();
        foreach ($usersupplierdel as $v) {
            $check = User::where('supplier_code', '<>', '0')->where('user_id', $v->name)->first();
            if ($check == null) {
                $del = Supplier::where('name', $v->id)->where('type', 1)->delete();
            }
        }
    }

    public function index(Request $request)
    {
//        $this->checkSupplier();
        $supplier = Supplier::get();
        $supplieruser = User::where('supplier_code', '<>', '0')->get();
        return view('setting.supplier', compact('supplier', 'supplieruser'));
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
        if ($request->site_username) {
            $supplier->site_username = $request->site_username;
        }
        if ($request->site_password) {
            $supplier->site_password = $request->site_password;
        }
        if ($request->site_url) {
            $supplier->site_url = $request->site_url;
        }
        if ($request->info) {
            $supplier->info = $request->info;
        }
        if ($request->api_key) {
            $supplier->api_key = $request->api_key;
        }
        if ($request->api_server_details_id) {
            $supplier->api_server_details_id = $request->api_server_details_id;
        }
        $supplier->save();

        if ($supplier->api_server_details_id != null && $supplier->api_server_details_id != "") {
            $this->updateApiServiceSupplier($id);
        }

        //cập nhập phí+ tỉ giá
        $utility = new Utility();
        $utility->Repricing($type = 'supplier', $id);
        return redirect('/supplier');
    }

    public function quickedit(Request $request)
    {
        $supplier = Supplier::find($request->id);
        if ($request->column == 'name' && $supplier->type == !1) {
            $supplier->name = $request->editval;
        }
        if ($request->column == 'transactionfee') {
            $supplier->transactionfee = $request->editval;
            $utility = new Utility();
            $utility->Repricing($type = 'supplier', $request->id);
        }
        if ($request->column == 'exchangerate') {
            $supplier->exchangerate = $request->editval;
            $utility = new Utility();
            $utility->Repricing($type = 'supplier', $request->id);
        }
        if ($request->column == 'site_username') {
            $supplier->site_username = $request->editval;
        }
        if ($request->column == 'site_password') {
            $supplier->site_password = $request->editval;
        }
        if ($request->column == 'site_url') {
            $supplier->site_url = $request->editval;
        }
        if ($request->column == 'info') {
            $supplier->info = $request->editval;
        }
        if ($request->column == 'api_key') {
            $supplier->api_key = $request->editval;
        }
        if ($request->column == 'api_server_details_id') {
            $supplier->api_server_details_id = $request->editval;
        }
        $supplier->save();

        if ($supplier->api_server_details_id != null && $supplier->api_server_details_id != "") {
            $this->updateApiServiceSupplier($request->id);
        }

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
        $supplier->api_server_details_id = $request->api_server_details_id;
        $supplier->save();

        if ($supplier->api_server_details_id != null && $supplier->api_server_details_id != "") {
            $this->updateApiServiceSupplier($supplier->id);
        }

        return back();
    }


    public function delete($id)
    {
        Supplier::find($id)->delete();
        $imei = Imeiservicepricing::where('id_supplier', $id)->update(['id_supplier' => null]);
        $server = Serverservicepricing::where('id_supplier', $id)->update(['id_supplier' => null]);
        return back();
    }

    public function updateApiServiceSupplier($supplierId = null)
    {
        if ($supplierId == null) {
            $supplier = Supplier::get(['id', 'name', 'site_url', 'api_server_details_id']);
        } else {
            $supplier = Supplier::where('id', $supplierId)->get(['id', 'name', 'site_url', 'api_server_details_id']);
        }

//        foreach ($supplier as $sp) {
//            $apis = explode(',', $sp->api_server_details_id);
//            $sp->api_server_details_id = $apis;
//        }

        foreach ($supplier as $sp) {
            $supplier_id = $sp->id;
            $apis = explode(',', $sp->api_server_details_id);
            $imeiServices = collect();
            $serverServices = collect();
            foreach ($apis as $a) {
                $imeiServices = $imeiServices->merge(Imeiservice::where('api', $a)->get([
                    'id',
                    'service_name',
                    'api',
                    'api_id'
                ]));

                $serverServices = $serverServices->merge(Serverservice::where('api', $a)->get([
                    'id',
                    'service_name',
                    'api',
                    'api_id'
                ]));
            }

            foreach ($imeiServices as $i) {
                $imei = Imeiservicepricing::find($i->id);
                $imei->id_supplier = $supplier_id;
                $imei->save();
            }

            foreach ($serverServices as $s) {
                $server = Serverservicepricing::find($s->id);
                $server->id_supplier = $supplier_id;
                $server->save();
            }
        }
    }
}
