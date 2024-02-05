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
            $table->foreignUuid('country_id')->constrained('countries')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('state_id')->constrained('states')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('lg_id')->constrained('lgs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('recipient_first_name')->index();
            $table->string('recipient_last_name')->index();
            $table->string('postal_code')->index();
            $table->string('email')->index();
            $table->string('alternative_phone')->index();
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
