<?php


namespace App\Models;


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
        return $this->belongsTo('App\Models\Serverservicequantityrange','server_service_range_id','id');
    }
    public function clientgroup()
    {
        return $this->belongsTo('App\Models\Clientgroup','client_group_id','id');
    }


}

