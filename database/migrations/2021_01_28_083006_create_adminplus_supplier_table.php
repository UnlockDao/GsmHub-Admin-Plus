<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminplusSupplierTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adminplus_supplier', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->float('exchangerate', 10, 0);
			$table->float('transactionfee', 10, 0)->default(0);
			$table->string('site_username')->nullable();
			$table->string('site_password')->nullable();
			$table->string('site_url')->nullable();
			$table->integer('api_type')->nullable();
			$table->string('info')->nullable();
			$table->string('api_key')->nullable();
			$table->string('api_server_details_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adminplus_supplier');
	}

}
