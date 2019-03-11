<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->nullable();
            $table->string('type', '50')->nullable();
            $table->string('subtype', '50')->nullable();
            $table->string('action', '255')->nullable();
            $table->unsignedInteger('ref')->nullable();
            $table->text('data')->nullable();

            // Foreign keys
            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_history');
    }
}
