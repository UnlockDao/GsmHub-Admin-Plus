<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Apiserverservicetypeprice extends Model
{
    protected $table = 'api_server_service_type_wise_price';
    public $timestamps = false;

    public function serverservice()
    {
        return $this->belongsTo('App\Serverservice','api_server_service_id','api_id');
    }
}

