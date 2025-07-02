<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('type');
            $table->boolean('hidden')->default(false);
            $table->boolean('require')->default(false);
            $table->string('module');
            $table->timestamps();
        });

        Schema::create('field_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->string('name');
            $table->integer('field_id', false, true);
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });

        Schema::create('field_type_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position', false, true)->default(true);
            $table->integer('field_id', false, true);
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });

        Schema::create('field_type_data_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->string('slug');
            $table->text('value');
            $table->integer('field_type_data_id', false, true);
            $table->foreign('field_type_data_id')->references('id')->on('field_type_datas')->onDelete('cascade');
        });

        Schema::create('field_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_type_data_id', false, true)->default(0);
            $table->integer('field_id', false, true);
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->integer('module_id', false, true)->index();
            $table->string('module_type');
        });

        Schema::create('field_data_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->text('value');
            $table->integer('field_data_id', false, true);
            $table->foreign('field_data_id')->references('id')->on('field_datas')->onDelete('cascade');
        });

        // -------------------------------------------------------------------

        /** create role and permission */
        /** @var \Modules\Acl\Models\Permission $permission */
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.field.index',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách các trường tùy biến'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.field.create',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo trường tùy biến mới'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.field.edit',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa trường tùy biến'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.field.destroy',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa trường tùy biến'
                ]
            ]
        ]);
        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.type.index',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xem danh sách các con của trường tùy biến'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.type.create',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Tạo thuộc tính con của trường tùy biến mới'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.type.edit',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Sửa thuộc tính con của trường tùy biến'
                ]
            ]
        ]);

        $permission = \Modules\Acl\Models\Permission::create([
            'slug' => 'customfield.type.destroy',
            'module' => 'custom_field'
        ]);
        $permission->saveLanguages([
            'language' => [
                'vi' => [
                    'description' => 'Xóa thuộc tính con của trường tùy biến'
                ]
            ]
        ]);


        /** @var \Modules\Menu\Models\MenuItem $menu */
        \Modules\Menu\Models\MenuItem::takeAPositionToEmpty(5);
        $menu = \Modules\Menu\Models\MenuItem::create([
            'attributes' => [
                'url' => '/iadmin/custom-field',
                'id' => null,
                'class' => null,
                'rel' => 'dofollow',
                'icon' => 'gi gi-settings',
                'target' => '_self',
                'permission' => 'customfield.field.index'
            ],
            'position' => 5,
            'level' => 0,
            'parent_id' => 0,
            'menu_id' => 1
        ]);
        $menu->saveLanguages([
            'language' => [
                'vi' => [
                    'name' => 'Trường tùy biến',
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
        \Modules\Acl\Models\Permission::where('module', 'custom_field')->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%customfield.field.%')
            ->get()
            ->each->delete();
        \Modules\Menu\Models\MenuItem::where('attributes', 'like', '%customfield.type.%')
            ->get()
            ->each->delete();

        Schema::dropIfExists('field_type_data_languages');
        Schema::dropIfExists('field_type_datas');
        Schema::dropIfExists('field_data_languages');
        Schema::dropIfExists('field_datas');
        Schema::dropIfExists('field_languages');
        Schema::dropIfExists('fields');
    }
}
