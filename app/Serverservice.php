<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverservice extends Model
{
    protected $table = 'server_service';
    public $timestamps = false;
    public function apiserverservicetypeprice()
    {
        return $this->hasMany('App\Apiserverservicetypeprice','api_server_service_id','api_id');
    }
    public function serverservicequantityrange()
    {
        return $this->hasMany('App\Serverservicequantityrange','server_service_id','id');
    }
    public function serverservicetypewiseprice()
    {
        return $this->hasMany('App\Serverservicetypewiseprice','server_service_id','id');
    }
    public function servicepricing()
    {
        return $this->belongsTo('App\Serviceservicepricing','id','id');
    }

}

