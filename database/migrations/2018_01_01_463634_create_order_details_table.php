<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<< Updated upstream
class CreateOrderDetailsTable extends Migration {
=======
class giCreateOrderDetailsTable extends Migration {
>>>>>>> Stashed changes
    public function up()
    {
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignuuid('order_id');
            $table->foreignuuid('book_id');
            $table->decimal('quantity', 15, 5);
            $table->decimal('total_price', 15, 5);
            $table->decimal('discount', 2, 1)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orderdetails');
    }
}
