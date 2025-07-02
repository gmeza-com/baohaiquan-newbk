<?php
namespace Modules\Acl\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use ModelLanguages;

    protected $fillable = [
        'slug',
        'module'
    ];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission'
        );
    }

    public function languages()
    {
        return $this->hasMany(PermissionLanguage::class);
    }
}
