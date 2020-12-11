<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
          'title'=>'ICU Shortener',
          'keywords'=>'url, link, short, shorten links',
          'description'=>'URL Link Shortener Website by vooky',
          'logo'=>'https://u.vlkn.icu/img/url.svg',
          'favicon'=>'http://u.vlkn.icu/img/url.svg',
          'header_code'=>'',
          'footer_code'=>'',
          'created_at'=>now(),
          'updated_at'=>now()
        ]);
    }
}
