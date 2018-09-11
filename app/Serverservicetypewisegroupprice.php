<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serverservicetypewisegroupprice extends Model
{
    protected $table = 'server_service_type_wise_groupprice';
    public $timestamps = false;
    protected $fillable = [
        'server_service_id',
        'service_type_id',
        'group_id',
        'currency',
        'amount'
    ];


}

