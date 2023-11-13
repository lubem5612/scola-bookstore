<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


<<<<<<< HEAD:database/migrations/2018_01_01_463634_create_order_details_table.php
class CreateOrderDetailsTable extends Migration {
=======
return new class extends Migration {
>>>>>>> 35b4e20c2d72f0b73212371a6951a85647988719:database/migrations/2023_07_01_463634_create_order_details_table.php

    public function up()
    {
        if (Schema::hasTable('order_details')) return;
        Schema::create('order_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignuuid('order_id');
            $table->foreignuuid('book_id');
            $table->decimal('quantity', 15, 5);
            $table->decimal('total_price', 15, 5);
            $table->decimal('discount', 8, 5)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
