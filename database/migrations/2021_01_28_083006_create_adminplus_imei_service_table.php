<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminplusImeiServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adminplus_imei_service', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_supplier')->nullable();
			$table->decimal('purchasecost', 20, 4)->nullable();
			$table->integer('sale')->nullable();
			$table->decimal('pricing_sale', 20, 4)->nullable();
			$table->decimal('pricingdefault_sale', 20, 4)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adminplus_imei_service');
	}

}
