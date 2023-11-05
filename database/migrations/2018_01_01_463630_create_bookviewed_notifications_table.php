<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookViewedNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('book_viewed_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignuuid('user_id');
            $table->foreignuuid('book_id');
            $table->string('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_viewed_notifications');
    }
}
