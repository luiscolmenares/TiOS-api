<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('type', 191);
			$table->integer('menu_order')->nullable();
			$table->integer('role_id')->unsigned()->index('menus_role_id_foreign');
			$table->integer('parent_id')->nullable();
			$table->string('data_ui_sref', 191)->nullable();
			$table->string('data_ui_sref_active', 191)->nullable();
			$table->string('data_ng_class', 191)->nullable();
			$table->string('data_toggle', 191)->nullable();
			$table->string('ng_click', 191)->nullable();
			$table->string('icon_class', 191)->nullable();
			$table->string('span_class', 191)->nullable();
			$table->integer('active')->nullable();
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
		Schema::drop('menus');
	}

}
