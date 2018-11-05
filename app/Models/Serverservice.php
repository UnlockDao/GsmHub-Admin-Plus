<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Serverservice extends Model
{
    protected $table = 'server_service';
    public $timestamps = false;
    public function apiserverservicetypeprice()
    {
        return $this->hasMany('App\Models\Apiserverservicetypeprice','api_server_service_id','api_id');
    }
    public function serverservicequantityrange()
    {
        return $this->hasMany('App\Models\Serverservicequantityrange','server_service_id','id');
    }
    public function serverservicetypewiseprice()
    {
        return $this->hasMany('App\Models\Serverservicetypewiseprice','server_service_id','id')->orderBy('amount');
    }
    public function servicepricing()
    {
        return $this->belongsTo('App\Models\Serviceservicepricing','id','id');
    }
    public function apiserverservices()
    {
        return $this->belongsTo('App\Models\Apiserverservices','api_id','id');
    }

}

