<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsDeleteAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('product_category', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('restaurant_images', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('product_category', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('restaurant_images', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('is_delete')->default(false);
        });
    }
}
