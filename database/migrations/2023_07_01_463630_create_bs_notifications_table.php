<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bs_notifications')) return;
        Schema::create('bs_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sender_id')->constrained('fc_users')->cascadeOnDelete();
            $table->string('title', 300)->index();
            $table->string('message', 600)->index();
            $table->string('type', 60)->index()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_notifications');
    }
};
