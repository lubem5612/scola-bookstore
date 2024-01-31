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
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('publisher')->nullable()->index();
            $table->string('title')->index();
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('primary_author')->index();
            $table->json('contributors')->nullable()->index();
            $table->json('keywords')->index();
            $table->string('publication_date')->index();
            $table->string('pages')->nullable();
            $table->string('file_path')->nullable(); 
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
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
