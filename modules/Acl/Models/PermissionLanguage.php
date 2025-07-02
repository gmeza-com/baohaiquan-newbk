<?php
namespace Modules\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionLanguage extends Model
{
    protected $fillable = [
        'locale', 'description', 'permission_id'
    ];
    public $timestamps = false;

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
