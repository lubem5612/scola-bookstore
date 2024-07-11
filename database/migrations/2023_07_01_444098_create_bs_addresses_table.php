<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_addresses')) return;
        Schema::create('bs_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->string('address', 766)->nullable()->index();
            $table->foreignUuid('lg_id')->nullable()->constrained('bs_lgs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('postal_code', 25)->nullable()->index();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_addresses');
    }
};