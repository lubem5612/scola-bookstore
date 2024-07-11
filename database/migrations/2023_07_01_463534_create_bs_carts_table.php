<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_carts')) return;
        Schema::create('bs_carts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('resource_id')->constrained('bs_resources')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('is_selected')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_carts');
    }
};