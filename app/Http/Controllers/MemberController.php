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
        $cache = $request;
        $member = User::where('user_name', 'LIKE', $request->username)
            ->where('user_status', 'LIKE', $request->userstatus)
            ->where('user_code', 'LIKE', $request->usercode)
            ->where('email', 'LIKE', $request->useremail)
            ->where('user_access', 'LIKE', $request->usertype)
            ->paginate(30);

        $chart = $this->chartstatus();
        return view('member', compact('member','chart','cache'));
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
