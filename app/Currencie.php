<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Currencie extends Model
{
    protected $table = 'currencies';
    public $timestamps = false;

    public function currenciepricing()
    {
        return $this->belongsTo('App\Currenciepricing','id','currency_id');
    }
}

