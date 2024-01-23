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
            $table->uuid('resource_id');
            $table->integer('quantity');
            $table->dateTime('order_date');
            $table->decimal('unit_price', 15, 5);
            $table->decimal('total_amount', 15, 5);
            $table->string('invoice_number')->unique()->index();
            $table->string('payment_reference')->unique()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
            $table->enum('status', ['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled'])->default('processing')->index();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

