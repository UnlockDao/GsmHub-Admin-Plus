<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $member = User::paginate(30);

        $chart = $this->chartstatus();
        return view('member', compact('member','chart'));
    }

    public function chartstatus(){
        $return_arr = [];
        $return_arr['newregister'] = User::where('activated','0')->count();
        $return_arr['active'] = User::where('activated','1')->count();
        $return_arr['block'] = User::where('user_status','Locked')->count();
        $return_arr['subscribe_newsletter'] = User::where('subscribe_newsletter','1')->count();

        return $return_arr;
    }
}
