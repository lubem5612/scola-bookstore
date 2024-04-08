<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->boolean('is_published')->index()->after('publication_info')->default(false);
            $table->boolean('status')->index()->after('number_of_views')->default(false);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('status', ['open', 'close'])->index()->after('type')->default('open');
        });
    }

    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropColumn('is_published');

            $table->dropIndex(['status']);
            $table->dropColumn(['status']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status']);
        });
    }
};