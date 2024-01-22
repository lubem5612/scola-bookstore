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
            $table->string('subtitle')->nullable()->index();
            $table->string('primary_author')->index();
            $table->json('contributors')->nullable()->index();
            $table->string('abstract')->nullable();
            $table->json('keywords')->nullable()->index();
            $table->string('conference_name')->nullable()->index();
            $table->string('conference_year')->nullable()->index();
            $table->string('conference_date')->nullable()->index();
            $table->string('conference_location')->nullable()->index();  
            $table->json('institutional_affiliations')->nullable()->index();
            $table->string('file_path')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('price', 15, 5)->index();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->timestamps();
        });
  }


    public function down()
    {
        Schema::dropIfExists('conference_papers');
    }
};
