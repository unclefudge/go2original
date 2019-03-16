<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetupAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('slug', 100)->unique()->nullable();
            $table->string('timezone', 255)->nullable();
            $table->string('dateformat', 25)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('banner', 255)->nullable();
            $table->dateTime('grade_adv')->nullable();
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('accounts');
    }
}
