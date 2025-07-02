<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDashboardPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // -------------------------------------------------------------------

        /** create role and permission */
        /** @var \Modules\Acl\Models\Role $admin */
        $admin = \Modules\Acl\Models\Role::find(1);

        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'dashboard.dashboard.index',
            'module' => 'dashboard'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem bản thông tin'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(1);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'fa fa-dashboard',
                'target' => '_self',
                'permission' => 'dashboard.dashboard.index'
            ],
            'position' => 1,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Bảng thông tin',
                ]
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Modules\Acl\Models\Permission::where('module', 'dashboard')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%dashboard.dashboard.%')
            ->get()
            ->each->delete();
    }
}
