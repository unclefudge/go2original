<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseholdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('households', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->unsignedInteger('uid')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('aid')->nullable();

            // Foreign keys
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('users_household', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hid')->nullable();
            $table->unsignedInteger('uid')->nullable();

            // Foreign keys
            $table->foreign('hid')->references('id')->on('households')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('users_household');
        Schema::dropIfExists('households');
    }
}
