<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('resources')) return;
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('author_id')->constrained('authors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title', 500)->index();
            $table->string('subtitle', 500)->nullable()->index();
            $table->string('preface', 766)->nullable()->comment('books');
            $table->string('source', 766)->nullable()->comment('research, website for journal,');
            $table->string('page_url', 766)->nullable()->comment('research');
            $table->string('pages')->nullable();
            $table->json('contributors')->nullable();
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->string('ISBN', 100)->unique()->index()->comment('books');
            $table->json('publication_info')->nullable()->comment('date, publisher, place');
            $table->json('report_info')->nullable()->comment('report_number, organization, funding, license');
            $table->json('editors')->nullable()->comment('festchrisfts');
            $table->json('dedicatees')->nullable()->comment('festchrisfts');
            $table->json('journal_info')->nullable()->comment('volume, page_start, page_end, editorial');
            $table->json('editorial_board_members')->nullable()->comment('journal');
            $table->string('edition', 80)->nullable()->index()->comment('books');
            $table->string('keywords', 766)->nullable()->index();
            $table->string('summary', 766)->nullable()->comment('books');
            $table->string('overview', 766)->nullable()->comment('books, research');
            $table->json('conference')->nullable()->comment('name, year, date, location');
            $table->json('institutional_affiliations')->nullable()->comment('conference, reports');
            $table->string('file_path', 700)->nullable();
            $table->string('cover_image', 700)->nullable();
            $table->decimal('price', 15, 5)->index();
            $table->float('percentage_share', 5, 2)->default(50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
