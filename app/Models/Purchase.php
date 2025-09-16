<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'purchase_vendor_id', 'id');
    }
    public function fromAccount()
    {
        return $this->belongsTo(AgencyAccount::class, 'purchase_account_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
