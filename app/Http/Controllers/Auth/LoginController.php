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
        if ($users == !null) {
            $admin = Administrator::where('user_id', $users->user_id)->where('administrator_role_id', '!=', '0')->where('role_adminplus', '1')->first();
            if ($admin == !null) {
                $hash = $users->bba_token;
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
        } else {
            return redirect('/');
        }
    }

    public function role()
    {
        $administrator = Administrator::get();
        foreach ($administrator as $v) {
            if ($v->user_id == '1' || $v->user->is_super_admin == '1') {
                $role = Administrator::find($v->id);
                $role->role_adminplus = 1;
                $role->save();
            }
        }
        return view('role', compact('administrator'));
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $role = Administrator::find($id);
            $role->role_adminplus = $par2;
            $role->save();
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