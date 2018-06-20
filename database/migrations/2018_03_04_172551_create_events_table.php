<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->nullable();
			$table->string('title', 191);
			$table->string('description', 191)->nullable();
			$table->string('action', 191)->nullable();
			$table->string('valueFrom', 191)->nullable();
			$table->string('ValueTo', 191)->nullable();
			$table->boolean('allDay')->nullable();
			$table->string('start', 191)->nullable();
			$table->string('end', 191)->nullable();
			$table->string('url', 191)->nullable();
			$table->string('className', 191)->nullable();
			$table->boolean('editable')->nullable();
			$table->boolean('startEditable')->nullable();
			$table->boolean('durationEditable')->nullable();
			$table->boolean('resourceEditable')->nullable();
			$table->string('rendering', 191)->nullable();
			$table->boolean('overlap')->nullable();
			$table->string('constraint', 191)->nullable();
			$table->string('color', 191)->nullable();
			$table->string('backgroundColor', 191)->nullable();
			$table->string('borderColor', 191)->nullable();
			$table->string('textColor', 191)->nullable();
			$table->integer('active')->nullable();
			$table->integer('organization_id')->unsigned()->index('events_organization_id_foreign');
			$table->integer('project_id')->unsigned()->index('events_project_id_foreign');
			$table->integer('datasource_id');
			$table->integer('datapoint_id');
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
		Schema::drop('events');
	}

}
