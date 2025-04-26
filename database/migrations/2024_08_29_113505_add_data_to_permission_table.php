<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'module_name')) {
                $table->string('module_name')->after('name')->nullable();
            }
        });
        $permissions = [
            [
                'module_name' => 'user',
                'permission' => 'view_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'create_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'edit_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'delete_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'view_all_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'delete_all_user'
            ],
            [
                'module_name' => 'user',
                'permission' => 'update_all_user'
            ],
            [
                'module_name' => 'category',
                'permission' => 'view_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'edit_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'delete_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'create_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'view_all_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'delete_all_category'
            ],
            [
                'module_name' => 'category',
                'permission' => 'update_all_category'
            ],
            [
                'module_name' => 'post',
                'permission' => 'view_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'create_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'edit_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'delete_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'publish_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'view_all_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'delete_all_post'
            ],
            [
                'module_name' => 'post',
                'permission' => 'update_all_post'
            ],
            [
                'module_name' => 'comment',
                'permission' => 'create_comment'
            ],
            [
                'module_name' => 'comment',
                'permission' => 'edit_comment'
            ],
            [
                'module_name' => 'comment',
                'permission' => 'delete_comment'
            ],
            [
                'module_name' => 'comment',
                'permission' => 'view_comment'
            ],
        ];
        foreach ($permissions as $permission) {

            $perm = new Permission();
            $perm->module_name = $permission['module_name'];
            $perm->name = $permission['permission'];
            $perm->save();
        }
        // User::factory()->create(
        //     [
        //         'fname' => 'Aryan',
        //         'lname' => 'Patel',
        //         'email' => 'aryan@gmail.com',
        //         'mobile' => '9099771724',
        //         'birthdate' => fake()->date(),
        //     ]
        // );
        // $user = User::find(1);
        // // dd($role);
        // $user->givePermissionTo(Permission::all());

        // $role->permissions()->saveMany($permissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission', function (Blueprint $table) {
            //
        });
    }
};
