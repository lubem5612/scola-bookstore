<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('books')) return;
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('publisher')->index();           
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->string('preface')->nullable();
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable(); //the materials
            $table->string('primary_author')->index();
            $table->json('contributors')->nullable()->index();
            $table->string('ISBN')->unique()->index();
            $table->string('publication_date')->index();
            $table->string('edition')->nullable()->index();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->decimal('price', 15, 5);
            $table->string('tags')->nullable();
            $table->string('summary')->nullable();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
