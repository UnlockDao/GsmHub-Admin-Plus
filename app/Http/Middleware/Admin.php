<?php
/**
 * Created by PhpStorm.
 * User: To Nguyen
 * Date: 12/22/2018
 * Time: 4:02 PM
 */

namespace App\Http\Middleware;


use App\Models\Administrator;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        $user_id = Auth::id();

        $admin = Administrator::where('user_id', $user_id)
            ->where('administrator_role_id', '>', '0')
            ->where('role_adminplus', 1)
            ->where('supplier_access', 'Admin')->first();

        if ($user_id == 1 || $admin) {
            return $next($request);
        }
        return redirect('/');
    }
}
