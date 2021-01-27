<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Serverservicepricing extends Model
{
    protected $table = 'adminplus_service_service';
    public $timestamps = false;
    protected $fillable = [
        'id'
    ];

	    public function service()
    {
        return $this->belongsTo('App\Models\Serverservice','id','id');
    }
    public function nhacungcap()
    {
        return $this->belongsTo('App\Models\Supplier','id_supplier','id');
    }
}

