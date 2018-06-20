<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDatasourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datasources', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('type', 191);
			$table->string('unitid', 191)->nullable();
			$table->string('ip', 191)->nullable();
			$table->string('port', 191)->nullable();
			$table->text('options', 65535)->nullable();
			$table->text('data', 65535)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->boolean('active')->default(1);
			$table->integer('project_id')->unsigned()->index('datasources_project_id_foreign');
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
		Schema::drop('datasources');
	}

}
