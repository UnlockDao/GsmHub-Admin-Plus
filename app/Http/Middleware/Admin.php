<?php
/**
 * Created by PhpStorm.
 * User: To Nguyen
 * Date: 12/22/2018
 * Time: 4:02 PM
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        $users = Auth::user()->admin;
        if(Auth::user()->is_super_admin !== 1 ){
            return redirect('home');
        }

        return $next($request);
    }
}