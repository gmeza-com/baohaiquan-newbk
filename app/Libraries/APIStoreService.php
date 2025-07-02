<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Support\Facades\Storage;

class APIStoreService
{
    const SERVER = 'https://portal.cnv.vn/api/';
    public $client;
    public $cache;

    public function __construct(Client $client, CacheFactory $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @return void
     */
    public function checkForUpdateForCore()
    {
        //
    }

    public function getAllModules($page = 1)
    {
        return null;
    }

    public function getLinkDownloads($secretKey)
    {
        return false;
    }

    protected function buildUrlRequest($uri, $params = [])
    {
        $p = [];
        foreach($params as $param => $value)
        {
            $p[] = $param . '=' . urlencode($value);
        }
        $p = implode('&', $p);
        $url = static::SERVER . $uri . '?' . $p;

        return $url;
    }

    public function doInstallFreeModule($slug)
    {
        return false;
    }

    public function doInstallPremiumModule($secretKey)
    {
        return false;
    }

    protected function doInstallModule($url)
    {
        //
    }

    public function doUpdating($url, $version)
    {
        return false;
    }
}
