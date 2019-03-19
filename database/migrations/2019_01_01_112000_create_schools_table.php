<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
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

        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->tinyInteger('order')->nullable;
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

        Schema::create('schools_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sid')->nullable();
            $table->unsignedInteger('gid')->nullable();

            // Foreign keys
            $table->foreign('sid')->references('id')->on('schools');
            $table->foreign('gid')->references('id')->on('grades')->onDelete('cascade');

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
        Schema::dropIfExists('schools_grades');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('schools');
    }
}
