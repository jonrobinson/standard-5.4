<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrganizationSubdomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('organizations_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id');
            $table->integer('user_id');
            $table->string('unq_key')->unique();
            $table->string('role')->nulalble();
            $table->timestamps();

            $table->index('organization_id');
            $table->index('user_id');
        });

        Schema::create('user_onboardings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->string('email')->unique();
            $table->string('email_confirmation_code')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('organization_name')->nullable();
            $table->boolean('joining_an_organization')->default(0);
            $table->boolean('email_confirmed')->default(0);
            $table->boolean('accepted_terms_of_service')->default(0);
            $table->boolean('invite_others_page_seen')->default(0);
            $table->boolean('completed')->default(0);
            $table->integer('number_of_emails_sent')->default(0);
            $table->timestamp('email_confirmation_code_expires_at')->nullable();
            $table->timestamp('email_confirmation_code_sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organizations');
        Schema::drop('organizations_users');
        Schema::drop('user_onboardings');
    }
}
