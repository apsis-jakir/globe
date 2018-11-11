<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description');
            $table->integer('created_by')->length(10)->default(0);
            $table->timestamp('created');
            $table->integer('updated_by')->length(10)->default(0);
            $table->timestamp('updated')->useCurrent();
            $table->enum('status',['Active', 'Inactive']);
            $table->tinyInteger('privilege_edit')->length(1)->default(0);
            $table->tinyInteger('privilege_delete')->length(1)->default(0);
            $table->tinyInteger('privilege_add')->length(1)->default(0);
            $table->tinyInteger('privilege_view_all')->length(1)->default(0);
            $table->tinyInteger('all_privilege')->length(1);

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
        Schema::dropIfExists('user_levels');
    }
}
