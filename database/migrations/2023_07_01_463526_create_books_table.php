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
            $table->foreignuuid('user_id');
            $table->foreignuuid('category_id');
            $table->foreignuuid('publisher_id')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('author');
            $table->string('cover')->nullable();
            $table->string('file')->nullable();
            $table->date('publish_date');
            $table->string('publisher');
            $table->string('edition')->nullable();
            $table->string('ISBN')->unique();
            $table->decimal('price', 15, 5);
            $table->string('tags')->nullable();
            $table->string('summary')->nullable();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate();
            $table->foreign('publisher_id')->references('id')->on('publishers')->cascadeOnUpdate();

            $table->index(['author']);
            $table->index(['edition']);
            $table->index(['title']);
            $table->index(['publish_date']);
            $table->index(['publisher']);
            $table->index(['tags']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
