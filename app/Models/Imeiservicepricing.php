<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Imeiservicepricing extends Model
{
    protected $table = 'adminplus_imei_service';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_supplier',
        'purchasecost'
    ];

	    public function imei()
    {
        return $this->belongsTo('App\Models\Imeiservice','id','id');
    }
    public function nhacungcap()
    {
        return $this->belongsTo('App\Models\Supplier','id_supplier','id');
    }
}

