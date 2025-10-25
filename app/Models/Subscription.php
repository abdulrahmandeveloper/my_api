<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['customer_id', 'start_date', 'end_date', 'status'];
    public function customer() {
    return $this->belongsTo(Customer::class);
}

public function channels() {
    return $this->belongsToMany(Channel::class);
}

}
