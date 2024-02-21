<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('authors')) return;
        Schema::create('authors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->foreignUuid('faculty_id')->nullable()->constrained('faculties')->cascadeOnDelete();
            $table->string('specialization', 700)->nullable();
            $table->text('bio')->nullable();
            $table->json('bank_info')->nullable()->comment('account_no, account_name, bank_code');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('authors');
    }
};