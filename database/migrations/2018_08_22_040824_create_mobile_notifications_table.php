<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();;
            $table->string('space')->nullable();
            $table->string('topic')->nullable();
            $table->string('value')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('timestamp')->nullable();
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
        Schema::dropIfExists('mobile_notifications');
    }
}
