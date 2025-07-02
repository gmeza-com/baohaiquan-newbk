<?php

namespace Modules\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Acl\Traits\HasRoleAndPermission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Acl\Models\Role;
use Modules\Activity\Models\Activity;
use Modules\Activity\Traits\RecordsActivity;

class User extends Authenticatable
{
    use Notifiable, HasRoleAndPermission, RecordsActivity;

    protected static $modelEvents = ['created', 'deleted'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'token', 'setting', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

    protected $casts = [
        'setting' => 'array',
        'activated' => 'boolean'
    ];

    /**
     * Avatar
     * @param $avatar
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        return $this->getSetting('avatar') ?
            asset('/storage/avatars/' . $this->getSetting('avatar')) :
            asset('assets/images/placeholder.png');
    }

    public function getSetting($name)
    {
        return isset($this->setting[$name]) ? $this->setting[$name] : '';
    }

    /**
     * Setting up data
     * @param $request
     * @return array
     */
    public function readyProfile($request)
    {
        $data = $request->all();
        $data['setting'] = array_merge($this->setting ? $this->setting : [], $data['setting']);

        // change password
        if(! $data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        // status
        if(! isset($data['activated'])) {
            $data['activated'] = false;
        }

        // delete avatar

        $rootDir = realpath(config('filesystems.disks.public.root'));

        if($request->has('remove_avatar')) {
            @unlink($rootDir . '/avatars/' . $this->getSetting('avatar'));
            $data['setting']['avatar'] = null;
        }

        // upload avatar
        if($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $str = str_random(25);
            $newFileName =  $str . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(200, 200)->save($rootDir . '/avatars/' . $newFileName);
            $data['setting']['avatar'] = $newFileName;
        }

        return $data;
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Activity logs
     */
    public function getNameOnLogsAttribute($value)
    {
        return $this->username;
    }

    public function getUrlOnLogsAttribute($value)
    {
        return route('admin.user.index');
    }
}
