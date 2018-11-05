<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Serverservicetypewiseprice extends Model
{
    protected $table = 'server_service_type_wise_price';
    public $timestamps = false;

    public function serverservicetypewisegroupprice()
    {
        return $this->hasMany('App\Serverservicetypewisegroupprice','server_service_id','server_service_id');
    }
    public function servicetypegroupprice()
    {
        return $this->hasMany('App\Serverservicetypewisegroupprice','service_type_id','id');
    }
    public function adminplus_service()
    {
        return $this->belongsTo('App\Serviceservicepricing','server_service_id','id');
    }
    public function apiservicetypewisepriceid()
    {
        return $this->hasMany('App\Apiserverservicetypeprice','id','api_service_type_wise_price_id');
    }


}

