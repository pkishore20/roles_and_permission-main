<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // user permissions
        Permission::create(['name' => 'user_show']);
        Permission::create(['name' => 'user_create']);
        Permission::create(['name' => 'user_edit']);
        Permission::create(['name' => 'user_delete']);

        // role permissions
        Permission::create(['name' => 'role_show']);
        Permission::create(['name' => 'role_create']);
        Permission::create(['name' => 'role_edit']);
        Permission::create(['name' => 'role_delete']);

        // permission permissions
        Permission::create(['name' => 'permission_show']);
        Permission::create(['name' => 'permission_create']);
        Permission::create(['name' => 'permission_edit']);
        Permission::create(['name' => 'permission_delete']);

        // article permissions
        Permission::create(['name' => 'article_show']);
        Permission::create(['name' => 'article_create']);
        Permission::create(['name' => 'article_edit']);
        Permission::create(['name' => 'article_delete']);



        $role1 = Role::create(['name' => 'Super Admin']);
        $role1->givePermissionTo(Permission::all());

        // or may be done by chaining
        $role2 = Role::create(['name' => 'Admin'])
            ->givePermissionTo(['article_show','article_create','article_edit','article_delete','user_show','user_create']);

        $role3 = Role::create(['name' => 'User']);
        $role3->givePermissionTo('article_show','article_create','article_edit','article_delete');


        $user = \App\Models\User::factory()->create([
            'name' => 'Kishore SuperAdmin',
            'email' => 'superadmin@gmail.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'kishore Admin',
            'email' => 'admin@admin.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'kishore User',
            'email' => 'user@user.com',
        ]);
        $user->assignRole($role3);

    }
}
