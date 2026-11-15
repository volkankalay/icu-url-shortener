<?php

namespace Database\Seeders;

use App\Enums\UserType;
use DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserType::cases() as $userType) {

            DB::table('users')->insert([
                'created_at' => now(),
                'updated_at' => now(),
                'name'       => 'Test',
                'surname'    => $userType->label(),
                'email'      => $userType->value . '@demo.com',
                'username'   => $userType->value,
                'user_type'  => $userType->value,
                'password'   => bcrypt(123456),
            ]);
        }
    }
}
