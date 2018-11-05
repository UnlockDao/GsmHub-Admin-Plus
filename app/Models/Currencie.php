<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Currencie extends Model
{
    protected $table = 'currencies';
    public $timestamps = false;

    public function currenciepricing()
    {
        return $this->belongsTo('App\Models\Currenciepricing','id','currency_id');
    }
}

