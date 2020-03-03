<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function response($content = '', $status = 200, array $headers = [])
    {
        return response($content, $status, $headers);
    }

    protected function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }

    protected function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->jsonp($callback, $data, $status, $headers, $options);
    }

    protected function stream($callback, $status = 200, array $headers = [])
    {
        return response()->stream($callback, $status, $headers);
    }

    protected function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
    {
        return response()->streamDownload($callback, $name, $headers, $disposition);
    }

    protected function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        return response()->download($file, $name, $headers, $disposition);
    }

    protected function file($file, array $headers = [])
    {
        return response()->file($file, $headers);
    }

    protected function render($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }

    protected function noContent($status = 204, array $headers = [])
    {
        return response()->noContent($status, $headers);
    }

    protected function redirectTo($to = null, $status = 302, $headers = [], $secure = null)
    {
        return redirect($to, $status, $headers, $secure);
    }

    protected function redirectToRoute($route, $parameters = [], $status = 302, $headers = [])
    {
        return redirect()->route($route, $parameters, $status, $headers);
    }

    protected function redirectGuest($path, $status = 302, $headers = [], $secure = null)
    {
        return redirect()->guest($path, $status, $headers, $secure);
    }

    protected function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        return redirect()->intended($default, $status, $headers, $secure);
    }

    protected function redirectBack($status = 302, $headers = [], $fallback = false)
    {
        return back($status, $headers, $fallback);
    }

    protected function flash($message = null, $level = 'info')
    {
        return flash($message, $level);
    }
}
