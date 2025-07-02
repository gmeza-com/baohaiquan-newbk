<?php
namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Traits\RecordsActivity;

class Menu extends Model
{
    use RecordsActivity;

    protected $with = [
        'items'
    ];

    protected $fillable = [
        'slug',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function getNameOnLogsAttribute($value)
    {
        return $this->slug;
    }

    /**
     * Activity logs
     */
    public function getUrlOnLogsAttribute($value)
    {
        return route('admin.menu.index');
    }
}