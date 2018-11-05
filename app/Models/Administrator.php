<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Administrator extends Model
{
    protected $table = 'administrator';
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\User','user_id','user_id');
    }

}

