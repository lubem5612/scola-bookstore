<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConferencePapersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('conference_papers')) return;
        Schema::create('conference_papers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->string('abstract');
            $table->string('primary_author')->index();
            $table->json('other_authors')->nullable()->index();
            $table->date('conference_date')->index();
            $table->string('conference_title')->index();
            $table->string('pages')->nullable();
            $table->string('file')->nullable();
            $table->string('introduction')->nullable();
            $table->string('background')->nullable();
            $table->string('methodology')->nullable();
            $table->string('conclusion')->nullable();
            $table->string('result')->nullable();
            $table->decimal('price', 15, 5)->index();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->string('location')->index();
            $table->json('keywords')->nullable()->index();
            $table->json('references')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conference_papers');
    }
};
