<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Closure;

class QueuesUpdateDatabase
{
    public function handle(Request &$request, Closure &$next)
    {
        if($request->session()->has('queues_update_datatabse')) {
            Artisan::call('migrate', ['--force' => 1]);
            $request->session()->forget('queues_update_datatabse');
        }
    }
}