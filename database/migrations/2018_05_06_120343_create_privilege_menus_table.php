<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivilegeMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege_menus', function (Blueprint $table) {
            $table->increments('menus_id');
            $table->integer('user_levels_id')->nulable();
            $table->integer('users_id')->nulable();
            $table->unique(['menus_id', 'user_levels_id']);
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
        Schema::dropIfExists('privilege_menus');
    }
}
