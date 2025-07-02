<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->text('field')->nullable();
            $table->timestamps();
        });

        Schema::create('form_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('data')->nullable();
            $table->integer('form_id', false, true);
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->timestamps();
        });

        // -------------------------------------------------------------------

        /** create role and permission */
        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.form.index',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách kiểu form'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.form.create',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo kiểu form mới'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.form.edit',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa form'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.form.destroy',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa form'
                ]
            ]
        ]);

        $admin = \Modules\Acl\Models\Role::find(1);
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.data.index',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách dữ liệu biểu mẫu'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.data.show',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem dữ liệu biểu mẫu'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'form.data.destroy',
            'module' => 'form'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa dữ liệu mẫu'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(5);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/form/data',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'fa fa-check',
                'target' => '_self',
                'permission' => 'form.data.index'
            ],
            'position' => 5,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Biểu mẫu',
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
        \Modules\Acl\Models\Permission::where('module', 'form')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%form.form.%')
            ->get()
            ->each->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%form.data.%')
            ->get()
            ->each->delete();

        Schema::dropIfExists('form_datas');
        Schema::dropIfExists('forms');
    }
}
