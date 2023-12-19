<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFestchriftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festchrifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('publisher')->nullable();
            $table->string('title')->index();
            $table->text('abstract')->nullable();
            $table->string('subtitle')->nullable()->index();
            $table->json('editors')->nullable()->index();
            $table->json('keywords')->index();
            $table->date('publish_date')->index();
            $table->text('table_of_contents')->nullable();
            $table->string('cover')->nullable(); 
            $table->string('file')->nullable();
            $table->string('introduction')->nullable();
            $table->json('dedicatees')->nullable();
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
        Schema::dropIfExists('festchrifts');
    }
};
