<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_date',
    ];
    public function product() {
    return $this->belongsTo(Product::class);
}

    public function customer() {
    return $this->belongsTo(Customer::class);
}


}
