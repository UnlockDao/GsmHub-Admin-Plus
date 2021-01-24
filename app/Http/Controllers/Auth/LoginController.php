<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /** @var string */
    protected $redirectTo = "/";

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
