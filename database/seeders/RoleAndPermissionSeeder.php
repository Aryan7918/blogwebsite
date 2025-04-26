<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // // create permissions
        // Permission::create(['name' => 'create post']);
        // Permission::create(['name' => 'view post']);
        // Permission::create(['name' => 'edit post']);
        // Permission::create(['name' => 'delete post']);
        // Permission::create(['name' => 'publish post']);
        // Permission::create(['name' => 'unpublish post']);
        // Permission::create(['name' => 'view category']);
        // Permission::create(['name' => 'create category']);
        // Permission::create(['name' => 'edit category']);
        // Permission::create(['name' => 'delete category']);
        // Permission::create(['name' => 'create user']);
        // Permission::create(['name' => 'view user']);
        // Permission::create(['name' => 'edit user']);
        // Permission::create(['name' => 'delete user']);


        // create roles and assign created permissions

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // // this can be done as separate statements
        $role = Role::create(['name' => 'author']);
        $role->givePermissionTo(['create_post', 'edit_post', 'view_post', 'delete_post', 'create_category', 'edit_category', 'view_category', 'delete_category', 'create_user', 'edit_user', 'view_user', 'delete_user']);

        $role = Role::create(['name' => 'reader']);
        $role->givePermissionTo('view_post');
    }
}
