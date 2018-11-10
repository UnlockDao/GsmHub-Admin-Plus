<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class AdminPlus_SiteProfitDetails extends Model
{
    public $timestamps = false;
    protected $table = 'adminplus_site_profit_details';
    protected $primarykey = 'id';
    protected $table_fields = ['id','date_profit','date_added','date_updated','imei_profit_amount','reversed_amount','imei_linked_profit','file_profit_amount','server_profit_amount'];

    public function addNew($data_arr)
    {
        return $this->insertGetId($data_arr);
    }

}

