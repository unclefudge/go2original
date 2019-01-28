<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schools
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('grade_from', 25)->nullable();
            $table->string('grade_to', 25)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('aid')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('gender', 10)->nullable();
            $table->dateTime('dob')->nullable();

            // Contact Details
            $table->string('email', 255)->nullable();
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
            $table->string('photo', 255)->nullable();
            $table->string('wwc_no', 50)->nullable();
            $table->dateTime('wwc_exp')->nullable();
            $table->dateTime('wwc_verified')->nullable();
            $table->unsignedInteger('wwc_verified_by')->nullable();

            $table->string('minhub', 50)->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('aid')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People Medical
        Schema::create('people_medical', function (Blueprint $table) {
            $table->increments('id');
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
            $table->unsignedInteger('pid')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->unsignedInteger('verified_by')->nullable();

            // Foreign keys
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People Notes
        Schema::create('people_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->nullable();
            $table->text('info')->nullable();
            $table->tinyInteger('private')->default(1);

            // Foreign keys
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People Relationships
        Schema::create('people_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->nullable();
            $table->string('relationship', 50)->nullable();
            $table->unsignedInteger('related2')->nullable();
            $table->tinyInteger('primary')->default(0);

            // Foreign keys
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People Linked
        Schema::create('people_linked', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->nullable();
            $table->unsignedInteger('linked2')->nullable();
            $table->dateTime('linked2_modified')->nullable();

            // Foreign keys
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('linked2')->references('id')->on('people')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // People Linked
        Schema::create('users_people', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->unsignedInteger('pid')->nullable();
            $table->tinyInteger('primary')->default(1);

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');

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
        Schema::dropIfExists('users_people');
        Schema::dropIfExists('people_linked');
        Schema::dropIfExists('people_relationships');
        Schema::dropIfExists('people_notes');
        Schema::dropIfExists('people_medical');
        Schema::dropIfExists('people');
        Schema::dropIfExists('schools');
    }
}
