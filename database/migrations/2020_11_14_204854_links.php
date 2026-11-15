<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Links extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::create('links', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('owner_id');
            $table->string('url');
            $short = $table->string('short')->unique();
            match ($driver) {
                'mysql', 'mariadb' => $short->charset('utf8mb4')->collation('utf8mb4_bin'),
                'pgsql'            => $short->collation('C'),
                'sqlsrv'           => $short->collation('Latin1_General_100_BIN2'),
                'sqlite'           => $short->collation('BINARY'),
                default            => null,
            };
            $table->unsignedBigInteger('total_clicks')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
