<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Imeiserviceorder extends Model
{
    protected $table = 'imei_service_order';
    public $timestamps = false;

    public function imeiservice()
    {
        return $this->belongsTo('App\Models\Imeiservice','imei_service_id','id');
    }
}

