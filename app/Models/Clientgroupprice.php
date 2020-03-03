<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Clientgroupprice extends Model
{
    public $timestamps = false;
    protected $table = 'client_group_price';
    protected $fillable = [
        'group_id',
        'service_id',
        'service_type',
        'currency',
        'discount'
    ];

    public function chietkhau()
    {
        return $this->belongsTo('App\Models\Clientgroup', 'group_id', 'id');
    }
}

