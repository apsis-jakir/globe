<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255)->nullable();
            $table->integer('product_categories_id')->unsigned()->nullable()->index();
            $table->string('description', 1000)->nullable();
            $table->integer('created_by')->unsigned()->nullable()->index();
            $table->integer('updated_by')->unsigned()->nullable()->index();
            $table->boolean('is_active')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
