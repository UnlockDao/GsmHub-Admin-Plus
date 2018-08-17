<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Imeiservicepricing extends Model
{
    protected $table = 'imei_service_pricing';
    public $timestamps = false;

	    public function imei()
    {
        return $this->belongsTo('App\Imeiservice','id_imei','id');
    }
    public function nhacungcap()
    {
        return $this->belongsTo('App\Supplier','id_nhacungcap','id');
    }
}

