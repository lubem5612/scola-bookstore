<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignuuid('user_id');
            $table->foreignuuid('book_id');
            $table->Integer('quantity');
<<<<<<< HEAD
            $table->decimal('amount', 15,5);
            $table->decimal('total_amount', 15, 5);
=======
            $table->decimal('amount', 16, 9);
            $table->decimal('total_amount', 16, 9);
>>>>>>> 8169ade3e9b5250f10abb6ff38afca5d67a83026
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};