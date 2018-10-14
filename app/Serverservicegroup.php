<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverservicegroup extends Model
{
    protected $table = 'server_service_group';
    public $timestamps = false;

    public function servergroup()
    {
        return $this->belongsTo('App\Serverservice','id','server_service_group_id');
    }
}

