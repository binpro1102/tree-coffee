<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id'); // FK
            $table->unsignedBigInteger('restaurant_id'); // FK

           // $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_method')->onDelete('cascade');
           //  $table->foreign('restaurant_id')->references('restaurant_id')->on('restaurants')->onDelete('cascade');

            //  Việt Lưu fetch code về bỏ cmt //, để tạo khóa ngoại tới 2 bảng trên
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
