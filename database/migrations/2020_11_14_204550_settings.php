<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('settings', function (Blueprint $table) {
          $table->id();
          $table->string('site')->default('https://u.vlkn.icu');
          $table->string('title');
          $table->string('keywords')->nullable();
          $table->string('description')->nullable();
          $table->string('logo');
          $table->string('favicon')->nullable();
          $table->longText('header_code')->nullable();
          $table->longText('footer_code')->nullable();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
