<?php
namespace Modules\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class RoleLanguage extends Model
{
    protected $fillable = [
        'locale', 'name', 'description', 'role_id'
    ];
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
