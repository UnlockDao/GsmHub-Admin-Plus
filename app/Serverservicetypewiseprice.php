<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverservicetypewiseprice extends Model
{
    protected $table = 'server_service_type_wise_price';
    public $timestamps = false;

    public function serverservicetypewisegroupprice()
    {
        return $this->hasMany('App\Serverservicetypewisegroupprice','server_service_id','server_service_id');
    }

}

