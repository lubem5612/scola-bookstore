<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) { 
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title')->index();
            $table->text('abstract');
            $table->string('subtitle')->nullable()->index();
            $table->string('introduction')->nullable();
            $table->string('primary_author')->index();
            $table->json('other_authors')->nullable()->index();
            $table->json('keywords')->index();
            $table->date('publish_date')->index();
            $table->string('ISSN')->nullable()->index();
            $table->string('pages')->nullable();
            $table->string('file')->nullable(); 
            $table->string('literature_review')->nullable();
            $table->string('methodology')->nullable();
            $table->string('result')->nullable();
            $table->string('discussion')->nullable();
            $table->string('conclusion')->nullable();
            $table->json('references')->nullable();
            $table->decimal('price', 15, 5)->index();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
