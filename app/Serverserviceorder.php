<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverserviceorder extends Model
{
    protected $table = 'server_service_order';
    public $timestamps = false;

    public function serverservice()
    {
        return $this->belongsTo('App\Serverservice','server_service_id','id');
    }
}

