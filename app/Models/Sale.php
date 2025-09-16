<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class)->where('status', 1);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'sale_customer_id', 'id');
    }
}
