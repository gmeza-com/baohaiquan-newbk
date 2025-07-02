<?php
namespace Modules\Activity\Traits;

use Modules\Activity\Models\Activity;
use Illuminate\Contracts\Auth\Factory as Auth;

trait RecordsActivity
{
    /**
     *
     */
    protected static function bootRecordsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * @param $event
     */
    public function recordActivity($event)
    {
        Activity::create([
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'subject_name' => $this->name_on_logs,
            'subject_url' => $this->url_on_logs,
            'event' => $event,
            'user_id' => auth()->check() ? auth()->user()->id : 0,
            'ip' => request()->ip(),
            'user_agent'=> request()->header('User-Agent')
        ]);
    }

    /**
     * @return array
     */
    protected static function getModelEvents()
    {
        if (isset(static::$modelEvents)) {
            return static::$modelEvents;
        }

        return [
            'created', 'updated', 'deleted'
        ];
    }

    /**
     * Activity logs copy heres
     */

    /**
     * @param $value
     * @return string
     */
    public function getNameOnLogsAttribute($value)
    {
        return 'Unknow';
    }

    /**
     * @param $value
     * @return string
     */
    public function getUrlOnLogsAttribute($value)
    {
        return '';
    }
}