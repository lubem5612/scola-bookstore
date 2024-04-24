<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('address', 766)->nullable()->index();
            $table->foreignUuid('lg_id')->nullable()->constrained('lgs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('recipient_name')->index();
            $table->string('postal_code')->nullable()->index();
            $table->string('email')->index();
            $table->string('alternative_phone')->nullable()->index();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickups');
    }
};
