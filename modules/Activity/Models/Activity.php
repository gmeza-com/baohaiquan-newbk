<?php
namespace Modules\Activity\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Activity extends Model
{
    protected $fillable = [
        'subject_id',
        'subject_type',
        'subject_name',
        'subject_url',
        'event',
        'user_id',
        'ip',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function getDescriptionAttribute($value)
    {
        $object = ucwords($this->object);
        $action = $this->event;

        return "{$this->getAttribute('link_on_logs')} has been {$action}";
    }

    public function getClassAttribute($value)
    {
        $class = 'label label-';
        switch ($this->event) {
            case 'created':
                $class .= 'success';
                break;
            case 'updated':
                $class .= 'info';
                break;
            case 'deleted':
                $class .= 'danger';
                break;
            default:
                $class .= 'primary';
                break;
        }

        return $class;
    }

    public function getCreatorAttribute($user)
    {
        if(! $this->user) {
            return 'Anonymous';
        }

        return link_to_route('admin.user.edit', $this->user->username, $this->user->id);
    }

    public function getLinkOnLogsAttribute($value)
    {
        if($this->subject_url) {
            return link_to($this->subject_url, $this->subject_name);
        }
        return $this->subject_name;
    }
}