<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('phone', 191)->nullable();
			$table->string('name', 191);
			$table->string('email', 191)->unique();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->boolean('active')->default(1);
			$table->timestamps();
			$table->softDeletes();
			$table->string('notes', 191)->nullable();
			$table->integer('role_id')->unsigned()->default(1);
			$table->integer('organization_id')->unsigned()->default(1);
			$table->boolean('active_sms')->default(1);
			$table->boolean('active_email')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
