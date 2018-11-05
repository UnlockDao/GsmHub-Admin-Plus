<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Apiserverservicetypeprice extends Model
{
    protected $table = 'api_server_service_type_wise_price';
    public $timestamps = false;

    public function serverservice()
    {
        return $this->belongsTo('App\Models\Serverservice','api_server_service_id','api_id');
    }
}

