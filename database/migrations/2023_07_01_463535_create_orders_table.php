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
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number')->unique()->index();
            $table->enum('delivery_status', ['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled'])->default('processing')->index();
            $table->enum('order_status', ['success', 'failed'])->default('success')->index();
            $table->string('payment_status')->nullable()->comment('paid or unpaid');
            $table->string('payment_reference')->nullable();
            $table->decimal('total_amount', 18, 6)->default(0);
            $table->json('shipping_info')->nullable()->comment('address, fee, delivery_time');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

