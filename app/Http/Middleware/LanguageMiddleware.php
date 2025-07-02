<?php

namespace App\Http\Middleware;

use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            $language = $request->get('lang');
            $languagesAvaiable = config('cnv.languages');

            foreach ($languagesAvaiable as $lang) {
                if ($lang['locale'] == $language) {
                    app('session')->put('lang', $language);
                    break;
                }
            }
        }

        $currentLang = session('lang');
        app('language')->init();
        $response =  $next($request);
        $afterLang = session('lang');

        // first access page does not have current language
        if($currentLang !== $afterLang && $currentLang) {
            return redirect()->refresh();
        }

        return $response;
    }
}
