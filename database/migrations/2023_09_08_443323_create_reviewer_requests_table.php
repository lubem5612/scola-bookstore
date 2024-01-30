<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewerRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('reviewer_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('specialization');
            $table->string('status')->nullable();
            $table->json('previous_projects');
            $table->integer('year_of_project');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviewer_requests');
    }
}
