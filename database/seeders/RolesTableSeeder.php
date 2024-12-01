<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'super admin', 'guard_name' => 'superadmin']);
        Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        Role::create(['name' => 'teacher', 'guard_name' => 'teacher']);
        Role::create(['name' => 'student', 'guard_name' => 'student']);
    }
}
