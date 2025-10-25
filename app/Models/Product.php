<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
        protected $fillable = ['name', 'model', 'price', 'description'];

        
    public function category() {
    return $this->belongsTo(Category::class);
}

public function orders() {
    return $this->hasMany(Order::class);
}

}
