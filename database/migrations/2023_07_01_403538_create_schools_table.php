<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('schools')) return;
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
};
