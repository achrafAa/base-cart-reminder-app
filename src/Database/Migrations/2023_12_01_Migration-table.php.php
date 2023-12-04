<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up()
    {
        DBCapsule::schema()->create('migrations', function (Blueprint $table) {
            $table->id();
            $table->string('migration');
            $table->integer('batch');
            $table->timestamps();
        });
    }

    public function down()
    {
        DBCapsule::schema()->dropIfExists('migrations');
    }
};
