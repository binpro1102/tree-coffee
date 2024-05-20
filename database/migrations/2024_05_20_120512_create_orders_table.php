<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');                 //PK
            $table->unsignedBigInteger('user_id'); // FK
            $table->date('order_date');
            $table->decimal('total_price');
            $table->string('shipping_address');
            $table->string('note');
            $table->string('total_discount');
            $table->string('sub_total');
            $table->string('status');
            $table->timestamps();


             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
