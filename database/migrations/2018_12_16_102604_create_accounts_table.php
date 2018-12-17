<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
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
            $table->string('slug', 100)->unique();
            $table->string('timezone', 255)->unique();
            $table->string('dateformat', 25)->unique();
            $table->string('country', 100)->unique();
            $table->string('banner', 255)->unique();
            $table->dateTime('grade_adv')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('accounts_security', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aid')->nullable();
            $table->unsignedInteger('uid')->nullable();
            $table->tinyInteger('people')->nullable();
            $table->tinyInteger('events')->nullable();
            $table->tinyInteger('groups')->nullable();
            $table->tinyInteger('settings')->nullable();
            $table->tinyInteger('billing')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');

            // Modify info
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('accounts_linked', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aid')->nullable();
            $table->unsignedInteger('linked2')->nullable();
            $table->dateTime('expiry')->nullable();

            // Foreign keys
            $table->foreign('aid')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('linked2')->references('id')->on('accounts')->onDelete('cascade');

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
        Schema::dropIfExists('accounts_linked');
        Schema::dropIfExists('accounts_security');
        Schema::dropIfExists('accounts');
    }
}
