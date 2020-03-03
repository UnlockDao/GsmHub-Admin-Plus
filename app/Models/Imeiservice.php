<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Imeiservice extends Model
{
    protected $table = 'imei_service';
    public $fillable = [
        'service_name',
        'imei_service_group_id',
        'process_time',
        'time_unit',
        'service_information',
        'purchase_cost',
        'credit',
        ];
    public $timestamps = false;

	    public function clientgroupprice()
    {
        return $this->hasMany('App\Models\Clientgroupprice','service_id','id');
    }
	    public function apiserverservices()
    {
        return $this->belongsTo('App\Models\Apiserverservices','api_id','id');
    }
    public function imeipricing()
    {
        return $this->belongsTo('App\Models\Imeiservicepricing','id','id');
    }
}

