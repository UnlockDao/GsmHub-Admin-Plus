<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverserviceclientgroupcredit extends Model
{
    protected $table = 'server_service_client_group_credit';
    public $timestamps = false;
    protected $fillable = [
        'server_service_range_id',
        'client_group_id',
        'credit',
        'currency'
    ];
    public function serverservicequantityrange()
    {
        return $this->belongsTo('App\Serverservicequantityrange','server_service_range_id','id');
    }

}

