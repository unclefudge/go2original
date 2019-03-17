<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            // Personal Details
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('gender', 10)->nullable();
            $table->dateTime('dob')->nullable();

            // Contact Details
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('instagram', 50)->nullable();

            $table->string('address', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('suburb', 150)->nullable();
            $table->string('state', 40)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('country', 100)->nullable();

            // Additional fields
            $table->string('grade', 25)->nullable();
            $table->unsignedInteger('school_id')->nullable();
            $table->dateTime('media_consent')->nullable();
            $table->unsignedInteger('media_consent_by')->nullable();
            $table->dateTime('media_consent_at')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('wwc_no', 50)->nullable();
            $table->dateTime('wwc_exp')->nullable();
            $table->dateTime('wwc_verified')->nullable();
            $table->unsignedInteger('wwc_verified_by')->nullable();
            $table->string('minhub', 50)->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('aid')->nullable();

            // Login Details
            $table->tinyInteger('login')->default(0);
            $table->string('username', 50)->unique()->nullable();
            $table->string('password', 60)->nullable();
            $table->timestamp('password_reset')->nullable();
            $table->string('activation')->nullable();
            $table->string('last_ip', 25)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // User Medical
        Schema::create('users_medical', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->string('emergency_name1', 255)->nullable();
            $table->string('emergency_phone1', 50)->nullable();
            $table->string('emergency_name2', 255)->nullable();
            $table->string('emergency_phone2', 50)->nullable();
            $table->string('medicare_num', 50)->nullable();
            $table->string('medicare_pos', 10)->nullable();
            $table->dateTime('medicare_exp')->nullable();
            $table->string('privatehealth_name', 255)->nullable();
            $table->string('privatehealth_num', 50)->nullable();
            $table->string('allergies')->nullable();
            $table->tinyInteger('anaphylactic')->nullable();
            $table->string('dietary')->nullable();
            $table->text('medical')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->unsignedInteger('verified_by')->nullable();

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // User Notes
        Schema::create('users_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->text('info')->nullable();
            $table->tinyInteger('private')->default(1);

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // User Relationships
        Schema::create('users_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->string('relationship', 50)->nullable();
            $table->unsignedInteger('related2')->nullable();
            $table->tinyInteger('primary')->default(0);

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // User History
        Schema::create('users_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->string('type', '50')->nullable();
            $table->string('subtype', '50')->nullable();
            $table->string('action', '255')->nullable();
            $table->unsignedInteger('ref')->nullable();
            $table->text('data')->nullable();

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Users Linked
        Schema::create('users_linked', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->unsignedInteger('linked2')->nullable();
            $table->dateTime('linked2_modified')->nullable();

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('linked2')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_linked');
        Schema::dropIfExists('users_history');
        Schema::dropIfExists('users_relationships');
        Schema::dropIfExists('users_notes');
        Schema::dropIfExists('users_medical');
        Schema::dropIfExists('users');
    }
}
