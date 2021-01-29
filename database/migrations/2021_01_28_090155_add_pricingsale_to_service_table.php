<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricingsaleToServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_service_quantity_range', function (Blueprint $table) {
            $table->integer('sale')->nullable();
            $table->decimal('pricing_sale', 20, 8)->nullable();
        });

        Schema::table('server_service_type_wise_price', function (Blueprint $table) {
            $table->integer('sale')->nullable();
            $table->decimal('pricing_sale', 20, 8)->nullable();
            $table->decimal('pricingdefault_sale', 20, 8)->nullable();
        });

        Schema::table('server_service_user_credit', function (Blueprint $table) {
            $table->decimal('pricingdefault_sale', 20, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_service_quantity_range', function (Blueprint $table) {
            $table->dropColumn(['sale', 'pricing_sale']);
        });

        Schema::table('server_service_quantity_range', function (Blueprint $table) {
            $table->dropColumn(['sale', 'pricing_sale', 'pricingdefault_sale']);
        });

        Schema::table('server_service_user_credit', function (Blueprint $table) {
            $table->dropColumn(['pricingdefault_sale']);
        });
    }
}
