<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdmin();
    }

    /**
     * Create/Update Admin User
     */
    private function createAdmin(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@tasknest.com'],
            [
                'name' => 'TaskNest Admin',
                'password' => bcrypt('123456789'),
            ]
        );
    }
}
