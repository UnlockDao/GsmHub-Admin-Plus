<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Serviceservicepricing extends Model
{
    protected $table = 'adminplus_service_service';
    public $timestamps = false;

	    public function service()
    {
        return $this->belongsTo('App\Serverservice','id','id');
    }
    public function nhacungcap()
    {
        return $this->belongsTo('App\Supplier','id_supplier','id');
    }
}

