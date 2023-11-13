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
            $table->foreignuuid('school_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['superAdmin', 'admin', 'publisher', 'user']);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('bio')->nullable();
            $table->string('specialization')->nullable();
            $table->string('profile_image', 700)->nullable();
            $table->string('address')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('verification_token')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->nullOnDelete();

            $table->index(['first_name']);
            $table->index(['last_name']);
            $table->index(['is_verified']);
            $table->index(['role']);
            $table->index(['specialization']);
            $table->index('phone');
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
