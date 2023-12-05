<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up()
    {
        DBCapsule::schema()->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        DBCapsule::schema()->dropIfExists('products');
    }
};
