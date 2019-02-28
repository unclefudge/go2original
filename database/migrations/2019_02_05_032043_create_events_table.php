<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Events
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->tinyInteger('recur')->default(0);
            $table->string('code', 50)->nullable();
            $table->string('day', 20)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('grades', 250)->nullable();
            $table->string('background', 250)->nullable();
            $table->text('notes')->nullable();
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

        // Events Instance
        Schema::create('events_instance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('day', 20)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('grades', 250)->nullable();
            $table->string('background', 250)->nullable();
            $table->string('minhub', 50)->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('eid')->nullable();
            $table->unsignedInteger('aid')->nullable();

            // Foreign keys
            $table->foreign('eid')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Events Form
        Schema::create('events_form', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->nullable();
            $table->tinyInteger('custom')->default(0);
            $table->tinyInteger('checkin')->default(0);
            $table->unsignedInteger('eid')->nullable();

            // Foreign keys
            $table->foreign('eid')->references('id')->on('events')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // Events Access
        Schema::create('events_access', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->nullable();
            $table->unsignedInteger('eid')->nullable();
            $table->tinyInteger('level')->default(0);
            $table->tinyInteger('checkin')->default(0);

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('eid')->references('id')->on('events')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // Event Attendance
        Schema::create('events_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eid')->nullable();
            $table->unsignedInteger('pid')->nullable();
            $table->dateTime('in')->nullable();
            $table->dateTime('out')->nullable();
            $table->string('method', 25)->nullable();

            // Foreign keys
            $table->foreign('eid')->references('id')->on('events_instance')->onDelete('cascade');
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
        Schema::dropIfExists('events_attendance');
        Schema::dropIfExists('events_access');
        Schema::dropIfExists('events_form');
        Schema::dropIfExists('events_instance');
        Schema::dropIfExists('events');
    }
}
