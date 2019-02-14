<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Supplier extends Model
{
    protected $table = 'adminplus_supplier';
    protected $fillable = [
        'name','type'
    ];
    public $timestamps = false;

    function userSupplier()
    {
        return $this->belongsTo('App\User','name','user_id');
    }

}

