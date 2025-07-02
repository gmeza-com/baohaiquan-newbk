<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('content')->nullable();
            $table->text('setting')->nullable();
            $table->string('type');
            $table->boolean('lock')->default(false);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        /** create role and permission */
        /** @var \Modules\Acl\Models\Role $admin */
        $admin = \Modules\Acl\Models\Role::find(1);

        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'widget.widget.index',
            'module' => 'widget'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách các widget'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'widget.widget.create',
            'module' => 'widget'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo bài widget'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'widget.widget.edit',
            'module' => 'widget'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa widget'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'widget.widget.destroy',
            'module' => 'widget'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa widget'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(3);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/widget',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'fa fa-anchor',
                'target' => '_self',
                'permission' => 'widget.widget.index'
            ],
            'position' => 3,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Widgets',
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
        \Modules\Acl\Models\Permission::where('module', 'widget')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%widget.widget.%')
            ->get()
            ->each->delete();
        Schema::dropIfExists('widgets');
    }
}
