<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Admin::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'user_name' => 'Test Admin',
            'email' => 'test@example.com'
        
        ]);
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password'=>Hash::make('password'),
            'userable_id'=> 1,
            'userable_type'=> Admin::class
        ]);

        $this->call([
            RolePermissionSeeder::class
        ]);
    }
}
