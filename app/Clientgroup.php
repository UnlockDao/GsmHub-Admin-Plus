<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Clientgroup extends Model
{
    protected $table = 'client_group';
    protected $fillable = ['id','chietkhau'];
    public $timestamps = false;
}

