<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['name' => strtolower('admin')]);
        Role::updateOrCreate(['name' => strtolower('staff')]);
        Role::updateOrCreate(['name' => strtolower('user')]);
    }
}