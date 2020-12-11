<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name'=>'Demo',
          'surname'=>'Admin',
          'email'=>'demo@admin.com',
          'username'=>'admin',
          'password'=>bcrypt(123456),
          'role'=>1,
          'created_at'=>now(),
          'updated_at'=>now()
        ]);
    }
}
