<?php

namespace App;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class CUtil
{

    //role admin plus

    public static function apAdmin()
    {
        $users = Auth::user()->admin;
        if($users->supplier_access == 'Admin'){
            return true;
        }else{
            return false;
        }
    }

    public static function apServiceManager()
    {
        $users = Auth::user()->admin;
        if($users->supplier_access == 'ServiceManager'){
            return true;
        }else{
            return false;
        }
    }

    public static function apStaff()
    {
        $users = Auth::user()->admin;
        if($users->supplier_access == 'Staff'){
            return true;
        }else{
            return false;
        }
    }
    //end
    public static function checkauth()
    {
         $users = Auth::user()->admin;
         if($users->administrator_role_id == 1 && Auth::user()->is_super_admin == 1 ){
             return true;
         }else{
             return false;
         }
    }
    public static function issuperadmin()
    {
        if(Auth::user()->is_super_admin == 1 ){
            return true;
        }else{
            return false;
        }
    }

    public static function convertDate($date, $format, $date_format = 'Y-m-d H:i:s')
    {
        if (!$date || '0000-00-00' === $date || '0000-00-00 00:00:00' === $date || '1970-01-01 00:00:00' === $date || '0000-01-01 00:00:00' === $date) {
            return '';
        }
        $input_timezone = 'UTC';
        $user_timezone = '';
        if ('' === $user_timezone) {
            $user_timezone = 'asia/ho_chi_minh';
        }

        if ('' !== $user_timezone) {
            $utc_time = $date;
            $date_obj = new DateTime($utc_time, new DateTimeZone($input_timezone));
            $date_obj->setTimezone(new DateTimeZone($user_timezone));

            return $date_obj->format($format);
        }

        return DateTime::createFromFormat($date_format, $date)->format($format);
    }

    public static function convertDateS($date, $date_format = 'Y-m-d H:i:s')
    {
        if (!$date || '0000-00-00' === $date || '0000-00-00 00:00:00' === $date || '1970-01-01 00:00:00' === $date || '0000-01-01 00:00:00' === $date) {
            return '';
        }
        $input_timezone = 'asia/ho_chi_minh';
        $user_timezone = '';
        if ('' === $user_timezone) {
            $user_timezone = 'UTC';
        }

        if ('' !== $user_timezone) {
            $utc_time = $date;
            $date_obj = new DateTime($utc_time, new DateTimeZone($input_timezone));
            $date_obj->setTimezone(new DateTimeZone($user_timezone));

            return $date_obj->format('Y-m-d H:i:s');
        }

        return DateTime::createFromFormat($date_format, $date)->format($format);
    }
}