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
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete();
            $table->string('title')->index();
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('publisher')->nullable()->index();
            $table->string('publication_date')->nullable()->index();
            $table->string('publication_year')->nullable()->index();
            $table->json('editors')->nullable()->index();
            $table->string('website')->nullable()->index();
            $table->string('volume')->index();
            $table->string('page_start')->nullable()->index();
            $table->string('page_end')->nullable()->index();
            $table->string('editorial')->nullable();
            $table->json('editorial_board_members')->nullable()->index();
            $table->string('file_path')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('conclusion')->nullable();
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
