<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login_by')->default('email')->comment('login by username/email/mobile');
            $table->integer('username_minimum_length')->length(5)->default(5);
            $table->integer('username_maximum_length')->length(10)->default(25);
            $table->string('username_have_special_character')->default(0)->comment('0=no and 1=yes');
            $table->tinyInteger('maximum_login_same_user')->length(1)->default(1);
            $table->tinyInteger('maximum_login_perday')->length(2)->default(10);
            $table->integer('maximum_login_permonth')->length(4)->default(1000);
            $table->tinyInteger('maximum_login_attempt')->length(1)->default(5);
            $table->tinyInteger('maximum_login_attempt_reset_time')->length(3)->default(15)->comment('minimum 15 minutes');
            $table->tinyInteger('have_login_security_code')->length(1)->default(0)->comment('0=no , 1= yes');
            $table->tinyInteger('security_code_length')->length(1)->default(6);
            //$table->string('security_code')->comment('auto generate 6 digit code');
            $table->string('session_time')->default(30)->comment('default 30 minutes');
            $table->integer('password_minimum_length')->length(5)->default(6);
            $table->integer('password_maximum_length')->length(10)->default(12);
            $table->tinyInteger('password_have_special_character')->length(1)->default(0)->comment('0=no and 1=yes');
            $table->string('password_special_character',100)->nullable()->default(null);
            $table->tinyInteger('password_have_expire_day')->length(1)->default(0)->comment('0=no, 1=yes');
            $table->integer('password_expire_days')->length(5)->default(0);
            $table->tinyInteger('last_used_password')->length(1)->default(5)->comment('do not use last 5 used password');
            $table->tinyInteger('have_default_password')->length(1)->default(0)->comment('0=no, 1=yes');
            $table->integer('default_password_length')->default(8);
            $table->string('default_password',100)->nullable()->default(null);
            $table->tinyInteger('have_password_plaintext')->length(1)->default(1)->comment('0=no, 1=yes');
            //$table->string('password_plaintext',50);
            $table->tinyInteger('username_email_phone_as_password')->length(1)->default(1)->comment('0=no, 1=yes');
            $table->text('password_prohibited_words')->nullable()->default(null)->comment('this words are not as a password');
            $table->tinyInteger('user_activation_policy')->length(1)->default(1)->comment('0=no, 1=yes');
            $table->integer('created_by')->length(10);
            $table->integer('updated_by')->length(10)->default(0);
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
        Schema::dropIfExists('user_settings');
    }
}
