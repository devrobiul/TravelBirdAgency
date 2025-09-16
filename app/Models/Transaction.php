<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];


    public function scopeType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function fromAccount()
    {
        return $this->belongsTo(AgencyAccount::class, 'from_account_id','id');
    }

    public function toAccount()
    {
        return $this->belongsTo(AgencyAccount::class, 'to_account_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
