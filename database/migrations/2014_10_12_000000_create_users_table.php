<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 30)->unique();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('theme_id', 3)->nullable();
            $table->string('headline', 100)->nullable();
            $table->string('photo', 100)->nullable();
            $table->text('introduction')->nullable();
            $table->text('more_info')->nullable();
            $table->string('facebook', 50)->nullable();
            $table->string('instagram', 30)->nullable();
            $table->string('linkedin', 50)->nullable();
            $table->string('github', 30)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
