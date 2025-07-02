<?php

namespace Plugins\SEO\Traits;

use App\Libraries\Str;
use Plugins\SEO\Models\Seo;

trait Seoable
{
    public static function bootSeoable()
    {
        if(config('cnv.seo_plugin')) {
            // when to update update content
            static::saved(function ($model) {
                if(request()->has('seo')) {
                    $request = static::getRequestForLanguage(request()->all());
                    if ($model->seo) {
                        $model->seo->saveLanguages($request);
                    } else {
                        $seo = Seo::create([
                            'seoable_id' => $model->id,
                            'seoable_type' => get_class($model)
                        ]);
                        $request = static::getRequestForLanguage(request()->all());
                        $seo->saveLanguages($request);
                    }
                }
            });

            // when to delete, delete it
            static::deleting(function ($model) {
                $model->seo()->delete();
            });
        }
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public static function getRequestForLanguage($data, $languages = [])
    {
        $languages = $languages ? $languages : config('cnv.languages');
        foreach ($languages as $language) {
            if( ! @$data['seo']['language'][$language['locale']]['title']) {
                $data['seo']['language'][$language['locale']]['title'] = $data['language'][$language['locale']]['name'];
            }
            if( ! @$data['seo']['language'][$language['locale']]['description']) {
                $data['seo']['language'][$language['locale']]['description'] = $data['language'][$language['locale']]['description'];
            }
        }

        return $data['seo'];
    }
}