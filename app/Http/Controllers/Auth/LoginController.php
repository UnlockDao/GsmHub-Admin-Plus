<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
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
        $col = 'user_name';
        if ($this->checkEmail($request->email)) {
            $col = 'email';
        }
        $users = User::where($col, $request->email)->where('user_access', 'Admin')->first();
        if ($users == !null) {
            $admin = Administrator::where('user_id', $users->user_id)->where('administrator_role_id', '!=',
                '0')->where('role_adminplus', '1')->first();

            if ($admin == !null) {
                $md5passwd = md5($request->password . $users->bba_token);
                if ($md5passwd == $users->password) {
                    Auth::login($users);
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

    public function checkEmail($email): bool
    {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false && $find2 > $find1);
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
        return view('setting.role', compact('administrator'));
    }

    public function status($par = null, $par2 = null)
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


    public function supplier_access(Request $request)
    {
        $role = Administrator::find($request->id);
        $role->supplier_access = $request->value;
        $role->save();
        return $role;
    }
}
