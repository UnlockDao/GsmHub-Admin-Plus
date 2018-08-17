<?php

namespace App\Http\Controllers;

use App\Imeiservice;
use App\Nhanvien;
use App\Phongban;
use Illuminate\Http\Request;
use Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $imei_service = Imeiservice::get();
        return view('home',compact('imei_service'));
    }
}
