<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTriggersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('triggers', function(Blueprint $table)
		{
			$table->foreign('datapoint_id')->references('id')->on('datapoints')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('datasource_id')->references('id')->on('datasources')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('project_id')->references('id')->on('projects')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('trigger_action_type_id')->references('id')->on('trigger_action_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('triggers', function(Blueprint $table)
		{
			$table->dropForeign('triggers_datapoint_id_foreign');
			$table->dropForeign('triggers_datasource_id_foreign');
			$table->dropForeign('triggers_project_id_foreign');
			$table->dropForeign('triggers_trigger_action_type_id_foreign');
		});
	}

}
