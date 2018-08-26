<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SQL extends Migration
{


    public function createSupplier()
    {
        if (!Schema::hasTable('supplier')) {
            Schema::create('supplier', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->double('tigia');
                $table->double('phi');
            });
        }
    }

    public function imeiPricing()
    {
        if (!Schema::hasTable('imei_service_pricing')) {
            Schema::create('imei_service_pricing', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_nhacungcap')->nullable();
                $table->double('gianhap')->nullable();
            });
//            Schema::table('client_group_price', function (Blueprint $table) {
//                $table->double('discount')->change();
//            });
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
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
            DB::table('admin')->insert([
                'name' => 'Admin',
                'email' => 'admin',
                'password' => '$1$HLtDlotP$034OcaZtKDkjpYqrTvWwA0',
            ]);
        }

    }

    public function currencyPricing()
    {
        if (!Schema::hasTable('currency_pricing')) {
            Schema::create('currency_pricing', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('type');
                $table->integer('currency_id');

            });
            DB::table('currency_pricing')->insert([
                'type' => '1',
                'currency_id' => '145'
            ]);
        }

    }

}
