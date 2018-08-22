<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Imeiservice extends Model
{
    protected $table = 'imei_service';
    public $timestamps = false;
	
	    public function clientgroupprice()
    {
        return $this->hasMany('App\Clientgroupprice','service_id','id');
    }
	    public function apiserverservices()
    {
        return $this->belongsTo('App\Apiserverservices','api_id','id');
    }
}

