<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSensordataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sensordata', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('type', 191);
			$table->string('unitid', 191);
			$table->string('ip', 191);
			$table->string('port', 191);
			$table->string('address', 191);
			$table->string('data', 191);
			$table->string('fc', 191);
			$table->string('quantity', 191);
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
		Schema::drop('sensordata');
	}

}
