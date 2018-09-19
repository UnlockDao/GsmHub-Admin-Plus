<?php

namespace App\Http\Controllers\Auth;

use App\Administrator;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $users = User::where('user_name', $request->email)->where('user_access', 'Admin')->first();
        $admin = Administrator::where('user_id', $users->user_id)->where('administrator_role_id', '1')->first();
        if ($admin == !null) {
            if ($users == !null) {
                $hash = $users->bba_token;
            } else {
                $hash = '';
            }
            $user = User::where('user_name', $request->email)
                ->where('password', md5($request->password . $hash))
                ->first();

            if ($user == !null) {
                Auth::login($user);
                return redirect('/');
            } else {
                return redirect('/');
            }
        } else {
            $admin = Administrator::where('user_id', $users->user_id)->where('role_adminplus', '1')->first();
            if ($admin == !null) {
                if ($users == !null) {
                    $hash = $users->bba_token;
                } else {
                    $hash = '';
                }
                $user = User::where('user_name', $request->email)
                    ->where('password', md5($request->password . $hash))
                    ->first();

                if ($user == !null) {
                    Auth::login($user);
                    return redirect('/');
                } else {
                    return redirect('/');
                }
            } else {
                return redirect('/');
            }
        }
    }

    public function role()
    {
        $administrator = Administrator::get();
        return view('role',compact('administrator'));
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $imei = Administrator::find($id);
            $imei->role_adminplus = $par2;
            $imei->save();
            if ($par2 == '1') {
                echo 'Active';
            } else {
                echo 'Disable';
            }
            exit();
        }
        return;
    }
}