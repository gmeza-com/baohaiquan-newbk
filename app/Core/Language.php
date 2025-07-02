<?php

namespace App\Core;

use Carbon\Carbon;

class Language
{
    public $languages = [];

    public function __construct()
    {
        $this->languages = config('cnv.languages');
        $this->setupCurrentLanguage();
    }

    public function init()
    {
        app()->setLocale(app('session')->get('lang'));

        Carbon::setLocale(app('session')->get('lang'));
        date_default_timezone_set($this->getKey('timezone', $this->getPosition()));

        view()->share('languages', app('language')->getLanguages());
        view()->share('current_language', app('language')->getCurrentLanguage());
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function getKey($key = "locale", $position = 0)
    {
        return $this->languages[$position][$key];
    }

    public function getPosition($lang = null)
    {
        $lang = $lang ?: app('session')->get('lang');
        return array_search(app('session')->get('lang'), array_column($this->languages, 'locale'));
    }

    public function setupCurrentLanguage()
    {
        if (!app('session')->has('lang')) {
            app('session')->put('lang', $this->languages[$this->getPosition(
                config('cnv.language_default')
            )]['locale']);
        }
    }

    public function getCurrentLanguage()
    {
        return app('session')->get('lang');
    }
}