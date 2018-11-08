<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $to_name = 'To Nguyen';
        $to_email = 'nguyentb@s-developers.com';
        $data = array('name' => "Sam Jose", "body" => "Test mail");

        Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Artisans Web Testing Mail');
            $message->from('admin@mccorp.vn', 'Test');
        });
        echo 'send';
    }

}
