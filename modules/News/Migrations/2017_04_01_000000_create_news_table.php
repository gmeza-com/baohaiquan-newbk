<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at');
            $table->boolean('published')->default(false);
            $table->string('thumbnail')->nullable();
            $table->mediumInteger('user_id', false, true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('post_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('slug')->index()->nullable();
            $table->string('description');
            $table->longtext('content');
            $table->bigInteger('post_id', false, true);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('parent_id', false, true);
            $table->boolean('published')->default(false);
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        Schema::create('post_category_languages', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('slug')->index()->nullable();
            $table->mediumInteger('post_category_id', false, true);
            $table->foreign('post_category_id')->references('id')->on('post_categories')->onDelete('cascade');
        });

        Schema::create('post_category', function (Blueprint $table) {
            $table->bigInteger('post_id', false, true);
            $table->mediumInteger('post_category_id', false, true);
            $table->primary(['post_id', 'post_category_id']);
        });


        /** create role and permission */
        /** @var \Modules\Acl\Models\Role $admin */
        $admin = \Modules\Acl\Models\Role::find(1);

        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.index',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách các bài viết'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.create',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo bài viết mới'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.edit',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa bài viết'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.destroy',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa bài viết'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.category.index',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách các danh mục bài viêt'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.category.create',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo danh mục bài viêt mới'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.category.edit',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa danh mục bài viêt'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.category.destroy',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa danh mục bài viêt'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.approved',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Kiểm duyệt bài viết.'
                ]
            ]
        ]);
        $admin->permissions()->attach($permission->id);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.approved_level_1',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Kiểm duyệt bài viết dành cho biên tập.'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.approved_level_2',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Kiểm duyệt bài viết dành cho phó biên tập.'
                ]
            ]
        ]);
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.approved_level_3',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Kiểm duyệt bài viết dành cho tổng biên tập.'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'news.post.only_show_my_post',
            'module' => 'post'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Chỉ hiển thị bài viết của bản thân.'
                ]
            ]
        ]);


        // Phóng viên > Biên tập > Phó Tổng Biên Tập > Tổng Biên Tập


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(4);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '#',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'fa fa-pencil-square-o',
                'target' => '_self',
                'permission' => 'news.post.index'
            ],
            'position' => 4,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Bài viết',
                ]
            ]
        ]);
        $parenID = $menu->id;
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/post',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => null,
                'target' => '_self',
                'permission' => 'news.post.index'
            ],
            'position' => 0,
            'level' => 1,
            'parent_id' => $parenID,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Danh sách bài viết',
                ]
            ]
        ]);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/post/category',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => null,
                'target' => '_self',
                'permission' => 'news.category.index'
            ],
            'position' => 1,
            'level' => 1,
            'parent_id' => $parenID,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Danh sách danh mục',
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
        \Modules\Acl\Models\Permission::where('module', 'news')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%news.post.%')
            ->get()
            ->each->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%news.category.%')
            ->get()
            ->each->delete();
        Schema::dropIfExists('post_category');
        Schema::dropIfExists('post_languages');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_category_languages');
        Schema::dropIfExists('post_categories');
    }
}
