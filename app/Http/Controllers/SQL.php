<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SQL extends Migration
{

    public function run(){
        $this->clientGroup();
        $this->createSupplier();
        $this->imeiPricing();
        $this->servicePricing();
        $this->currencyPricing();
        $this->addServerservicepurchasecostnotvip();
    }

    public function createSupplier()
    {
        if (!Schema::hasTable('adminplus_supplier')) {
            Schema::create('adminplus_supplier', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->double('exchangerate');
                $table->double('transactionfee');
            });
        }
    }

    public function imeiPricing()
    {
        if (!Schema::hasTable('adminplus_imei_service')) {
            Schema::create('adminplus_imei_service', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_supplier')->nullable();
                $table->double('purchasecost')->nullable();
            });
//            Schema::table('client_group_price', function (Blueprint $table) {
//                $table->double('discount')->change();
//            });
        }
    }

    public function servicePricing()
    {
        if (!Schema::hasTable('adminplus_service_service')) {
            Schema::create('adminplus_service_service', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_supplier')->nullable();
                $table->double('purchasecost')->nullable();
            });
        }
    }

    public function clientGroup()
    {
        if (!Schema::hasColumn('client_group', 'chietkhau'))
        {
            Schema::table('client_group', function (Blueprint $table) {
                $table->integer('chietkhau');
            });
        }
    }

    public function Admin()
    {
        if (!Schema::hasTable('adminplus_admin')) {
            Schema::create('adminplus_admin', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
            DB::table('adminplus_admin')->insert([
                'name' => 'Admin',
                'email' => 'admin',
                'password' => '$1$HLtDlotP$034OcaZtKDkjpYqrTvWwA0',
            ]);
        }

    }

    public function currencyPricing()
    {
        if (!Schema::hasTable('adminplus_currency')) {
            Schema::create('adminplus_currency', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('type');
                $table->integer('currency_id');

            });
            DB::table('adminplus_currency')->insert([
                'type' => '1',
                'currency_id' => '145'
            ]);
        }

    }

    public function addServerservicepurchasecostnotvip()
    {
        if (!Schema::hasColumn('server_service_type_wise_price', 'purchase_cost_not_net'))
        {
            Schema::table('server_service_type_wise_price', function (Blueprint $table) {
                $table->double('purchase_cost_not_net');
            });
        }

    }

}
