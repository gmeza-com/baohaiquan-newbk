<?php
namespace Modules\Acl\Traits;

use Modules\Acl\Models\Role;

trait HasRoleAndPermission
{
    /**
     * Roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * is super admin
     * @param string $value
     * @return bool
     */
    public function getIsSuperAdminAttribute($value)
    {
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getIdAttribute($id)
    {
        if(! $id) {
            return 0;
        }

        return $id;
    }

    /**
     * Check has role
     * @param  string|\Illuminate\Database\Eloquent\Collection|\Modules\Acl\Models\Role  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        // if super admin, passed all
        if($this->is_super_admin) {
            return true;
        }
        // if $role is string
        if (is_string($role)) {
            return !! $this->roles()->where('slug', $role)->first();
        }
        // if $role is collection
        if (is_a($role, \Illuminate\Database\Eloquent\Collection::class)) {
            return ! $this->roles->intersect($role)->isEmpty();
        }
        // else
        return $this->roles->contains($role);
    }
}
