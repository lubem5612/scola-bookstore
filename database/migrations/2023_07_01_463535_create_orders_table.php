<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('orders')) return;
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignuuid('user_id');
            $table->foreignuuid('book_id');
            $table->string('invoice_no')->unique();
            $table->decimal('amount', 15, 5);
            $table->decimal('total_amount', 15, 5);
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
