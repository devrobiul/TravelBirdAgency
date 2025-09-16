<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected  $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'purchase_vendor_id', 'id');
    }
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }
    public function visa()
    {
        return $this->belongsTo(Visa::class, 'visa_id', 'id');
    }
    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }

    public function sales()
    {
        return $this->hasOne(Sale::class);
    }
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }


    public function group_ticket_sales()
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }
}
