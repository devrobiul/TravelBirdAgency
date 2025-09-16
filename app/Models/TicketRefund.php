<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketRefund extends Model
{

    protected $table = 'ticket_refunds';
    protected $fillable = [
        'product_id',
        'refund_pnr',
        'user_id',
        'refund_vendor_id',
        'refund_amount',
        'customer_refund',
        'refund_profit',
        'refund_date',
        'refund_expected_date',
        'status',
        'profit_account_id',
    ];

    protected $dates = ['refund_date', 'refund_expected_date'];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'refund_vendor_id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
