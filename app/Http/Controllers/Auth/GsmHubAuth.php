<?php


namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Administrator;

trait GsmHubAuth
{

    public function userAuthGsmHub(array $credentials = [], $mustBeAdmin = false)
    {
        $username = $credentials['email'];
        $password = $credentials['password'];

        $col = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        $user = User::where($col, $username)->first();

        $checkPasswd = false;
        $isAdmin = false;

        if ($user) {
            $checkPasswd = md5($password . $user->bba_token) == $user->password;
        } else {
            return null;
        }

        if ($mustBeAdmin) {
            $admin = $this->isAdmin($user->user_id);
            if ($checkPasswd && $admin) {
                return $user;
            } else {
                return null;
            }
        } else {
            if ($checkPasswd) {
                return $user;
            } else {
                return null;
            }
        }
    }

    public function isAdmin($user_id)
    {
        $admin = Administrator::where('user_id', $user_id)
            ->where('administrator_role_id', '>', '0')
            ->where('role_adminplus', 1)->first();

        if ($admin->user->user_access == 'Admin') {
            return true;
        } else {
            return false;
        }
    }

}

