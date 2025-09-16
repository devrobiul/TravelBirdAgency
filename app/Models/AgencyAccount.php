<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyAccount extends Model
{
    protected $guarded = [];


    public function transactionsOut()
    {
        return $this->hasMany(Transaction::class,'from_account_id');
    }


    public function transactionsIn()
    {
        return $this->hasMany(Transaction::class,'to_account_id');
    }
}
