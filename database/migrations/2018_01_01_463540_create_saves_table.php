<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavesTable extends Migration
{
    public function up()
    {
        Schema::create('saves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignuuid('user_id');
            $table->foreignuuid('book_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('saves');
    }
}
