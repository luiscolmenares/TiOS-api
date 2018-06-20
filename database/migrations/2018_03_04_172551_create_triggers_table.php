<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTriggersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('triggers', function(Blueprint $table)
		{
			$table->string('name', 191);
			$table->string('operator', 191);
			$table->string('value', 191);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('trigger_action_type_id')->unsigned()->index('triggers_trigger_action_type_id_foreign');
			$table->integer('project_id')->unsigned()->index('triggers_project_id_foreign');
			$table->integer('datasource_id')->unsigned()->index('triggers_datasource_id_foreign');
			$table->integer('datapoint_id')->unsigned()->index('triggers_datapoint_id_foreign');
			$table->boolean('active')->default(1);
			$table->text('custommessage')->nullable();
			$table->string('recipients')->nullable();
			$table->increments('id');
			$table->string('notes', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('triggers');
	}

}
