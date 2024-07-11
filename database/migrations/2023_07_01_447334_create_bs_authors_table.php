<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_authors')) return;
        Schema::create('bs_authors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('bs_departments')->cascadeOnDelete();
            $table->foreignUuid('faculty_id')->nullable()->constrained('bs_faculties')->cascadeOnDelete();
            $table->string('specialization', 700)->nullable();
            $table->text('bio')->nullable();
            $table->json('bank_info')->nullable()->comment('account_no, account_name, bank_code');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_authors');
    }
};