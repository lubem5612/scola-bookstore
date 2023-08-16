<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->string('invoice_no')->unique();
            $table->integer('amount');
            $table->integer('total_amount');
            $table->enum('status', ['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled'])->default('processing');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');



            $table->index(['invoice_no']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
