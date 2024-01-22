<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUuid('publisher_id')->constrained('publishers')->cascadeOnDelete();
            $table->string('publisher')->index();
            $table->string('publication_date')->nullable()->index();
            $table->string('report_number')->nullable()->index();
            $table->string('publication_year')->index();
            $table->string('organization')->nullable()->index();
            $table->json('institutional_affiliations')->nullable()->index();
            $table->string('primary_author')->index();
            $table->json('contributors')->nullable()->index();
            $table->string('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->json('keywords')->nullable()->index();
            $table->string('summary')->nullable();
            $table->string('funding_information')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path');
            $table->string('license_information')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
