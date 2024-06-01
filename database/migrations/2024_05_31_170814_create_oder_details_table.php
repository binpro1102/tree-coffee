<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oder_details', function (Blueprint $table) {
            $table->id('order_detail_id');              // PK
            $table->unsignedBigInteger('product_id'); //FK
            $table->unsignedBigInteger('order_id'); // FK
            $table->string('price');
            $table->decimal('quantity');
            $table->string('discount');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
            
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oder_details');
    }
}
