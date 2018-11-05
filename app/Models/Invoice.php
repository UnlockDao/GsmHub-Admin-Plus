<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Invoice extends Model
{
    public $timestamps = false;
    protected $table = 'invoice';
    protected $primarykey = 'id';
    protected $table_fields = ['id', 'user_id', 'date_added', 'date_paid', 'due_date', 'invoice_amount', 'total_tax_amount', 'invoice_total_amount', 'currency', 'set_as_paid_by', 'is_paid', 'created_by', 'admin_notes', 'user_notes', 'site_payment_transaction_id', 'payment_gateway', 'payment_gateway_fee', 'payment_gateway_status', 'payment_gateway_ref_id', 'sender_detail', 'assigned_to_staff_id', 'payment_gateway_receiver', 'manual_payment_notes', 'invoice_status', 'is_deleted'];


    public function user()
    {
        return $this->belongsTo('App\User','user_id','user_id');
    }
}

