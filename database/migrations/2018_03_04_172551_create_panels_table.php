<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePanelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('panels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->integer('order')->default(0);
			$table->string('type', 191);
			$table->string('data', 191)->nullable();
			$table->string('options', 191)->nullable();
			$table->boolean('active')->default(1);
			$table->integer('dashboard_id')->unsigned()->index('panels_dashboard_id_foreign');
			$table->integer('datapoint_id')->unsigned()->index('panels_datapoint_id_foreign');
			$table->integer('datasource_id')->unsigned()->index('panels_datasource_id_foreign');
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
		Schema::drop('panels');
	}

}
