<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminplusSiteProfitDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adminplus_site_profit_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date_profit');
			$table->dateTime('date_added');
			$table->dateTime('date_updated');
			$table->float('imei_profit_amount', 10, 0);
			$table->float('reversed_amount', 10, 0);
			$table->float('imei_linked_profit', 10, 0);
			$table->float('file_profit_amount', 10, 0);
			$table->float('server_profit_amount', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adminplus_site_profit_details');
	}

}
