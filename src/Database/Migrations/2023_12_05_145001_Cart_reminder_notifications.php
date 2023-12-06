<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DBCapsule;
use Illuminate\Database\Schema\Blueprint;

return new class
{
    /**
     * @return void
     */
    public function up()
    {
        DBCapsule::schema()->create('cart_reminder_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->integer('attempt')->default(0);
            $table->timestamp('sent_at')->useCurrent();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        DBCapsule::schema()->dropIfExists('cart_reminder_notifications');
    }
};
