<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete();
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->string('ISSN')->nullable()->index();
            $table->string('publisher')->nullable()->index();
            $table->string('publish_date')->nullable()->index();
            $table->json('editors')->nullable()->index();
            $table->string('website')->nullable()->index();
            $table->text('table_of_contents')->nullable();
            $table->text('editorial')->nullable();
            $table->json('editorial_board_members')->nullable()->index();
            $table->text('articles')->nullable();
            $table->string('file');
            $table->string('cover')->nullable();
            $table->text('conclusion')->nullable();
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
        Schema::dropIfExists('journals');
    }
};
