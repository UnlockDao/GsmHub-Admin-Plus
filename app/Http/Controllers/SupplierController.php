<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Supplier;
use Excel;
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
        return view('supplier', compact('supplier'));
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return view('edit.editsupplier', compact('supplier'));
    }

    public function edit($id, Request $request)
    {

        $supplier = Supplier::find($id);
        $supplier->phi = $request->phi;
        $supplier->tigia = $request->tigia;
        $supplier->save();
        return redirect('/supplier');
    }

}
