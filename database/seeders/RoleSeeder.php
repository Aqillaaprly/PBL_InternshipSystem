<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**a
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert(
            [
                ['name' => 'admin']
                ]);
    }
}