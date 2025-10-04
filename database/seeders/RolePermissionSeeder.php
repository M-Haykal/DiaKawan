<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Graphic Designer',
            'Cinematographer',
            'Content Creator',
            'Data Analyst',
            'Surveyor & Research',
            'Public Speaker',
            'Budget Planner',
            'Inventory Manager',
            'User',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Pastikan user #1 adalah admin
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('Admin');
        }
    }
}