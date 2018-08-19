<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Clientgroupprice extends Model
{
    public $timestamps = false;
    protected $table = 'client_group_price';

    public function chietkhau()
    {
        return $this->belongsTo('App\Clientgroup', 'group_id', 'id');
    }
}

