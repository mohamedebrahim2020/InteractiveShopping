<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'credit' => 0,
        ]);
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleUser = Role::create(['name' => 'User']);
        $roleAdmin = Role::where('name', '=', 'Admin')->first();
        $user->assignRole([$roleAdmin->id]);
    }
}
