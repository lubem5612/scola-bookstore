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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reference', 50)->unique();
            $table->decimal('amount', 19, 8);
            $table->decimal('charges', 19, 8)->default(0);
            $table->decimal('commission', 19, 8)->default(0);
            $table->enum('type', ['debit', 'credit'])->index()->default('credit');
            $table->string('description', 700)->nullable()->index();
            $table->string('status', 50)->default('pending')->index();
            $table->json('payload')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};