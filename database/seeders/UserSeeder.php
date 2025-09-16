<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
  public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ]);
        $user = User::firstOrCreate(
            ['email' => 'admin@.com'],
            [
                'name' => 'Developer Robiul',
                'slug' => 'super-admin',
                'uui_code' => uniqid('ADM-'),
                'phone' => '01882850027',
                'password' => Hash::make('01882850027'), 
            ]
        );
        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
    }
}
