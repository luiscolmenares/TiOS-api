<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDatapointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('datapoints', function(Blueprint $table)
		{
			$table->foreign('datasource_id')->references('id')->on('datasources')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('datapoints', function(Blueprint $table)
		{
			$table->dropForeign('datapoints_datasource_id_foreign');
		});
	}

}
