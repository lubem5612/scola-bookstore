<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bs_payment_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->string('account_number', 20)->index();
            $table->string('account_name', 100)->index();
            $table->string('account_status', 20)->index()->comment('active, inactive');
            $table->string('bank_name', 100)->index();
            $table->string('bank_code', 10)->index()->nullable();
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });

        Schema::table('bs_authors', function (Blueprint $table) {
            $table->dropColumn('bank_info');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bs_payment_details');

        Schema::table('bs_authors', function (Blueprint $table) {
            $table->json('bank_info')->after('bio')->nullable()->comment('account_no, account_name, bank_code');
        });
    }
};