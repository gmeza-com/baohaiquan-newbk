<?php
namespace Modules\Acl\Models;

use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Role extends Model
{
    use ModelLanguages;

    protected $fillable = [
        'slug',
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission'
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function languages()
    {
        return $this->hasMany(RoleLanguage::class);
    }
}
