<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create(
            [
                'fname' => 'Aryan',
                'lname' => 'Patel',
                'email' => 'aryan@gmail.com',
                'mobile' => '9099771724',
                'birthdate' => fake()->date(),
            ]
        );
        User::factory()->create(
            [
                'fname' => 'Smit',
                'lname' => 'Patel',
                'email' => 'smit@gmail.com',
                'mobile' => '9099771725',
                'birthdate' => fake()->date(),
            ]
        );
        $this->call([
            RoleAndPermissionSeeder::class
        ]);
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('reader');
        });
        $user = User::find(1);
        $user->syncRoles('admin');

        $user = User::find(2);
        $user->syncRoles('author');

        Category::factory(5)->create();
        Post::factory(5)->create();
        Comment::factory(5)->create();


        // $role = Role::create(['name' => 'author']);
        // $permission = Permission::create(['name' => 'edit post']);

        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);
        // $user



    }
}