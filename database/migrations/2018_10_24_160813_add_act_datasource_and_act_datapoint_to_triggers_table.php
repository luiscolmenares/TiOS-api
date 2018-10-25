<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActDatasourceAndActDatapointToTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('triggers', function (Blueprint $table) {
            $table->integer('act_datasource_id')->nullable();
            $table->integer('act_datapoint_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('triggers', function (Blueprint $table) {
            //
        });
    }
}
