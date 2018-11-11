<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name',500);
            $table->string('menu_description',500);
            $table->string('menu_type',500);
            $table->integer('parent_menus_id')->length(11)->default(null);
            $table->integer('modules_id')->length(10)->default(null);
            $table->string('icon_class',500);
            $table->string('menu_url',500);
            $table->integer('sort_number')->length(10)->default(null);
            $table->integer('created_by')->length(10)->default(0);
            $table->integer('updated_by')->length(10)->default(0);
            $table->timestamps();
            $table->string('status',500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
