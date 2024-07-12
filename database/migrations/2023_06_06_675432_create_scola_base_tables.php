<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fc_organizations')) {
            Schema::create('fc_organizations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('org', 191)->index('fc_organizations_org_index');
                $table->string('domain', 191)->index('fc_organizations_domain_index');
                $table->string('full_url', 191)->index('fc_organizations_full_url_index');
                $table->string('subdomain', 191)->index('fc_organizations_subdomain_index');
                $table->boolean('is_local_default_organization')->default(0);
                $table->boolean('is_shut_down')->default(0);
                $table->text('shut_down_reason')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
            });
        }

        if (!Schema::hasTable('fc_departments')) {
            Schema::create('fc_departments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('key', 191);
                $table->string('long_name', 191);
                $table->boolean('is_unit')->default(0);
                $table->string('email', 191)->nullable();
                $table->string('telephone', 191)->nullable();
                $table->string('physical_location', 191)->nullable();
                $table->binary('logo_image');
                $table->boolean('is_ad_import')->default(0);
                $table->string('ad_type', 191)->nullable();
                $table->string('ad_key', 191)->nullable();
                $table->text('ad_data')->nullable();
                $table->uuid('parent_id')->nullable();
                $table->uuid('organization_id')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();

                $table->foreign('organization_id', 'fc_departments_organization_id_foreign')->references('id')->on('fc_organizations');
            });
        }

        if (!Schema::hasTable('fc_users')) {
            Schema::create('fc_users', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('email', 191);
                $table->string('password', 191);
                $table->string('telephone', 191);
                $table->string('title', 191)->nullable();
                $table->string('first_name', 191)->nullable();
                $table->string('middle_name', 191)->nullable();
                $table->string('last_name', 191)->nullable();
                $table->string('user_code', 191)->nullable();
                $table->string('preferred_name', 191)->nullable();
                $table->string('physical_location', 191)->nullable();
                $table->uuid('department_id')->nullable();
                $table->string('job_title', 191)->nullable();
                $table->dateTime('last_loggedin_at')->nullable();
                $table->boolean('is_disabled')->default(0);
                $table->text('disable_reason')->nullable();
                $table->dateTime('disabled_at')->nullable();
                $table->uuid('disabling_user_id')->nullable();
                $table->binary('profile_image')->nullable();
                $table->uuid('organization_id')->nullable();
                $table->boolean('is_ad_import')->default(0);
                $table->string('ad_type', 191)->nullable();
                $table->string('ad_key', 191)->nullable();
                $table->text('ad_data')->nullable();
                $table->string('presence_status', 191)->default('available');
                $table->text('leave_delegation_notes')->nullable();
                $table->integer('ranking_ordinal')->default(1);
                $table->boolean('is_first_login')->default(0);
                $table->rememberToken();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
                $table->string('gender', 191)->nullable();

                $table->unique(['email', 'organization_id'], 'fc_users_email_organization_id_unique');
                $table->foreign('department_id', 'fc_users_department_id_foreign')->references('id')->on('fc_departments');
                $table->foreign('organization_id', 'fc_users_organization_id_foreign')->references('id')->on('fc_organizations');
            });
        }

        Schema::table('fc_users', function (Blueprint $table) {
            if (!Schema::hasColumn('fc_users', 'role')) {
                $table->string('role', 30)->nullable()->after('password');
            }
            if (!Schema::hasColumn('fc_users', 'is_verified')) {
                $table->boolean('is_verified')->nullable()->after('password');
            }
            if (!Schema::hasColumn('fc_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('fc_users', 'token')) {
                $table->string('token')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('fc_users', 'image_url')) {
                $table->string('image_url', 600)->nullable()->after('profile_image');
            }
        });

        Schema::dropIfExists('users');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fc_organizations');
        Schema::dropIfExists('fc_departments');
        Schema::dropIfExists('fc_users');
    }
};