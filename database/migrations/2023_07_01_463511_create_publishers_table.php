<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('publishers')) return;
        Schema::create('publishers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();

            $table->index(['name']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('publishers');

    }
}
