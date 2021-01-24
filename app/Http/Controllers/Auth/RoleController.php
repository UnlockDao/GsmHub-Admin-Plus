<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
    }

    public function supplier_access(Request $request)
    {
        $role = Administrator::find($request->id);
        $role->supplier_access = $request->value;
        $role->save();
        return $role;
    }
}
