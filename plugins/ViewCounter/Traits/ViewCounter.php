<?php

namespace Plugins\ViewCounter\Traits;

use App\Libraries\Str;
use Plugins\SEO\Models\Seo;
use Plugins\ViewCounter\Models\View;

trait ViewCounter
{
    public static function bootViewCounter()
    {
        static::created(function ($model) {
            View::create([
                'subject_id' => $model->id,
                'subject_type'=> get_class($model),
                'count' => 0
            ]);
         });

        static::deleting(function ($model) {
            $model->view()->delete();
        });
    }

    public function view()
    {
        return $this->morphOne(View::class, 'subject');
    }

    public function counting()
    {
        $this->view->increment('count');
    }
}