<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function fromAccount()
    {
        return $this->belongsTo(AgencyAccount::class, 'account_id');
    }
}
