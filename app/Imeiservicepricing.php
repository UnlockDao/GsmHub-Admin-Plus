<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Imeiservicepricing extends Model
{
    protected $table = 'adminplus_imei_service';
    public $timestamps = false;
    protected $fillable = [
        'id'
    ];

	    public function imei()
    {
        return $this->belongsTo('App\Imeiservice','id','id');
    }
    public function nhacungcap()
    {
        return $this->belongsTo('App\Supplier','id_supplier','id');
    }
}

