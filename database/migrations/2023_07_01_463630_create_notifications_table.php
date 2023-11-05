<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('notifications')) return;
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignuuid('user_id');
            $table->foreignuuid('book_id');
            $table->string('type')->nullable()->index();
            $table->string('message', 700)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
