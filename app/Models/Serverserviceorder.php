<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Serverserviceorder extends Model
{
    protected $table = 'server_service_order';
    public $timestamps = false;

    public function serverservice()
    {
        return $this->belongsTo('App\Models\Serverservice','server_service_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','user_id');
    }
}

