<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Clientgroupprice extends Model
{
    protected $table = 'client_group_price';
    public $timestamps = false;
    public function chietkhau()
    {
        return $this->belongsTo('App\Clientgroup','group_id','id');
    }
}

