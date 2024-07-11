<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bs_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->string('invoice_number')->unique()->index();
            $table->enum('delivery_status', ['processing', 'on_the_way', 'arrived', 'delivered', 'cancelled'])->default('processing')->index();
            $table->enum('order_status', ['success', 'failed', 'cancelled', 'pending'])->default('success')->index();
            $table->string('payment_status')->nullable()->comment('paid or unpaid');
            $table->string('payment_reference')->nullable();
            $table->decimal('total_amount', 18, 6)->default(0);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_orders');
    }
};

