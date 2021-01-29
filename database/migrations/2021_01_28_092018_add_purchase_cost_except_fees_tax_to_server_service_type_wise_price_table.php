<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseCostExceptFeesTaxToServerServiceTypeWisePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_service_type_wise_price', function (Blueprint $table) {
            $table->decimal('purchase_cost_except_fees', 20, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_service_type_wise_price', function (Blueprint $table) {
            $table->dropColumn(['purchase_cost_except_fees']);
        });
    }
}
