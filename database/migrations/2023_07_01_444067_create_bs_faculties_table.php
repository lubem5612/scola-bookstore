<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_faculties')) return;
        Schema::create('bs_faculties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_faculties');
    }
};