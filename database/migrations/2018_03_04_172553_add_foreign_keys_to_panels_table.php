<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPanelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('panels', function(Blueprint $table)
		{
			$table->foreign('dashboard_id')->references('id')->on('dashboards')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('datapoint_id')->references('id')->on('datapoints')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('panels', function(Blueprint $table)
		{
			$table->dropForeign('panels_dashboard_id_foreign');
			$table->dropForeign('panels_datapoint_id_foreign');
			$table->dropForeign('panels_datasource_id_foreign');
		});
	}

}
