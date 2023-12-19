<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete();
            $table->string('publisher')->nullable()->index();
            $table->string('publication_date')->nullable()->index();
            $table->string('primary_author')->index();
            $table->json('contributory_authors')->nullable()->index();
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->string('overview')->nullable();
            $table->string('resource_type')->nullable(); // E.g., Dataset, Software, Educational Material
            $table->json('keywords')->nullable()->index();
            $table->string('methodology')->nullable();
            $table->string('file')->nullable();
            $table->string('cover')->nullable();
            $table->string('conclusion')->nullable();
            $table->string('references')->nullable();
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
        Schema::dropIfExists('research_resources');
    }
};
