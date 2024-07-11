<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_departments')) return;
        Schema::create('bs_departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faculty_id')->constrained('bs_faculties')->cascadeOnDelete();
            $table->string('name', 150)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_departments');
    }
};