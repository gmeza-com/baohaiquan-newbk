<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->boolean('published')->default(false);
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        Schema::create('gallery_category_languages', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('slug')->index()->nullable();
            $table->mediumInteger('gallery_category_id', false, true);
            $table->foreign('gallery_category_id')->references('id')->on('gallery_categories')->onDelete('cascade');
        });

        Schema::create('gallery_category', function (Blueprint $table) {
            $table->mediumInteger('gallery_id', false, true);
            $table->mediumInteger('gallery_category_id', false, true);
            $table->primary(['gallery_id', 'gallery_category_id']);
        });

        // delete old menu
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%gallery.gallery.%')
            ->get()
            ->each->delete();


        /** create role and permission */
        /** @var \Modules\Acl\Models\Role $admin */
        $admin = \Modules\Acl\Models\Role::find(1);

        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.category.index',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách danh mục thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.category.create',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo danh mục thư viện mới'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.category.edit',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa danh mục thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'gallery.category.destroy',
            'module' => 'gallery'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa danh mục thư viện'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(3);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '#',
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
        $parentId = $menu->id;
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/gallery',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => '',
                'target' => '_self',
                'permission' => 'gallery.gallery.index'
            ],
            'position' => 0,
            'level' => 1,
            'parent_id' => $parentId,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Quản lý thư viện',
                ]
            ]
        ]);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/gallery/category',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => '',
                'target' => '_self',
                'permission' => 'gallery.category.index'
            ],
            'position' => 1,
            'level' => 1,
            'parent_id' => $parentId,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Quản lý danh mục',
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
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%gallery.category.%')
            ->get()
            ->each->delete();
        Schema::dropIfExists('gallery_category');
        Schema::dropIfExists('gallery_category_languages');
        Schema::dropIfExists('gallery_categories');
    }
}
