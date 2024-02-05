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
        if (Schema::hasTable('users')) return;
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('password');
            $table->enum('role', ['super_admin', 'admin', 'publisher', 'user', 'reviewer'])->default('user')->index();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token')->nullable();
            $table->string('profile_image', 700)->nullable();
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
        Schema::dropIfExists('users');
    }
};
