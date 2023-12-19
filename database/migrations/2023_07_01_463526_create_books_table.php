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
            $table->string('title')->index();
            $table->text('abstract');
            $table->string('subtitle')->nullable();
            $table->string('primary_author')->index();
            $table->json('other_authors')->nullable()->index();
            $table->string('ISBN')->unique()->index();
            $table->string('publisher')->index();
            $table->date('publish_date')->index();
            $table->string('introduction')->nullable();
            $table->string('edition')->nullable()->index();
            $table->string('language')->nullable()->index();
            $table->string('table_of_contents')->nullable();
            $table->string('cover')->nullable();
            $table->string('file')->nullable();
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
