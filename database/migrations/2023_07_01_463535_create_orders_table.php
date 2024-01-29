<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) { 
            $table->uuid('id')->primary();
            $table->foreignuuid('user_id');
            $table->string('invoice_number')->unique()->index();
            $table->string('order_date')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('delivery_status', ['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled'])->default('processing')->index();
            $table->enum('order_status', ['success', 'failed'])->default('success')->index();
            $table->string('payment_status')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

