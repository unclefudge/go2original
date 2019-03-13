<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraUsersTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dateTime('media_consent_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('gender', 10)->nullable();
            $table->dateTime('dob')->nullable();

            // Contact Details
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
            $table->string('media_consent',10)->nullable();
            $table->dateTime('media_consent_at')->nullable();
            $table->unsignedInteger('media_consent_by')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('wwc_no', 50)->nullable();
            $table->dateTime('wwc_exp')->nullable();
            $table->dateTime('wwc_verified')->nullable();
            $table->unsignedInteger('wwc_verified_by')->nullable();

            $table->string('minhub', 50)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('aid')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function($table) {
            $table->dropColumn('media_consent_at');
        });

        Schema::table('user', function($table) {
            $table->dropColumn('aid');
            $table->dropColumn('notes');
            $table->dropColumn('minhub');
            $table->dropColumn('wwc_verified_by');
            $table->dropColumn('wwc_verified');
            $table->dropColumn('wwc_exp');
            $table->dropColumn('wwc_no');
            $table->dropColumn('photo');
            $table->dropColumn('media_consent_at');
            $table->dropColumn('media_consent_by');
            $table->dropColumn('media_consent_at');
            $table->dropColumn('media_consent');
            $table->dropColumn('school_id');
            $table->dropColumn('grade');
            $table->dropColumn('country');
            $table->dropColumn('postcode');
            $table->dropColumn('state');
            $table->dropColumn('suburb');
            $table->dropColumn('address2');
            $table->dropColumn('address');
            $table->dropColumn('instagram');
            $table->dropColumn('phone');
            $table->dropColumn('gender');
            $table->dropColumn('type');
            $table->dropColumn('lastname');
            $table->dropColumn('firstname');
        });
    }
}
