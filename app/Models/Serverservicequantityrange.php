<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Serverservicequantityrange extends Model
{
    protected $table = 'server_service_quantity_range';
    public $timestamps = false;

    public function serverserviceclientgroupcredit()
    {
        return $this->hasMany('App\Serverserviceclientgroupcredit','server_service_range_id','id');
    }

    public function serverserviceusercredit()
    {
        return $this->hasMany('App\Serverserviceusercredit','server_service_range_id','id');
    }
    public function serverservicequantityrange()
    {
        return $this->belongsTo('App\Serverservice','server_service_id','id');
    }



}

