<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Imeiservicegroup extends Model
{
    protected $table = 'imei_service_group';

    public function imeigroup()
    {
        return $this->belongsTo('App\Imeiservice','id','imei_service_group_id');
    }

}

