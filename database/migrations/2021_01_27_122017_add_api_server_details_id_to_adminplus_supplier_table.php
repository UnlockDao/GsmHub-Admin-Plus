<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiServerDetailsIdToAdminplusSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adminplus_supplier', function (Blueprint $table) {
            $table->string('api_server_details_id')->nullable()->after('api_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adminplus_supplier', function (Blueprint $table) {
            $table->dropColumn(['api_server_details_id']);
        });
    }
}
