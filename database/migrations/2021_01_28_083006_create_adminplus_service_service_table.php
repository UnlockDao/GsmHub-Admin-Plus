<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminplusServiceServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adminplus_service_service', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_supplier')->nullable();
			$table->decimal('purchasecost', 20, 4)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adminplus_service_service');
	}

}
