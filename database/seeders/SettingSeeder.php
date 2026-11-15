<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')
            ->insert([
                'site'        => config('app.url'),
                'title'       => 'ICU Shortener',
                'keywords'    => 'url, link, short, shorten links',
                'description' => 'URL Link Shortener Website by vooky',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
    }
}
