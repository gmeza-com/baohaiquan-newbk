<?php
namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemLanguage extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'value',
        'menu_item_id'
    ];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(MenuItem::class);
    }
}