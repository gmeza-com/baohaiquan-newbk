<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->timestamp('published_at');
            $table->string('thumbnail')->nullable();
            $table->string('type', 10);
            $table->mediumInteger('user_id', false, true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('gallery_languages', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('slug')->index()->nullable();
            $table->string('description');
            $table->text('content');
            $table->mediumInteger('gallery_id', false, true);
            $table->foreign('gallery_id')->references('id')->on('gallery')->onDelete('cascade');
        });


        /** create role and permission */
        /** @var \Modules\Acl\Models\Role $admin */
        $admin = \Modules\Acl\Models\Role::find(1);

        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.gallery.index',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.gallery.create',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo thư viện mới'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.gallery.edit',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.gallery.destroy',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(3);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/gallery',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'fa fa-picture-o',
                'target' => '_self',
                'permission' => 'gallery.gallery.index'
            ],
            'position' => 3,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Thư viện',
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
        \Modules\Acl\Models\Permission::where('module', 'gallery')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%gallery.gallery.%')
            ->get()
            ->each->delete();
        Schema::dropIfExists('gallery_languages');
        Schema::dropIfExists('gallery');
    }
}
