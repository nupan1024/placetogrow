<?php

namespace Database\Seeders;

use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(Roles::toArray());
        Role::findByName(ucwords(strtolower(str_replace('_', ' ', Roles::SUPER_ADMIN->name))))
            ->syncPermissions(Permissions::getPermissions());
    }
}
