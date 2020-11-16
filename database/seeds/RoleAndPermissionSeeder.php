<?php

use App\Enums\UserRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->truncateRolesAndPermissions();

        Cache::forget('spatie.permission.cache');

        foreach ($roles = [UserRole::ADMIN, UserRole::BUSINESS_ACCOUNT, UserRole::UNICER, UserRole::INFLUENCER] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'api']);
        }

        foreach ($permissions = [
            'profile.read',
            'profile.create',
            'profile.update',
            'profile.delete',
            'promotion.read',
            'promotion.create',
            'statistics.read',
            'uniwall.join'
        ] as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        /**
         * Give permissions to Admin.
         */
        Role::findByName('admin')->givePermissionTo($permissions);

        /**
         * Give permissions to Business Account.
         */
        $permissionBusinessAccount = Permission::whereIn('name', [
            'promotion.create',
            'statistics.read',
        ])->get();

        Role::findByName('business_account')->givePermissionTo($permissionBusinessAccount);

        /**
         * Give permissions to Unicer.
         */
        $permissionUnicer = Permission::whereIn('name', [
            'uniwall.join',
        ])->get();

        Role::findByName('unicer')->givePermissionTo($permissionUnicer);
    }

    protected function truncateRolesAndPermissions()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
