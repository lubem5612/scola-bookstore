<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_order_items')) return;
        Schema::create('bs_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('bs_orders')->cascadeOnDelete();
            $table->foreignUuid('resource_id')->constrained('bs_resources')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 5);
            $table->float('discount')->nullable()->index();
            $table->enum('discount_type', ['amount', 'percent'])->default('amount')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_order_items');
    }
};
