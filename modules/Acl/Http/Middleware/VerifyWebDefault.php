<?php

namespace Modules\Acl\Http\Middleware;

use App\Http\Middleware\QueuesUpdateDatabase;
use App\Libraries\APIStoreService;
use Closure;

class VerifyWebDefault
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // update service
        app()->make(APIStoreService::class)->checkForUpdateForCore();

        // checking update database
        (new QueuesUpdateDatabase())->handle($request, $next);

        if($request->exists('empty_cache')) {
            \Cache::flush();
        }

        if ($request->segment(1) === admin_path()) {
            // auto language vi redirect to admin
            session(['lang' => 'vi']);

            if(! auth()->check()) {
                return redirect('/auth');
            }
        }

        return $next($request);
    }
}
