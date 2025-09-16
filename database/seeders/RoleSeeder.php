<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleName = ['admin', 'accountent', 'staff'];

        foreach ($roleName as $role) {
            Role::firstOrCreate([
                'name' => $role,
            ]);
        }
    }
}
