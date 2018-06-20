<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTriggersNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('triggers_notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('trigger_action_type_id')->nullable();
			$table->integer('organization_id')->nullable();
			$table->integer('project_id')->nullable();
			$table->integer('datasource_id')->nullable();
			$table->integer('datapoint_id')->nullable();
			$table->text('message')->nullable();
			$table->string('recipients', 191)->nullable();
			$table->integer('viewed')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('triggers_notifications');
	}

}
