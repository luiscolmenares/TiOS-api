<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDatapointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datapoints', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('type', 191);
			$table->string('unitid', 191)->nullable();
			$table->string('address', 191);
			$table->text('data', 65535);
			$table->text('options', 65535);
			$table->string('active', 191)->nullable();
			$table->string('notes', 191)->nullable();
			$table->integer('datasource_id')->unsigned()->index('datapoints_datasource_id_foreign');
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
		Schema::drop('datapoints');
	}

}
