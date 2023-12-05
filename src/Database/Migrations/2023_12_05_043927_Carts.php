<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up()
    {
        DBCapsule::schema()->create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_email');
            $table->string('customer_fullname');
            $table->timestamps();
        });
    }

    public function down()
    {
        DBCapsule::schema()->dropIfExists('carts');
    }
};
